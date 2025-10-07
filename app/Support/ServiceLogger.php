<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Class ServiceLogger
 *
 * A helper class for unified logging across all service layers.
 * It provides consistent error and warning logging with optional
 * stack traces based on the application's debug configuration.
 *
 * Usage example:
 * ServiceLogger::error($exception, 'AuthService@register');
 * ServiceLogger::warning('User email not verified', ['user_id' => 5]);
 *
 * @package App\Support
 */
class ServiceLogger
{
    /**
     * Log an exception with contextual information.
     *
     * In debug mode, the full stack trace will be included in the log.
     * In production mode (APP_DEBUG = false), the trace will be omitted
     * to avoid leaking sensitive information.
     *
     * @param  Throwable  $e        The caught exception instance.
     * @param  string|null  $context  Optional context name (e.g., 'AuthService@register').
     * @return void
     */
    public static function error(Throwable $e, string $context = null): void
    {
        Log::error($context ?? 'Service Error', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ]);
    }

    /**
     * Log a non-critical warning message.
     *
     * Useful for reporting recoverable situations or unexpected conditions
     * that do not throw an exception but should be tracked.
     *
     * @param  string  $message  The warning message.
     * @param  array   $data     Additional context data.
     * @return void
     */
    public static function warning(string $message, array $data = []): void
    {
        Log::warning($message, $data);
    }
}
