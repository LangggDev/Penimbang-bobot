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

        if ($user->role === 'qc') {
            return redirect()->route('qc.dashboard');
        }

        if ($user->role === 'penimbang') {
            return redirect()->route('penimbang.dashboard');
        }

        if ($user->role === 'kasir') {
            return redirect()->route('kasir.dashboard');
        }

        abort(403, 'Role tidak dikenali.');
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
