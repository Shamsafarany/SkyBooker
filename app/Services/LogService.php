<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LogService
{
    public static function info(string $channel, string $message, array $context = [])
    {
        Log::channel($channel)->info($message, $context);
    }

    public static function warning(string $channel, string $message, array $context = [])
    {
        Log::channel($channel)->warning($message, $context);
    }

    public static function error(string $channel, string $message, array $context = [])
    {
        Log::channel($channel)->error($message, $context);
    }

    public static function critical(string $channel, string $message, array $context = [])
    {
        Log::channel($channel)->critical($message, $context);
    }

    // Convenience wrappers
    public static function system(string $message, array $context = [])
    {
        self::info('system', $message, $context);
    }

    public static function booking(string $message, array $context = [])
    {
        self::info('booking', $message, $context);
    }

    public static function auth(string $message, array $context = [])
    {
        self::info('auth', $message, $context);
    }
}
