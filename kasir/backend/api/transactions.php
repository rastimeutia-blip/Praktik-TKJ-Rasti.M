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

// GET riwayat transaksi
if ($method === 'GET' && $action === 'list') {
    $tgl_awal  = $_GET['tgl_awal'] ?? date('Y-m-01');
    $tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');

    $stmt = $pdo->prepare("
        SELECT t.*, u.nama as kasir_nama,
               (SELECT COUNT(*) FROM transaksi_item ti WHERE ti.transaksi_id = t.id) as jml_item
        FROM transaksi t
        JOIN users u ON t.user_id = u.id
        WHERE DATE(t.created_at) BETWEEN ? AND ?
        ORDER BY t.created_at DESC
    ");
    $stmt->execute([$tgl_awal, $tgl_akhir]);
    echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
    exit;
}

// GET detail transaksi
if ($method === 'GET' && $action === 'detail') {
    $id = (int)($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("
        SELECT ti.*, p.nama as produk_nama, p.kode
        FROM transaksi_item ti
        JOIN produk p ON ti.produk_id = p.id
        WHERE ti.transaksi_id = ?
    ");
    $stmt->execute([$id]);
    $items = $stmt->fetchAll();

    $stmtT = $pdo->prepare("SELECT t.*, u.nama as kasir_nama FROM transaksi t JOIN users u ON t.user_id = u.id WHERE t.id = ?");
    $stmtT->execute([$id]);
    $trx = $stmtT->fetch();

    echo json_encode(['success' => true, 'transaksi' => $trx, 'items' => $items]);
    exit;
}

// POST simpan transaksi
if ($method === 'POST' && $action === 'simpan') {
    $data  = json_decode(file_get_contents('php://input'), true);
    $items = $data['items'] ?? [];
    $bayar = (float)($data['bayar'] ?? 0);

    if (empty($items)) {
        echo json_encode(['success' => false, 'message' => 'Keranjang kosong']); exit;
    }

    // Hitung total
    $total = 0;
    foreach ($items as $item) {
        $total += (float)$item['subtotal'];
    }

    if ($bayar < $total) {
        echo json_encode(['success' => false, 'message' => 'Uang bayar kurang']); exit;
    }

    $kembalian  = $bayar - $total;
    $no_invoice = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
    $user_id    = $_SESSION['user_id'];

    $pdo->beginTransaction();
    try {
        // Insert transaksi
        $stmt = $pdo->prepare("INSERT INTO transaksi (no_invoice, user_id, total, bayar, kembalian) VALUES (?,?,?,?,?)");
        $stmt->execute([$no_invoice, $user_id, $total, $bayar, $kembalian]);
        $trx_id = $pdo->lastInsertId();

        // Insert items + kurangi stok
        $stmtItem = $pdo->prepare("INSERT INTO transaksi_item (transaksi_id, produk_id, qty, harga, subtotal) VALUES (?,?,?,?,?)");
        $stmtStok = $pdo->prepare("UPDATE produk SET stok = stok - ? WHERE id = ? AND stok >= ?");

        foreach ($items as $item) {
            $stmtItem->execute([$trx_id, $item['produk_id'], $item['qty'], $item['harga'], $item['subtotal']]);
            $stmtStok->execute([$item['qty'], $item['produk_id'], $item['qty']]);
            if ($stmtStok->rowCount() === 0) {
                throw new Exception('Stok produk tidak mencukupi');
            }
        }

        $pdo->commit();
        echo json_encode([
            'success'    => true,
            'message'    => 'Transaksi berhasil',
            'no_invoice' => $no_invoice,
            'total'      => $total,
            'bayar'      => $bayar,
            'kembalian'  => $kembalian,
            'trx_id'     => $trx_id
        ]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Action tidak dikenal']);
