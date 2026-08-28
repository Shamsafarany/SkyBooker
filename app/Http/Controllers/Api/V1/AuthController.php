<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Services\LogService;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = User::create([
                'first_name'    => $validated['first_name'],
                'last_name'     => $validated['last_name'],
                'email'         => $validated['email'],
                'username'      => $validated['username'],
                'phone'         => $validated['phone'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'password'      => Hash::make($validated['password']),
                'role'          => $validated['role'] ?? 'passenger',
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            LogService::auth('REGISTER SUCCESS', ['user_id' => $user->id]);

            return Response::success([
                'user'       => new UserResource($user),
                'token'      => $token,
                'token_type' => 'Bearer',
            ], 'User registered', 201);

        } catch (\Throwable $e) {
            LogService::auth('REGISTER ERROR', ['error' => $e->getMessage()]);
            return Response::error('Registration failed', 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $validated = $request->validated();

            $credentials = [
                'email'    => $validated['email'],
                'password' => $validated['password'],
            ];

            if (!Auth::attempt($credentials)) {
                LogService::auth('LOGIN FAILED', ['email' => $validated['email']]);
                return Response::error('Invalid credentials', 401);
            }

            $user  = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            LogService::auth('LOGIN SUCCESS', ['user_id' => $user->id]);

            return Response::success([
                'user'       => new UserResource($user),
                'token'      => $token,
                'token_type' => 'Bearer',
            ], 'Login successful');

        } catch (\Throwable $e) {
            LogService::auth('LOGIN ERROR', ['error' => $e->getMessage()]);
            return Response::error('Login failed', 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            $user->currentAccessToken()->delete();

            LogService::auth('LOGOUT SUCCESS', ['user_id' => $user->id]);

            return Response::success(null, 'Logged out');

        } catch (\Throwable $e) {
            LogService::auth('LOGOUT ERROR', ['error' => $e->getMessage()]);
            return Response::error('Logout failed', 500);
        }
    }

    public function user(Request $request)
    {
        try {
            return Response::success(
                new UserResource($request->user()),
                'Authenticated user retrieved'
            );

        } catch (\Throwable $e) {
            LogService::auth('USER FETCH ERROR', ['error' => $e->getMessage()]);
            return Response::error('Failed to retrieve user', 500);
        }
    }
}
