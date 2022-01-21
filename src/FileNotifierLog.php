<?php

declare(strict_types=1);

namespace Netzindianer\FileNotifier;

use Illuminate\Support\Facades\Log;

class FileNotifierLog
{
    public static function debug(string $message, array $context = []): void
    {
        if (!config('file-notifier.debug')) {
            return;
        }
        
        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/file-notifier.debug.log')
        ])
            ->debug($message, $context);
    }
}
