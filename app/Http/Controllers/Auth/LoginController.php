<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use App\Services\LogService;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            $request->session()->regenerate();
            LogService::auth('LOGIN SUCCESS', ['user_id' => $user->id]);
            return redirect()->intended('admin/dashboard');
        }
        LogService::auth('LOGIN FAILED', ['email' => $request->email]);

        return back()->withErrors([
            'email' => 'The provided credentials do not match.',
        ])->onlyInput('email'); 
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            LogService::auth('LOGOUT SUCCESS', ['user_id' => $user->id]);
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Logged out successfully!');
    }
}
