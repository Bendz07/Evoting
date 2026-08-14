<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\CSRF;
use App\Core\Database;
use App\Core\Session;

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->view('auth/login', ['csrf' => CSRF::token()]);
    }

    public function login(): void
    {
        CSRF::verify($_POST['_csrf'] ?? null);
        $email = filter_var(trim((string)($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
        $password = (string)($_POST['password'] ?? '');

        if (!$email || $password === '') {
            $this->view('auth/login', ['csrf' => CSRF::token(), 'error' => 'Invalid credentials.']);
            return;
        }

        $db = Database::connection();
        $stmt = $db->prepare('SELECT u.id, u.name, u.email, u.password_hash, u.status, r.name AS role FROM users u JOIN roles r ON r.id = u.role_id WHERE u.email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        $valid = $user && $user['status'] === 'active' && password_verify($password, $user['password_hash']);
        $attempt = $db->prepare('INSERT INTO login_attempts (email, ip_address, successful) VALUES (:email, :ip, :successful)');
        $attempt->execute(['email' => $email, 'ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0', 'successful' => $valid ? 1 : 0]);

        if (!$valid) {
            $this->view('auth/login', ['csrf' => CSRF::token(), 'error' => 'Invalid credentials.']);
            return;
        }

        Auth::login($user);
        $db->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id')->execute(['id' => $user['id']]);
        header('Location: /dashboard');
        exit;
    }

    public function logout(): void
    {
        Auth::logout();
        header('Location: /login');
        exit;
    }
}
