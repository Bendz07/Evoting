<?php
declare(strict_types=1);

use App\Core\Session;
use Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
if (is_file(dirname(__DIR__) . '/.env')) {
    $dotenv->safeLoad();
}

Session::start();

$app = require dirname(__DIR__) . '/config/app.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($app['name'], ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-primary shadow-sm">
    <div class="container"><a class="navbar-brand fw-bold" href="/">E-Voting</a></div>
</nav>
<main class="container py-5">
    <div class="p-5 bg-white rounded-4 shadow-sm">
        <span class="badge text-bg-success mb-3">Phase 1 initialized</span>
        <h1 class="display-5 fw-bold">Secure E-Voting System</h1>
        <p class="lead text-secondary">PHP 8+ / MySQL 8+ modular voting platform.</p>
        <hr>
        <p class="mb-0">Core architecture and security foundation are ready. Feature modules will be added in the following implementation phases.</p>
    </div>
</main>
</body>
</html>
