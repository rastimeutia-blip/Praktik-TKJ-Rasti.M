<?php
require_once __DIR__ . '/backend/config/auth.php';
cekLogin();
$page_title = 'Riwayat Transaksi';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="main-area">
    <div class="topbar">
        <h1 class="topbar-title">Riwayat Transaksi</h1>
        <div class="ms-auto d-flex gap-2 align-items-center">
            <input type="date" id="tglAwal" class="form-control form-control-sm" style="width:145px;">
            <input type="date" id="tglAkhir" class="form-control form-control-sm" style="width:145px;">
            <button class="btn btn-primary btn-sm" onclick="loadRiwayat()">Filter</button>
        </div>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>No Invoice</th><th>Tanggal</th><th>Kasir</th><th>Item</th><th class="text-end">Total</th><th class="text-end">Kembalian</th><th class="text-center">Aksi</th></tr>
                    </thead>
                    <tbody id="riwayatBody">
                        <tr><td colspan="7" class="text-center text-muted py-4">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Detail Transaksi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body" id="detailContent">Memuat...</div>
            <div class="modal-footer"><button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Tutup</button></div>
        </div>
    </div>
</div>

<?php
$inline_js = "
const API = '/kasir/backend/api/transactions.php';
const today = new Date().toISOString().split('T')[0];
const firstDay = today.slice(0,7) + '-01';
document.getElementById('tglAwal').value  = firstDay;
document.getElementById('tglAkhir').value = today;

async function loadRiwayat() {
    const tglAwal  = document.getElementById('tglAwal').value;
    const tglAkhir = document.getElementById('tglAkhir').value;
    const res = await fetch(API + `?action=list&tgl_awal=\${tglAwal}&tgl_akhir=\${tglAkhir}`);
    const d   = await res.json();
    const tbody = document.getElementById('riwayatBody');
    if (!d.success || !d.data.length) { tbody.innerHTML = '<tr><td colspan=\"7\" class=\"text-center text-muted py-4\">Tidak ada data transaksi</td></tr>'; return; }
    tbody.innerHTML = d.data.map(t => `
        <tr>
            <td><span class=\"badge bg-primary fw-normal\">\${t.no_invoice}</span></td>
            <td class=\"text-muted\" style=\"font-size:13px;\">\${new Date(t.created_at).toLocaleString('id-ID')}</td>
            <td>\${t.kasir_nama}</td>
            <td class=\"text-muted\">\${t.jml_item} item</td>
            <td class=\"text-end fw-semibold\">\${rupiah(t.total)}</td>
            <td class=\"text-end text-muted\">\${rupiah(t.kembalian)}</td>
            <td class=\"text-center\"><button class=\"btn btn-outline-secondary btn-sm\" onclick=\"lihatDetail(\${t.id})\">Detail</button></td>
        </tr>
    `).join('');
}

async function lihatDetail(id) {
    document.getElementById('detailContent').innerHTML = '<div class=\"text-center text-muted py-3\">Memuat...</div>';
    openModal('modalDetail');
    const res = await fetch(API + `?action=detail&id=\${id}`);
    const d   = await res.json();
    if (!d.success) return;
    const t = d.transaksi;
    document.getElementById('detailContent').innerHTML = `
        <div class=\"row g-3 mb-3\">
            <div class=\"col-6\"><div class=\"text-muted\" style=\"font-size:11px;text-transform:uppercase;letter-spacing:.5px;\">No Invoice</div><div class=\"fw-semibold\">\${t.no_invoice}</div></div>
            <div class=\"col-6\"><div class=\"text-muted\" style=\"font-size:11px;text-transform:uppercase;letter-spacing:.5px;\">Kasir</div><div class=\"fw-semibold\">\${t.kasir_nama}</div></div>
            <div class=\"col-6\"><div class=\"text-muted\" style=\"font-size:11px;text-transform:uppercase;letter-spacing:.5px;\">Tanggal</div><div>\${new Date(t.created_at).toLocaleString('id-ID')}</div></div>
        </div>
        <div class=\"table-responsive\">
            <table class=\"table table-sm mb-0\">
                <thead><tr><th>Produk</th><th class=\"text-end\">Harga</th><th class=\"text-end\">Qty</th><th class=\"text-end\">Subtotal</th></tr></thead>
                <tbody>\${d.items.map(i => `<tr><td>\${i.produk_nama}<br><small class=\"text-muted\">\${i.kode}</small></td><td class=\"text-end\">\${rupiah(i.harga)}</td><td class=\"text-end\">\${i.qty}</td><td class=\"text-end fw-semibold\">\${rupiah(i.subtotal)}</td></tr>`).join('')}</tbody>
                <tfoot style=\"border-top:2px solid #e2e8f0;\">
                    <tr><td colspan=\"3\" class=\"fw-bold\">Total</td><td class=\"text-end fw-bold\" style=\"color:#6366f1;font-size:15px;\">\${rupiah(t.total)}</td></tr>
                    <tr><td colspan=\"3\" class=\"text-muted\">Bayar</td><td class=\"text-end\">\${rupiah(t.bayar)}</td></tr>
                    <tr><td colspan=\"3\" class=\"text-muted\">Kembalian</td><td class=\"text-end\">\${rupiah(t.kembalian)}</td></tr>
                </tfoot>
            </table>
        </div>
    `;
}

loadRiwayat();
";
require_once __DIR__ . '/includes/footer.php';
?>
