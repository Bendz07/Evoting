<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;

final class RoleMiddleware
{
    public static function handle(string ...$roles): void
    {
        if (!Auth::check() || !Auth::hasRole(...$roles)) {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }
    }
}
