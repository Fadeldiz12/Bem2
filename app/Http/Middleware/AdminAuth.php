<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // =====================================================
        // CEK 1: Session login ada sama sekali?
        // =====================================================
        if (!session('admin_logged_in') || !session('admin_id')) {
            return redirect()->route('admin.login')
                             ->with('error', 'Silakan masuk terlebih dahulu.');
        }

        // =====================================================
        // CEK 2: Verifikasi ke database (bukan hanya session)
        // =====================================================
        // Kenapa ini penting?
        // Tanpa cek ini: jika admin dihapus/dinonaktifkan, dia masih bisa
        // akses sampai session expired (bisa berjam-jam!)
        //
        // Dengan cek ini: setiap request diverifikasi ke DB secara real-time.
        // Begitu is_active=false → langsung dikeluarkan.
        // =====================================================
        $adminId = session('admin_id');
        $user = User::find($adminId);

        if (!$user || !$user->isActive()) {
            // Akun benar-benar sudah tidak valid/dinonaktifkan → paksa logout.
            session()->flush();
            session()->regenerate();

            return redirect()->route('admin.login')
                             ->with('error', 'Akses Anda telah dicabut. Hubungi administrator.');
        }

        // =====================================================
        // CEK 3: Role harus super_admin untuk panel ini
        // =====================================================
        // PENTING: role 'admin'/'user' itu akun yang SAH (bukan dihapus/
        // dinonaktifkan), cuma tidak berhak ke panel admin organisasi ini.
        // Jangan flush session mereka — itu bikin mereka kelihatan
        // "ke-logout otomatis" padahal sesi modul LPJ mereka masih valid.
        // Cukup tolak & arahkan balik ke dashboard yang memang haknya.
        // =====================================================
        if (!$user->isSuperAdmin()) {
            return redirect()->route('dashboard')
                             ->with('error', 'Akses ditolak. Halaman ini khusus Super Admin.');
        }

        if (session('admin_name') !== $user->name) {
            session(['admin_name' => $user->name]);
        }

        return $next($request);
    }
}