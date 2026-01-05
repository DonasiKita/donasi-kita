<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Dashboard admin
     */
    public function dashboard(): View
    {
        return view('admin.dashboard');
    }

    /**
     * Halaman BGDN admin
     */
    public function bgdn(): View
    {
        return view('admin.bgdn');
    }
}
