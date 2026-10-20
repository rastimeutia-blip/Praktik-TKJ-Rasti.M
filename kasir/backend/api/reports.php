<?php
ini_set('display_errors', 0);
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']); exit;
}

$action = $_GET['action'] ?? '';

// Dashboard stats
if ($action === 'dashboard') {
    $today = date('Y-m-d');
    $month = date('Y-m');

    $pendapatanHari = $pdo->prepare("SELECT COALESCE(SUM(total),0) as total FROM transaksi WHERE DATE(created_at) = ?");
    $pendapatanHari->execute([$today]);
    $ph = $pendapatanHari->fetch()['total'];

    $briHari = $pdo->prepare("SELECT COALESCE(SUM(biaya_admin),0) as total FROM transaksi_brilink WHERE DATE(created_at) = ?");
    $briHari->execute([$today]);
    $bh = $briHari->fetch()['total'];

    $printHari = $pdo->prepare("SELECT COALESCE(SUM(total),0) as total FROM transaksi_print WHERE DATE(created_at) = ?");
    $printHari->execute([$today]);
    $prh = $printHari->fetch()['total'];

    $pendapatanBulan = $pdo->prepare("SELECT COALESCE(SUM(total),0) as total FROM transaksi WHERE DATE_FORMAT(created_at,'%Y-%m') = ?");
    $pendapatanBulan->execute([$month]);
    $pb = $pendapatanBulan->fetch()['total'];

    $briBulan = $pdo->prepare("SELECT COALESCE(SUM(biaya_admin),0) as total FROM transaksi_brilink WHERE DATE_FORMAT(created_at,'%Y-%m') = ?");
    $briBulan->execute([$month]);
    $bb = $briBulan->fetch()['total'];

    $printBulan = $pdo->prepare("SELECT COALESCE(SUM(total),0) as total FROM transaksi_print WHERE DATE_FORMAT(created_at,'%Y-%m') = ?");
    $printBulan->execute([$month]);
    $prb = $printBulan->fetch()['total'];

    $trxHari = $pdo->prepare("SELECT COUNT(*) as total FROM transaksi WHERE DATE(created_at) = ?");
    $trxHari->execute([$today]);
    $th = $trxHari->fetch()['total'];

    $stokMenipis = $pdo->query("SELECT COUNT(*) as total FROM produk WHERE stok <= 10")->fetch()['total'];

    // Grafik 7 hari terakhir
    $grafikMap    = [];
    $grafikBriMap = [];
    $grafikPrtMap = [];
    for ($i = 6; $i >= 0; $i--) {
        $d = date('Y-m-d', strtotime("-$i days"));
        $grafikMap[$d]    = 0;
        $grafikBriMap[$d] = 0;
        $grafikPrtMap[$d] = 0;
    }
    foreach ($pdo->query("SELECT DATE(created_at) as tgl, COALESCE(SUM(total),0) as total FROM transaksi WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY DATE(created_at)")->fetchAll() as $row) {
        $grafikMap[$row['tgl']] = $row['total'];
    }
    foreach ($pdo->query("SELECT DATE(created_at) as tgl, COALESCE(SUM(biaya_admin),0) as total FROM transaksi_brilink WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY DATE(created_at)")->fetchAll() as $row) {
        $grafikBriMap[$row['tgl']] = $row['total'];
    }
    foreach ($pdo->query("SELECT DATE(created_at) as tgl, COALESCE(SUM(total),0) as total FROM transaksi_print WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY DATE(created_at)")->fetchAll() as $row) {
        $grafikPrtMap[$row['tgl']] = $row['total'];
    }
    $grafik = array_map(fn($tgl, $total) => [
        'tgl'        => $tgl,
        'total'      => $total,
        'total_bri'  => $grafikBriMap[$tgl] ?? 0,
        'total_prt'  => $grafikPrtMap[$tgl] ?? 0,
    ], array_keys($grafikMap), $grafikMap);

    // Top 5 produk
    $topProduk = $pdo->query("
        SELECT p.nama, SUM(ti.qty) as total_terjual
        FROM transaksi_item ti
        JOIN produk p ON ti.produk_id = p.id
        JOIN transaksi t ON ti.transaksi_id = t.id
        WHERE DATE_FORMAT(t.created_at,'%Y-%m') = '" . $month . "'
        GROUP BY p.id, p.nama
        ORDER BY total_terjual DESC
        LIMIT 5
    ")->fetchAll();

    echo json_encode([
        'success'           => true,
        'pendapatan_hari'   => $ph,
        'pendapatan_bulan'  => $pb,
        'brilink_hari'      => $bh,
        'brilink_bulan'     => $bb,
        'print_hari'        => $prh,
        'print_bulan'       => $prb,
        'trx_hari'          => $th,
        'stok_menipis'      => $stokMenipis,
        'grafik'            => $grafik,
        'top_produk'        => $topProduk,
    ]);
    exit;
}

// Laporan
if ($action === 'laporan') {
    $tgl_awal  = $_GET['tgl_awal'] ?? date('Y-m-01');
    $tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');

    $summary = $pdo->prepare("SELECT COUNT(*) as total_transaksi, COALESCE(SUM(total),0) as total_pendapatan, COALESCE(AVG(total),0) as rata_rata FROM transaksi WHERE DATE(created_at) BETWEEN ? AND ?");
    $summary->execute([$tgl_awal, $tgl_akhir]);

    $summaryBri = $pdo->prepare("SELECT COUNT(*) as total_transaksi, COALESCE(SUM(biaya_admin),0) as total_pendapatan FROM transaksi_brilink WHERE DATE(created_at) BETWEEN ? AND ?");
    $summaryBri->execute([$tgl_awal, $tgl_akhir]);

    $summaryPrint = $pdo->prepare("SELECT COUNT(*) as total_transaksi, COALESCE(SUM(total),0) as total_pendapatan, COALESCE(SUM(CASE WHEN jenis='print' THEN total ELSE 0 END),0) as total_print, COALESCE(SUM(CASE WHEN jenis='fotokopi' THEN total ELSE 0 END),0) as total_fotokopi FROM transaksi_print WHERE DATE(created_at) BETWEEN ? AND ?");
    $summaryPrint->execute([$tgl_awal, $tgl_akhir]);

    $briPerJenis = $pdo->prepare("SELECT jenis, COUNT(*) as jml, COALESCE(SUM(biaya_admin),0) as total FROM transaksi_brilink WHERE DATE(created_at) BETWEEN ? AND ? GROUP BY jenis ORDER BY total DESC");
    $briPerJenis->execute([$tgl_awal, $tgl_akhir]);

    $printDetail = $pdo->prepare("
        SELECT p.*, u.nama AS kasir_nama
        FROM transaksi_print p
        JOIN users u ON p.user_id = u.id
        WHERE DATE(p.created_at) BETWEEN ? AND ?
        ORDER BY p.created_at ASC
    ");
    $printDetail->execute([$tgl_awal, $tgl_akhir]);

    $perHari = $pdo->prepare("SELECT DATE(created_at) as tgl, COUNT(*) as jml_trx, SUM(total) as total FROM transaksi WHERE DATE(created_at) BETWEEN ? AND ? GROUP BY DATE(created_at) ORDER BY tgl ASC");
    $perHari->execute([$tgl_awal, $tgl_akhir]);

    $produkTerlaris = $pdo->prepare("SELECT p.nama, p.kode, SUM(ti.qty) as total_qty, SUM(ti.subtotal) as total_omzet FROM transaksi_item ti JOIN produk p ON ti.produk_id = p.id JOIN transaksi t ON ti.transaksi_id = t.id WHERE DATE(t.created_at) BETWEEN ? AND ? GROUP BY p.id, p.nama, p.kode ORDER BY total_qty DESC LIMIT 10");
    $produkTerlaris->execute([$tgl_awal, $tgl_akhir]);

    echo json_encode([
        'success'           => true,
        'summary'           => $summary->fetch(),
        'summary_bri'       => $summaryBri->fetch(),
        'summary_print'     => $summaryPrint->fetch(),
        'brilink_per_jenis' => $briPerJenis->fetchAll(),
        'print_detail'      => $printDetail->fetchAll(),
        'per_hari'          => $perHari->fetchAll(),
        'produk_terlaris'   => $produkTerlaris->fetchAll(),
    ]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Action tidak dikenal']);
