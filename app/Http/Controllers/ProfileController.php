<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('admin.profiles.index', compact('user'));
    }
    public function edit(User $user)
    {
        $user = Auth::user();
        return view('admin.profiles.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
        ]);
        $user->update($validated);

        return redirect()->route('admin.profiles.index')
            ->with('success', 'Profile updated successfully!');
    }

    public function deleteConfirm()
    {
        $user = Auth::user();
        return view('admin.profiles.delete', compact('user'));
    }
    public function destroy(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'password' => 'required|string|current_password',
            'confirmation' => 'required|accepted',
        ]);
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('success', 'Your account has been deleted successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required|string|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.profiles.index')
            ->with('success', 'Password updated successfully!');
    }

}
