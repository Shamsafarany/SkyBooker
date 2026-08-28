<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Services\LogService;


class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted',
        ]);
        $username = $validated['username'] ?? $this->generateUsername(
            $validated['first_name'],
            $validated['last_name']
        );

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'username' => $username,
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
        ]);
        LogService::auth('REGISTRATION SUCCESS', ['user_id' => $user->id]);
        Auth::login($user);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Account created successfully! Welcome ' . $user->getFullNameAttribute() . '!');
    }
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    private function generateUsername(string $firstName, string $lastName): string
    {
        $baseUsername = strtolower($firstName . $lastName);
        
        $baseUsername = preg_replace('/[^a-z0-9]/', '', $baseUsername);
        
        $username = $baseUsername;
        $counter = 1;
        
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        return $username;
    }
}
