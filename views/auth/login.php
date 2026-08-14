<?php declare(strict_types=1); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - E-Voting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5"><div class="row justify-content-center"><div class="col-md-5">
<div class="card border-0 shadow-sm rounded-4"><div class="card-body p-4">
<h2 class="fw-bold mb-1">Sign in</h2><p class="text-secondary">Access the E-Voting platform.</p>
<?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<form method="post" action="/login">
<input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
<div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required autocomplete="username"></div>
<div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required autocomplete="current-password"></div>
<button class="btn btn-primary w-100">Sign in</button>
</form></div></div></div></div></div>
</body></html>
