<?php
require_once dirname(__DIR__).'/config.php'; require_once dirname(__DIR__).'/includes/functions.php';
if (is_admin()) { header('Location: '.BASE_URL.'admin/index.php'); exit; }
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    csrf_check();
    $username=trim($_POST['username'] ?? ''); $password=$_POST['password'] ?? '';
    $stmt=$pdo->prepare('SELECT id,username,password_hash FROM admins WHERE username=? LIMIT 1'); $stmt->execute([$username]); $admin=$stmt->fetch();
    if ($admin && password_verify($password,$admin['password_hash'])) {
        session_regenerate_id(true); $_SESSION['admin_id']=(int)$admin['id']; $_SESSION['admin_username']=$admin['username'];
        header('Location: '.BASE_URL.'admin/index.php'); exit;
    }
    $error='Username atau password salah.';
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login Admin - Keisha Mobil</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"><link href="<?= BASE_URL ?>assets/style.css" rel="stylesheet"></head><body class="admin-body d-flex align-items-center"><div class="container" style="max-width:440px"><div class="card admin-card p-4 p-md-5"><a href="<?= BASE_URL ?>" class="text-decoration-none fw-bold mb-4"><i class="fa-solid fa-car-side me-2"></i>KEISHA MOBIL</a><h1 class="h3 fw-bold">Login Admin</h1><p class="text-secondary">Masuk untuk mengelola katalog mobil.</p><?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="mb-3"><label class="form-label">Username</label><input class="form-control form-control-lg" name="username" required autocomplete="username"></div><div class="mb-4"><label class="form-label">Password</label><input class="form-control form-control-lg" type="password" name="password" required autocomplete="current-password"></div><button class="btn btn-primary btn-lg w-100">Masuk</button></form><a class="small mt-3 text-center" href="<?= BASE_URL ?>">Kembali ke website</a></div></div></body></html>
