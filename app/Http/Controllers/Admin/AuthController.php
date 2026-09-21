<?php
// Path asli: app/Http/Controllers/Admin/AuthController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // =========================================================
    // LOGIN
    // =========================================================

    public function login()
    {
        if (session('admin_logged_in')) {
            if (session('admin_role') === 'super_admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard');
        }
        return view('admin.login');
    }

    public function doLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|max:255',
            'password' => 'required|string|min:1|max:255',
        ]);

        $throttleKey = Str::lower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.");
        }

        $user = User::where('email', $request->email)
                    ->where('is_active', true)
                    ->first();

        if ($user && Hash::check($request->password, $user->password_hash)) {

            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            session([
                'admin_logged_in' => true,
                'admin_id'        => $user->id,
                'admin_name'      => $user->name,
                'admin_email'     => $user->email,
                'admin_role'      => $user->role,
            ]);

            // Simpan session ID ke database supaya bisa paksa logout
            $user->update([
                'last_login' => now(),
                'session_id' => session()->getId(),
            ]);

            if ($user->role === 'super_admin') {
                return redirect()->route('admin.dashboard');
            }

            if (in_array($user->role, ['admin', 'user'], true)) {
                return redirect()->route('dashboard');
            }

            return redirect()->route('dashboard');
        }

        RateLimiter::hit($throttleKey, 60);
        return back()->with('error', 'Email atau password salah.');
    }

    public function logout(Request $request)
    {
        $adminId = session('admin_id');
        if ($adminId) {
            User::where('id', $adminId)->update(['session_id' => null]);
        }

        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->route('admin.login');
    }


    public function showRegister()
    {
        return view('admin.register');
    }

    public function doRegister(Request $request)
    {
        $throttleKey = 'register|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()
                ->withInput()
                ->with('error', "Terlalu banyak pendaftaran. Coba lagi dalam {$seconds} detik.");
        }

        $request->validate([
            'name'     => [
                'required', 'string', 'min:3', 'max:100',
                'regex:/^[\pL\s\-]+$/u',
            ],
            'email'    => [
                'required', 'email:rfc,dns', 'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required', 'string', 'min:8', 'max:255',
                'confirmed',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/',
            ],
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'name.min'           => 'Nama minimal 3 karakter.',
            'name.regex'         => 'Nama hanya boleh berisi huruf dan spasi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex'     => 'Password harus mengandung minimal 1 huruf dan 1 angka.',
        ]);

        User::create([
            'name'          => strip_tags(trim($request->name)),
            'email'         => strtolower(trim($request->email)),
            'password_hash' => Hash::make($request->password),
            'role'          => 'user',
            'is_active'     => false, // Membutuhkan persetujuan & aktivasi Super Admin demi keamanan
            'last_login'    => null,
            'session_id'    => null,
        ]);

        RateLimiter::hit($throttleKey, 600);

        return redirect()
            ->route('admin.login')
            ->with('success', 'Pendaftaran berhasil! Akun Anda sedang menunggu persetujuan dan aktivasi oleh Super Admin.');
    }

    // =========================================================
    // FORGOT / RESET PASSWORD (kirim OTP ke Gmail)
    // =========================================================

    // Tampilkan form "lupa password" (input email)
    public function showForgotPassword()
    {
        return view('admin.forgot-password');
    }

    // Proses kirim OTP ke email
    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $email       = strtolower(trim($request->email));
        $throttleKey = 'otp-send|' . $email . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.");
        }

        RateLimiter::hit($throttleKey, 300); // maksimal 3x kirim per 5 menit

        $user = User::where('email', $email)
                    ->where('is_active', true)
                    ->first();

        // Selalu tampilkan pesan sukses yang sama walau email tidak terdaftar,
        // supaya orang lain tidak bisa menebak email admin mana yang valid.
        if ($user) {
            $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $user->update([
                'reset_otp'            => Hash::make($otp),
                'reset_otp_expires_at' => now()->addMinutes(10),
            ]);

            Mail::to($user->email)->send(new OtpMail($otp, $user->name));
        }

        session(['reset_email' => $email]);

        return redirect()->route('admin.reset-password')
            ->with('success', 'Jika email terdaftar, kode OTP telah dikirim ke Gmail Anda. Cek juga folder Spam.');
    }

    // Tampilkan form input OTP + password baru
    public function showResetPassword()
    {
        if (!session('reset_email')) {
            return redirect()->route('admin.forgot-password');
        }
        return view('admin.reset-password', ['email' => session('reset_email')]);
    }

    // Kirim ulang OTP (dari halaman reset-password)
    public function resendOtp(Request $request)
    {
        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('admin.forgot-password');
        }

        $request->merge(['email' => $email]);
        return $this->sendResetOtp($request);
    }

    // Verifikasi OTP dan simpan password baru
    public function resetPassword(Request $request)
    {
        $email = session('reset_email');

        if (!$email) {
            return redirect()->route('admin.forgot-password')
                ->with('error', 'Sesi reset password sudah habis, silakan ulangi.');
        }

        $request->validate([
            'otp'      => 'required|digits:6',
            'password' => [
                'required', 'string', 'min:8', 'max:255',
                'confirmed',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/',
            ],
        ], [
            'otp.required'        => 'Kode OTP wajib diisi.',
            'otp.digits'          => 'Kode OTP harus 6 digit angka.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex'     => 'Password harus mengandung minimal 1 huruf dan 1 angka.',
        ]);

        $throttleKey = 'otp-verify|' . $email . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Terlalu banyak percobaan salah. Coba lagi dalam {$seconds} detik.");
        }

        $user = User::where('email', $email)->first();

        $valid = $user
            && $user->reset_otp
            && $user->reset_otp_expires_at
            && now()->lessThan($user->reset_otp_expires_at)
            && Hash::check($request->otp, $user->reset_otp);

        if (!$valid) {
            RateLimiter::hit($throttleKey, 300);
            return back()->with('error', 'Kode OTP salah atau sudah kedaluwarsa.');
        }

        RateLimiter::clear($throttleKey);

        $user->update([
            'password_hash'        => Hash::make($request->password),
            'reset_otp'            => null,
            'reset_otp_expires_at' => null,
            'session_id'           => null, // paksa logout semua sesi lama demi keamanan
        ]);

        $request->session()->forget('reset_email');

        return redirect()->route('admin.login')
            ->with('success', 'Password berhasil direset! Silakan masuk dengan password baru Anda.');
    }
}
