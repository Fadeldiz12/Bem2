<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $role   = $request->get('role', '');

        $users = User::query()
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
                                        ->orWhere('email', 'like', "%{$search}%"))
            ->when($role, fn($q) => $q->where('role', $role))
            ->latest()
            ->get();

        // Ambil semua session_id aktif dari tabel sessions
        $activeSessions = DB::table('sessions')->pluck('id')->toArray();

        return view('admin.users.index', compact('users', 'search', 'role', 'activeSessions'));
    }

    public function toggleActive(int $id)
    {
        if ($id === session('admin_id')) {
            return back()->with('error', 'Tidak bisa menonaktifkan akun sendiri.');
        }

        return DB::transaction(function () use ($id) {
            $user = User::lockForUpdate()->findOrFail($id);

            $isDeactivatingActiveSuperAdmin = $user->role === 'super_admin' && $user->is_active;

            if ($isDeactivatingActiveSuperAdmin && $this->otherActiveSuperAdminCount($user->id) === 0) {
                return back()->with('error', 'Tidak bisa menonaktifkan. Minimal harus ada 1 Super Admin aktif di sistem.');
            }

            $user->update(['is_active' => !$user->is_active]);
            $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return back()->with('success', "Akun {$user->name} berhasil {$status}.");
        });
    }

    public function updateRole(Request $request, int $id)
    {
        $request->validate(['role' => 'required|in:super_admin,admin,user']);

        if ($id === session('admin_id')) {
            return back()->with('error', 'Tidak bisa mengubah role akun sendiri.');
        }

        return DB::transaction(function () use ($request, $id) {
            $user = User::lockForUpdate()->findOrFail($id);

            $isDemotingActiveSuperAdmin = $user->role === 'super_admin'
                && $user->is_active
                && $request->role !== 'super_admin';

            if ($isDemotingActiveSuperAdmin && $this->otherActiveSuperAdminCount($user->id) === 0) {
                return back()->with('error', 'Tidak bisa mengubah role. Minimal harus ada 1 Super Admin aktif di sistem.');
            }

            $user->update(['role' => $request->role]);
            return back()->with('success', "Role {$user->name} berhasil diubah.");
        });
    }

    public function forceLogout(int $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === session('admin_id')) {
            return back()->with('error', 'Tidak bisa logout akun sendiri.');
        }

        if ($user->session_id) {
            // Hapus session dari tabel sessions → user otomatis ter-logout
            DB::table('sessions')->where('id', $user->session_id)->delete();
            $user->update(['session_id' => null]);
        }

        return back()->with('success', "Sesi {$user->name} berhasil dihapus. User telah di-logout.");
    }

    public function destroy(int $id)
    {
        if ($id === session('admin_id')) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        return DB::transaction(function () use ($id) {
            $user = User::lockForUpdate()->findOrFail($id);

            $isDeletingActiveSuperAdmin = $user->role === 'super_admin' && $user->is_active;

            if ($isDeletingActiveSuperAdmin && $this->otherActiveSuperAdminCount($user->id) === 0) {
                return back()->with('error', 'Tidak bisa menghapus. Minimal harus ada 1 Super Admin aktif di sistem.');
            }

            // Hapus session juga saat user dihapus
            if ($user->session_id) {
                DB::table('sessions')->where('id', $user->session_id)->delete();
            }

            $user->delete();
            return back()->with('success', "Akun {$user->name} berhasil dihapus.");
        });
    }

    /**
     * Hitung jumlah super_admin aktif SELAIN user dengan id ini.
     */
    private function otherActiveSuperAdminCount(int $excludeId): int
    {
        return User::where('role', 'super_admin')
            ->where('is_active', true)
            ->where('id', '!=', $excludeId)
            ->lockForUpdate()
            ->count();
    }
}