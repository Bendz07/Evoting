<?php declare(strict_types=1); ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard - E-Voting</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/assets/css/app.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-primary shadow-sm"><div class="container-fluid px-4"><a class="navbar-brand fw-bold" href="/dashboard">E-Voting</a><div class="text-white"><span class="me-3"><?= htmlspecialchars($user['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></span><a href="/logout" class="btn btn-sm btn-light">Logout</a></div></div></nav>
<main class="container-fluid px-4 py-4">
<div class="d-flex justify-content-between align-items-center mb-4"><div><h1 class="h3 mb-1">Dashboard</h1><p class="text-secondary mb-0">Welcome back, <?= htmlspecialchars($user['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>.</p></div><span class="badge text-bg-primary text-uppercase"><?= htmlspecialchars($user['role'] ?? '', ENT_QUOTES, 'UTF-8') ?></span></div>
<div class="row g-4">
<?php foreach ([['elections','Elections'],['active_elections','Active Elections'],['candidates','Candidates'],['voters','Voters'],['votes','Votes Cast']] as [$key,$label]): ?>
<div class="col-sm-6 col-xl"><div class="card border-0 shadow-sm h-100 rounded-4"><div class="card-body"><div class="text-secondary small"><?= $label ?></div><div class="display-6 fw-bold mt-2"><?= (int)$stats[$key] ?></div></div></div></div>
<?php endforeach; ?>
</div>
<div class="row g-4 mt-1"><div class="col-lg-8"><div class="card border-0 shadow-sm rounded-4"><div class="card-body p-4"><h2 class="h5">System status</h2><p class="text-secondary mb-0">Core authentication, database access and role-aware dashboard are connected. Election management is the next module.</p></div></div></div><div class="col-lg-4"><div class="card border-0 shadow-sm rounded-4"><div class="card-body p-4"><h2 class="h5">Security</h2><ul class="list-unstyled mb-0"><li>✓ Secure sessions</li><li>✓ CSRF protection</li><li>✓ Password hashing</li><li>✓ Role authorization</li></ul></div></div></div></div>
</main></body></html>
