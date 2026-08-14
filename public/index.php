<?php
declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ElectionController;
use App\Core\Router;
use App\Core\Session;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
use Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

$root = dirname(__DIR__);
if (is_file($root . '/.env')) {
    Dotenv::createImmutable($root)->safeLoad();
}
Session::start();

$router = new Router();
$router->get('/', static function (): void { header('Location: /login'); exit; });
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/dashboard', static function (): void {
    AuthMiddleware::handle();
    (new DashboardController())->index();
});

$router->get('/elections', static function (): void {
    AuthMiddleware::handle();
    RoleMiddleware::handle('admin', 'officer');
    (new ElectionController())->index();
});
$router->get('/elections/create', static function (): void {
    AuthMiddleware::handle();
    RoleMiddleware::handle('admin', 'officer');
    (new ElectionController())->create();
});
$router->post('/elections', static function (): void {
    AuthMiddleware::handle();
    RoleMiddleware::handle('admin', 'officer');
    (new ElectionController())->store();
});
$router->post('/elections/{id}/close', static function (string $id): void {
    AuthMiddleware::handle();
    RoleMiddleware::handle('admin', 'officer');
    (new ElectionController())->close($id);
});

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
