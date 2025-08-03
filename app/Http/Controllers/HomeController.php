<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Jika user role-nya admin, cashier, kitchen, atau waiter
        if (in_array(auth()->user()->role, ['admin', 'cashier', 'kitchen', 'waiter'])) {
            return redirect()->route('admin.dashboard');
        }
        
        // Jika user role-nya customer atau role lain, tampilkan home
        return view('home');
    }
}
