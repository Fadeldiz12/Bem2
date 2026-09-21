<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware role untuk modul LPJ/Proposal.
 *
 * Sebelumnya modul ini punya sesi sendiri (Session::get('user_id') dkk).
 * Setelah digabung, ia memakai sesi yang sama dengan admin panel utama
 * (session('admin_id'), session('admin_role'), diisi oleh
 * Admin\AuthController::doLogin()).
 *
 * Dipakai tanpa parameter ("role") untuk memastikan sudah login (role apa
 * saja), atau dengan parameter ("role:super_admin,admin") untuk membatasi
 * ke role tertentu.
 */
class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!session('admin_logged_in') || !session('admin_id')) {
            return redirect()->route('admin.login')
                ->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $userRole = session('admin_role');
        $userName = session('admin_name');

        if (!empty($roles) && !in_array($userRole, $roles, true)) {
            Log::warning("Unauthorized access: {$userName} ({$userRole}) tried to access " . $request->url());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'Anda tidak memiliki akses.',
                ], 403);
            }

            return redirect()->route('dashboard')
                ->with('error', "Akses ditolak! Role {$userRole} tidak diizinkan.");
        }

        return $next($request);
    }
}
