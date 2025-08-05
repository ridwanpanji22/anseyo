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
        // Redirect berdasarkan role
        $user = auth()->user();
        
        if ($user->role === 'kitchen') {
            return redirect()->route('kitchen.dashboard');
        } elseif ($user->role === 'waiter') {
            return redirect()->route('waiter.dashboard');
        } elseif ($user->role === 'cashier') {
            return redirect()->route('cashier.dashboard');
        } elseif (in_array($user->role, ['admin'])) {
            return redirect()->route('admin.dashboard');
        }
        
        // Jika user role-nya customer atau role lain, tampilkan home
        return view('home');
    }
}
