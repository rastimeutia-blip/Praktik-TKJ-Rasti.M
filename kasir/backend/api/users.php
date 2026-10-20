<?php
ini_set('display_errors', 0);
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';
cekAdmin();

header('Content-Type: application/json');

function out(array $data): void {
    echo json_encode($data);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$act    = $_GET['act'] ?? '';
$me     = getUserLogin();

if ($method === 'GET' && $act === 'list') {
    $st = $pdo->query("SELECT id, username, nama, role, created_at FROM users ORDER BY role DESC, nama ASC");
    out(['ok' => true, 'data' => $st->fetchAll()]);
}

if ($method === 'POST' && $act === 'tambah') {
    $body     = json_decode(file_get_contents('php://input'), true) ?? [];
    $username = trim($body['username'] ?? '');
    $nama     = trim($body['nama']     ?? '');
    $role     = $body['role']          ?? 'kasir';
    $password = $body['password']      ?? '';

    if (!$username || !$nama || !$password) out(['ok' => false, 'msg' => 'Username, nama, dan password wajib diisi']);
    if (!in_array($role, ['admin','kasir'])) out(['ok' => false, 'msg' => 'Role tidak valid']);
    if (strlen($password) < 6) out(['ok' => false, 'msg' => 'Password minimal 6 karakter']);

    try {
        $st = $pdo->prepare("INSERT INTO users (username, password, nama, role) VALUES (?,?,?,?)");
        $st->execute([$username, password_hash($password, PASSWORD_BCRYPT), $nama, $role]);
        out(['ok' => true, 'msg' => 'Pengguna berhasil ditambahkan']);
    } catch (PDOException $e) {
        out(['ok' => false, 'msg' => 'Username sudah digunakan']);
    }
}

if ($method === 'POST' && $act === 'edit') {
     $body     = json_decode(file_get_contents('php://input'), true) ?? [];
     $id       = (int)($body['id']       ?? 0);
     $username = trim($body['username'] ?? '');
     $nama     = trim($body['nama']      ?? '');
     $role     = $body['role']           ?? 'kasir';
     $password = $body['password']       ?? '';

     if (!$id || !$username || !$nama) out(['ok' => false, 'msg' => 'Data tidak lengkap']);
     if (!in_array($role, ['admin','kasir'])) out(['ok' => false, 'msg' => 'Role tidak valid']);
     if ($id === (int)$me['id'] && $role !== $me['role']) out(['ok' => false, 'msg' => 'Tidak dapat mengubah role akun sendiri']);

     // Check if username already exists (excluding current user)
     $stmt = $pdo->prepare("SELECT id FROM users WHERE username=? AND id!=?");
     $stmt->execute([$username, $id]);
     if ($stmt->fetch()) out(['ok' => false, 'msg' => 'Username sudah digunakan']);

     if ($password !== '') {
         if (strlen($password) < 6) out(['ok' => false, 'msg' => 'Password minimal 6 karakter']);
         $st = $pdo->prepare("UPDATE users SET username=?, nama=?, role=?, password=? WHERE id=?");
         $st->execute([$username, $nama, $role, password_hash($password, PASSWORD_BCRYPT), $id]);
     } else {
         $st = $pdo->prepare("UPDATE users SET username=?, nama=?, role=? WHERE id=?");
         $st->execute([$username, $nama, $role, $id]);
     }
     out(['ok' => true, 'msg' => 'Pengguna berhasil diupdate']);
 }

if ($method === 'POST' && $act === 'hapus') {
    $body = json_decode(file_get_contents('php://input'), true) ?? [];
    $id   = (int)($body['id'] ?? 0);

    if ($id <= 0) out(['ok' => false, 'msg' => 'ID tidak valid']);
    if ($id === (int)$me['id']) out(['ok' => false, 'msg' => 'Tidak dapat menghapus akun sendiri']);

    $adminCount = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
    $st = $pdo->prepare("SELECT role FROM users WHERE id=?");
    $st->execute([$id]);
    $tgt = $st->fetch();
    if (!$tgt) out(['ok' => false, 'msg' => 'Pengguna tidak ditemukan']);
    if ($tgt['role'] === 'admin' && $adminCount <= 1) out(['ok' => false, 'msg' => 'Minimal harus ada satu admin']);

    $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$id]);
    out(['ok' => true, 'msg' => 'Pengguna berhasil dihapus']);
}

out(['ok' => false, 'msg' => 'Action tidak dikenal']);
