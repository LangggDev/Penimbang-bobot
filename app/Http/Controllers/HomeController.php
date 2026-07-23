<?php

namespace App\Http\Controllers;

/**
 * HomeController
 *
 * Mengontrol route-route umum yang tidak terikat ke role tertentu:
 *  - Redirect dari root (/) ke halaman login.
 *  - Redirect dari /dashboard ke dashboard sesuai role pengguna.
 *  - Redirect dari /profile ke dashboard.
 */
class HomeController extends Controller
{
    /**
     * Redirect ke halaman login.
     *
     * Route: GET /  (home)
     */
    public function home()
    {
        return redirect()->route('login');
    }

    /**
     * Redirect ke dashboard sesuai role pengguna yang sedang login.
     *
     * Route: GET /dashboard  (dashboard)
     */
    public function redirectDashboard()
    {
        $user = auth()->user();
        $role = strtolower($user->role ?? '');

        if ($role === 'qc') {
            return redirect()->route('qc.dashboard');
        }

        if ($role === 'penimbang') {
            return redirect()->route('penimbang.dashboard');
        }

        if ($role === 'kasir') {
            return redirect()->route('kasir.dashboard');
        }

        return redirect()->route('login');
    }

    /**
     * Redirect ke dashboard (alias untuk halaman profile yang belum diimplementasi).
     *
     * Route: GET /profile  (profile.edit)
     */
    public function redirectProfile()
    {
        return redirect()->route('dashboard');
    }
}
