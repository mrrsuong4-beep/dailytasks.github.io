<?php
require __DIR__ . '/../app/bootstrap.php';
if (!empty($_SESSION['user'])) redirect('index.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
    $stmt->execute([trim($_POST['username'] ?? '')]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'] ?? '', $user['password'])) {
        $_SESSION['user'] = ['id'=>$user['id'], 'name'=>$user['name'], 'role'=>$user['role']];
        redirect('index.php');
    }
    $error = 'ឈ្មោះអ្នកប្រើ ឬពាក្យសម្ងាត់មិនត្រឹមត្រូវ។';
}
?>
<!doctype html><html lang="km"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login — Attendance Pro</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/css/app.css" rel="stylesheet">
</head><body class="login-page">
<div class="login-box">
  <div class="brand-mark"><i class="bi bi-calendar2-check"></i></div>
  <h2>Attendance Pro</h2><p class="text-muted">ចូលប្រើប្រព័ន្ធគ្រប់គ្រងវត្តមាន</p>
  <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
  <form method="post">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
    <label>Username</label><input class="form-control mb-3" name="username" required autofocus>
    <label>Password</label><input class="form-control mb-4" type="password" name="password" required>
    <button class="btn btn-primary w-100 py-2">ចូលប្រព័ន្ធ</button>
  </form>
  <small class="text-muted d-block mt-3">Demo: admin / admin123</small>
</div>
</body></html>
