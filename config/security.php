<?php
declare(strict_types=1);

return [
    'session_lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 7200),
    'csrf_bytes' => 32,
    'password_algorithm' => PASSWORD_DEFAULT,
];
