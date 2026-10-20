<?php
ini_set('display_errors', 0);
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']); exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// GET semua produk
if ($method === 'GET' && $action === 'list') {
    $q = $_GET['q'] ?? '';
    if ($q) {
        $stmt = $pdo->prepare("SELECT * FROM produk WHERE nama LIKE ? OR kode LIKE ? ORDER BY nama");
        $stmt->execute(["%$q%", "%$q%"]);
    } else {
        $stmt = $pdo->query("SELECT * FROM produk ORDER BY nama");
    }
    echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
    exit;
}

// GET satu produk by kode (untuk scanner)
if ($method === 'GET' && $action === 'cari') {
    $kode = $_GET['kode'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM produk WHERE kode = ? LIMIT 1");
    $stmt->execute([$kode]);
    $produk = $stmt->fetch();
    if ($produk) {
        echo json_encode(['success' => true, 'data' => $produk]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan']);
    }
    exit;
}

// POST tambah produk
if ($method === 'POST' && $action === 'tambah') {
    $data = json_decode(file_get_contents('php://input'), true);
    $kode     = trim($data['kode'] ?? '');
    $nama     = trim($data['nama'] ?? '');
    $kategori = trim($data['kategori'] ?? '');
    $harga    = (float)($data['harga'] ?? 0);
    $stok     = (int)($data['stok'] ?? 0);

    if (!$kode || !$nama || $harga <= 0) {
        echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']); exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO produk (kode, nama, kategori, harga, stok) VALUES (?,?,?,?,?)");
        $stmt->execute([$kode, $nama, $kategori, $harga, $stok]);
        echo json_encode(['success' => true, 'message' => 'Produk berhasil ditambahkan']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Kode produk sudah ada']);
    }
    exit;
}

// PUT edit produk
if ($method === 'PUT' && $action === 'edit') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id       = (int)($data['id'] ?? 0);
    $nama     = trim($data['nama'] ?? '');
    $kategori = trim($data['kategori'] ?? '');
    $harga    = (float)($data['harga'] ?? 0);
    $stok     = (int)($data['stok'] ?? 0);

    $stmt = $pdo->prepare("UPDATE produk SET nama=?, kategori=?, harga=?, stok=?, updated_at=NOW() WHERE id=?");
    $stmt->execute([$nama, $kategori, $harga, $stok, $id]);
    echo json_encode(['success' => true, 'message' => 'Produk berhasil diupdate']);
    exit;
}

// DELETE hapus produk
if ($method === 'DELETE' && $action === 'hapus') {
    $id = (int)($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("DELETE FROM produk WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(['success' => true, 'message' => 'Produk berhasil dihapus']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Action tidak dikenal']);
