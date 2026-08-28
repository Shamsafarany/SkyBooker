<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Services\LogService;



class UserObserver
{
    public function created(User $user)
    {
        try {
            LogService::auth('USER CREATED', ['user_id' => $user->id]);
        } catch (\Throwable $e) {
            LogService::error('auth', "AUTH OBSERVER ERROR (created)", [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function updated(User $user)
    {
        try {
            LogService::auth('USER UPDATED', ['user_id' => $user->id]);
        } catch (\Throwable $e) {
            LogService::error('auth', "AUTH OBSERVER ERROR (updated)", [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function deleted(User $user)
    {
        try {
            LogService::auth('USER DELETED', ['user_id' => $user->id]);
        } catch (\Throwable $e) {
            LogService::error('auth', "AUTH OBSERVER ERROR (deleted)", [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
