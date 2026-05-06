<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (isset($_SESSION['user'])) { header('Location: index.php'); exit; }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'config/Database.php';
    try {
        $db   = (new Database())->connect();
        $stmt = $db->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->execute([$_POST['username']]);
        $u = $stmt->fetch();

        if ($u && ($u['password'] === $_POST['password'] || password_verify($_POST['password'], $u['password']))) {
            $_SESSION['user']    = $u['username'];
            $_SESSION['nama']    = $u['nama'];
            $_SESSION['role']    = $u['role'];
            $_SESSION['id_user'] = $u['id'];
            header('Location: index.php');
            exit;
        }
        $error = 'Username atau password salah.';
    } catch (Exception $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Laundry Hisam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #2c3e2d; min-height: 100vh; }
        .card { border: none; border-radius: 10px; }
        .btn-masuk { background: #2d6a4f; border: none; }
        .btn-masuk:hover { background: #245a42; }
        .form-control:focus { border-color: #2d6a4f; box-shadow: 0 0 0 .2rem rgba(45,106,79,.2); }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">
    <div class="card shadow p-4" style="width:100%;max-width:380px;">
        <div class="text-center mb-4">
            <span style="font-size:2rem;">🧺</span>
            <h5 class="fw-bold mt-2 mb-0">Laundry Hisam</h5>
            <small class="text-muted">Silakan login untuk melanjutkan</small>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                <i class="bi bi-exclamation-circle me-1"></i><?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
            </div>
            <button type="submit" class="btn btn-masuk text-white w-100">Login</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
