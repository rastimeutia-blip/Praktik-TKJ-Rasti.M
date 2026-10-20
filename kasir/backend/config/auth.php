<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function cekLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /kasir/login.php');
        exit;
    }
}

function cekAdmin() {
    cekLogin();
    if ($_SESSION['role'] !== 'admin') {
        header('Location: /kasir/dashboard.php');
        exit;
    }
}

function getUserLogin() {
    return [
        'id'   => $_SESSION['user_id'] ?? null,
        'nama' => $_SESSION['nama'] ?? '',
        'role' => $_SESSION['role'] ?? '',
    ];
}
