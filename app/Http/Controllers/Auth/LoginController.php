<?php

namespace App\Http\Controllers\Auth;

use App\Events\PasswordResetRequested;
use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\LogService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors([
            'email' => 'No user registered with this email.',
            ])->onlyInput('email'); 
        }
        
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

    public function sendResetLink(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
            'email' => 'No user registered with this email.',
            ])->onlyInput('email'); 
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => hash('sha256', $token),
                'created_at' => now()
            ]
        );
        event(new PasswordResetRequested($user, $token));

        return back()->with('success', 'Password reset link sent to your email.');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $hashed = hash('sha256', $request->token);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $hashed)
            ->first();

        if (!$record) {
            return back()->withErrors(['token' => 'Invalid or expired reset token.']);
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        LogService::warning('auth', 'PASSWORD UPDATED', ['email' => $request->email]);

        return redirect('/login')->with('success', 'Password reset successfully!');
    }


}
