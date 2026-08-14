<?php
declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class CSRF
{
    public static function token(): string
    {
        Session::start();
        if (!isset($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }

    public static function verify(?string $token): void
    {
        $expected = Session::get('_csrf');
        if (!$expected || !$token || !hash_equals($expected, $token)) {
            throw new RuntimeException('Invalid CSRF token.');
        }
    }
}
