<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PanelDebugMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Skip static assets to reduce noise.
        if ($request->is('build/*') || $request->is('assets/*') || $request->is('vendor/*') || $request->is('livewire/*')) {
            return $next($request);
        }

        $this->sendDebugLog([
            'hypothesisId' => 'H1',
            'location' => 'PanelDebugMiddleware@handle',
            'message' => 'filament request start',
            'data' => [
                'path' => $request->path(),
                'isAjax' => $request->expectsJson(),
                'userId' => optional($request->user())->id,
                'guard' => config('auth.defaults.guard'),
                'method' => $request->method(),
            ],
        ]);

        $response = $next($request);

        $this->sendDebugLog([
            'hypothesisId' => 'H1',
            'location' => 'PanelDebugMiddleware@handle',
            'message' => 'filament request end',
            'data' => [
                'path' => $request->path(),
                'status' => $response->getStatusCode(),
                'isAjax' => $request->expectsJson(),
                'method' => $request->method(),
            ],
        ]);

        return $response;
    }

    private function sendDebugLog(array $payload): void
    {
        // #region agent log
        $logPath = base_path('.cursor/debug.log');
        $entry = array_merge([
            'sessionId' => 'debug-session',
            'runId' => 'pre-fix',
            'timestamp' => (int) (microtime(true) * 1000),
        ], $payload);

        file_put_contents($logPath, json_encode($entry) . PHP_EOL, FILE_APPEND);
        // #endregion
    }
}

