<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: /kasir/dashboard.php'); exit;
}
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — KasirKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/kasir/assets/css/style.css?v=3">
</head>
<body>
<div class="login-page">
    <div style="width:100%;max-width:420px;">
        <div class="d-flex align-items-center gap-2 mb-4 justify-content-center">
            <div style="width:42px;height:42px;background:#2563eb;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:20px;height:20px;">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>
                </svg>
            </div>
            <span style="font-size:20px;font-weight:700;color:#0f172a;letter-spacing:-0.3px;">Kasir<span style="color:#2563eb;">Ku</span></span>
        </div>

        <div class="card shadow-sm border-0" style="border-radius:16px;">
            <div class="card-body p-4 p-md-5">
                <h1 class="mb-1" style="font-size:22px;font-weight:700;color:#0f172a;letter-spacing:-0.4px;">Selamat datang</h1>
                <p class="text-muted mb-4" style="font-size:13px;">Masuk ke akun Anda untuk melanjutkan</p>

                <?php if ($error): ?>
                <div class="alert alert-danger py-2 px-3 mb-3" style="font-size:13px;"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form action="/kasir/backend/auth/login.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:13px;">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Masuk</button>
                </form>

                <p class="text-center mt-4 text-muted mb-0" style="font-size:12px;">
                    Default: <strong>admin</strong> / <strong>password</strong>
                </p>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
