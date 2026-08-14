<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $user = Auth::user();
        $db = Database::connection();

        $stats = [
            'elections' => (int)$db->query('SELECT COUNT(*) FROM elections')->fetchColumn(),
            'active_elections' => (int)$db->query("SELECT COUNT(*) FROM elections WHERE status = 'active'")->fetchColumn(),
            'candidates' => (int)$db->query('SELECT COUNT(*) FROM candidates WHERE status = \'active\'')->fetchColumn(),
            'voters' => (int)$db->query('SELECT COUNT(*) FROM voters')->fetchColumn(),
            'votes' => (int)$db->query('SELECT COUNT(*) FROM votes')->fetchColumn(),
        ];

        $this->view('dashboard/index', compact('user', 'stats'));
    }
}
