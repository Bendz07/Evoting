<?php
declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        $file = dirname(__DIR__, 2) . '/views/' . $view . '.php';
        if (!is_file($file)) {
            http_response_code(500);
            echo 'View not found.';
            return;
        }
        extract($data, EXTR_SKIP);
        require $file;
    }

    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
