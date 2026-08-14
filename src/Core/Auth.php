<?php
declare(strict_types=1);

namespace App\Core;

final class Auth
{
    public static function login(array $user): void
    {
        Session::regenerate();
        Session::set('user', [
            'id' => (int)$user['id'],
            'role' => $user['role'],
            'email' => $user['email'],
            'name' => $user['name'] ?? '',
        ]);
    }

    public static function logout(): void
    {
        Session::destroy();
    }

    public static function check(): bool
    {
        return Session::get('user') !== null;
    }

    public static function user(): ?array
    {
        return Session::get('user');
    }

    public static function hasRole(string ...$roles): bool
    {
        $user = self::user();
        return $user !== null && in_array($user['role'], $roles, true);
    }
}
