<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\CSRF;
use App\Core\Database;
use App\Core\Auth;
use RuntimeException;

final class ElectionController extends Controller
{
    public function index(): void
    {
        $stmt = Database::connection()->query('SELECT e.*, u.name AS creator_name FROM elections e JOIN users u ON u.id = e.created_by ORDER BY e.starts_at DESC');
        $this->view('elections/index', ['elections' => $stmt->fetchAll(), 'csrf' => CSRF::token()]);
    }

    public function create(): void
    {
        $this->view('elections/create', ['csrf' => CSRF::token()]);
    }

    public function store(): void
    {
        CSRF::verify($_POST['_csrf'] ?? null);
        $title = trim((string)($_POST['title'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $type = trim((string)($_POST['election_type'] ?? ''));
        $starts = trim((string)($_POST['starts_at'] ?? ''));
        $ends = trim((string)($_POST['ends_at'] ?? ''));

        if ($title === '' || $type === '' || $starts === '' || $ends === '' || strtotime($starts) === false || strtotime($ends) === false || strtotime($ends) <= strtotime($starts)) {
            $this->view('elections/create', ['csrf' => CSRF::token(), 'error' => 'Please provide valid election data and a valid date range.']);
            return;
        }

        $stmt = Database::connection()->prepare('INSERT INTO elections (title, description, election_type, starts_at, ends_at, status, created_by) VALUES (:title, :description, :type, :starts_at, :ends_at, :status, :created_by)');
        $stmt->execute([
            'title' => $title,
            'description' => $description ?: null,
            'type' => $type,
            'starts_at' => date('Y-m-d H:i:s', strtotime($starts)),
            'ends_at' => date('Y-m-d H:i:s', strtotime($ends)),
            'status' => strtotime($starts) > time() ? 'scheduled' : 'active',
            'created_by' => Auth::user()['id'],
        ]);
        header('Location: /elections');
        exit;
    }

    public function close(string $id): void
    {
        CSRF::verify($_POST['_csrf'] ?? null);
        if (!ctype_digit($id)) throw new RuntimeException('Invalid election identifier.');
        $stmt = Database::connection()->prepare("UPDATE elections SET status = 'closed' WHERE id = :id AND status IN ('scheduled','active')");
        $stmt->execute(['id' => (int)$id]);
        header('Location: /elections');
        exit;
    }
}
