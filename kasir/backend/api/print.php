<?php
ini_set('display_errors', 0);
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']); exit;
}

$action  = $_GET['action'] ?? '';
$user_id = (int)$_SESSION['user_id'];

const HARGA = ['print' => 1000, 'fotokopi' => 400];

if ($action === 'list') {
    $tgl_awal  = $_GET['tgl_awal']  ?? date('Y-m-01');
    $tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');

    $stmt = $pdo->prepare("
        SELECT p.*, u.nama AS kasir_nama
        FROM transaksi_print p
        JOIN users u ON p.user_id = u.id
        WHERE DATE(p.created_at) BETWEEN ? AND ?
        ORDER BY p.created_at DESC
    ");
    $stmt->execute([$tgl_awal, $tgl_akhir]);
    echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
    exit;
}

if ($action === 'simpan' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $body         = json_decode(file_get_contents('php://input'), true) ?? [];
    $jenis        = trim($body['jenis'] ?? '');
    $jumlah       = intval($body['jumlah_lembar'] ?? 0);
    $keterangan   = trim($body['keterangan'] ?? '');

    if (!in_array($jenis, ['print', 'fotokopi'])) {
        echo json_encode(['success' => false, 'message' => 'Jenis tidak valid']); exit;
    }
    if ($jumlah <= 0) {
        echo json_encode(['success' => false, 'message' => 'Jumlah lembar harus lebih dari 0']); exit;
    }

    $harga_per_lembar = HARGA[$jenis];
    $total            = $jumlah * $harga_per_lembar;

    $prefix  = 'PRT-' . date('Ymd') . '-';
    $lastInv = $pdo->query(
        "SELECT no_invoice FROM transaksi_print WHERE no_invoice LIKE '{$prefix}%' ORDER BY id DESC LIMIT 1"
    )->fetch();
    $no_invoice = $prefix . str_pad($lastInv ? (intval(substr($lastInv['no_invoice'], -3)) + 1) : 1, 3, '0', STR_PAD_LEFT);

    $stmt = $pdo->prepare("
        INSERT INTO transaksi_print (no_invoice, user_id, jenis, jumlah_lembar, harga_per_lembar, total, keterangan)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$no_invoice, $user_id, $jenis, $jumlah, $harga_per_lembar, $total, $keterangan]);

    echo json_encode([
        'success'    => true,
        'no_invoice' => $no_invoice,
        'total'      => $total,
        'message'    => 'Transaksi berhasil disimpan',
    ]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Action tidak dikenal']);
