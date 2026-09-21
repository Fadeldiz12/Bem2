<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class AdminSessionExpiry
{
    public function handle(Request $request, Closure $next)
    {
        $adminId = session('admin_id');

        if ($adminId) {
            $user = User::find($adminId);

            $expired = !$user
                || !$user->last_login
                || Carbon::parse($user->last_login)->addDay()->isPast();

            if ($expired) {
                // Samain sama logic di logout() — bersihin session_id juga
                User::where('id', $adminId)->update(['session_id' => null]);

                $request->session()->flush();
                $request->session()->regenerate();

                return redirect()->route('admin.login')
                    ->with('error', 'Sesi kamu sudah habis, silakan login lagi.');
            }
        }

        return $next($request);
    }
}