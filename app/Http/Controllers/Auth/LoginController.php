<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            Log::channel('auth')->info('User logged in successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
            return redirect()->intended('admin/dashboard');
        }
        Log::channel('auth')->warning('Failed login attempt', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'attempted_at' => now(),
        ]);

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
            Log::channel('auth')->info('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
            ]);
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Logged out successfully!');
    }
}
