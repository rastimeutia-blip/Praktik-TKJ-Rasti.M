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

function hitungBiayaAdmin(string $jenis, string $sub_jenis, float $nominal): float {
    switch ($jenis) {
        case 'stor_tunai':
            if ($nominal >= 100000   && $nominal <= 2500000)  return 5000;
            if ($nominal > 2500000   && $nominal <= 7000000)  return 7000;
            if ($nominal > 7000000   && $nominal <= 10000000) return 10000;
            if ($nominal > 10000000  && $nominal <= 11000000) return 11000;
            if ($nominal > 11000000  && $nominal <= 12000000) return 12000;
            return 0;

        case 'tarik_tunai':
            if ($nominal >= 50000  && $nominal <= 100000)   return 2000;
            if ($nominal > 100000  && $nominal < 500000)    return 3000;
            if ($nominal >= 500000 && $nominal <= 850000)   return 4000;
            if ($nominal > 850000  && $nominal <= 1500000)  return 5000;
            if ($nominal > 1500000 && $nominal <= 2500000)  return 6000;
            if ($nominal > 2500000 && $nominal <= 3500000)  return 7000;
            if ($nominal > 3500000 && $nominal <= 4500000)  return 9000;
            if ($nominal > 4500000 && $nominal <= 5000000)  return 10000;
            return 0;

        case 'pembayaran':
            $fees = [
                'stor_pinjaman' => 5000,
                'briva'         => 5000,
                'motor'         => 5000,
                'bpjs'          => 2500,
                'token_listrik' => 2000,
            ];
            return (float)($fees[$sub_jenis] ?? 0);

        case 'transfer':
            if ($nominal <= 2000000) return 10000;
            if ($nominal <= 3500000) return 15000;
            if ($nominal <= 5000000) return 20000;
            return 0;

        case 'dompet_digital':
            return 3000;

        default:
            return 0;
    }
}

// List transactions
if ($action === 'list') {
    $tgl_awal  = $_GET['tgl_awal']  ?? date('Y-m-01');
    $tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');

    $stmt = $pdo->prepare("
        SELECT b.*, u.nama AS kasir_nama
        FROM transaksi_brilink b
        JOIN users u ON b.user_id = u.id
        WHERE DATE(b.created_at) BETWEEN ? AND ?
        ORDER BY b.created_at DESC
    ");
    $stmt->execute([$tgl_awal, $tgl_akhir]);
    echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
    exit;
}

// Calculate fee (AJAX preview)
if ($action === 'hitung') {
    $jenis     = $_GET['jenis']     ?? '';
    $sub_jenis = $_GET['sub_jenis'] ?? '';
    $nominal   = floatval($_GET['nominal'] ?? 0);
    $fee = hitungBiayaAdmin($jenis, $sub_jenis, $nominal);
    echo json_encode(['success' => true, 'biaya_admin' => $fee]);
    exit;
}

// Save transaction
if ($action === 'simpan' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true) ?? [];

    $jenis      = trim($body['jenis']      ?? '');
    $sub_jenis  = trim($body['sub_jenis']  ?? '');
    $nominal    = floatval($body['nominal'] ?? 0);
    $keterangan = trim($body['keterangan'] ?? '');

    $allowed = ['stor_tunai', 'tarik_tunai', 'pembayaran', 'transfer', 'dompet_digital'];
    if (!in_array($jenis, $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Jenis transaksi tidak valid']); exit;
    }

    $biaya_admin = hitungBiayaAdmin($jenis, $sub_jenis, $nominal);

    // Generate invoice number
    $prefix  = 'BRI-' . date('Ymd') . '-';
    $lastInv = $pdo->query(
        "SELECT no_invoice FROM transaksi_brilink WHERE no_invoice LIKE '{$prefix}%' ORDER BY id DESC LIMIT 1"
    )->fetch();
    $no_invoice = $prefix . str_pad($lastInv ? (intval(substr($lastInv['no_invoice'], -3)) + 1) : 1, 3, '0', STR_PAD_LEFT);

    $stmt = $pdo->prepare("
        INSERT INTO transaksi_brilink (no_invoice, user_id, jenis, sub_jenis, nominal, biaya_admin, keterangan)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$no_invoice, $user_id, $jenis, $sub_jenis, $nominal, $biaya_admin, $keterangan]);

    echo json_encode([
        'success'     => true,
        'no_invoice'  => $no_invoice,
        'biaya_admin' => $biaya_admin,
        'message'     => 'Transaksi BRI Link berhasil disimpan',
    ]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Action tidak dikenal']);
