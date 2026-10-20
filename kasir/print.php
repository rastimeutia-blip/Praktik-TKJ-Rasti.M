<?php
require_once __DIR__ . '/backend/config/auth.php';
cekLogin();
$page_title = 'Jasa Print';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="main-area">
    <div class="topbar">
        <h1 class="topbar-title">Jasa Print</h1>
        <span class="topbar-sub">Print &amp; Fotokopi</span>
    </div>

    <div class="page-content">
        <div class="row g-3">

            <!-- Form -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header px-4 py-3 fw-semibold">Input Transaksi</div>
                    <div class="card-body p-4">

                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:12px;">Jenis</label>
                            <div class="d-flex gap-2">
                                <button type="button" id="btnPrint" class="btn btn-outline-primary flex-fill fw-semibold" onclick="pilihJenis('print')">
                                    🖨️ Print <small class="d-block text-muted fw-normal">Rp 1.000/lembar</small>
                                </button>
                                <button type="button" id="btnFotokopi" class="btn btn-outline-primary flex-fill fw-semibold" onclick="pilihJenis('fotokopi')">
                                    📄 Fotokopi <small class="d-block text-muted fw-normal">Rp 400/lembar</small>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:12px;">Jumlah Lembar</label>
                            <input type="number" id="inpLembar" class="form-control" placeholder="0" min="1" step="1" oninput="hitungTotal()">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:12px;">Keterangan <span class="text-muted">(opsional)</span></label>
                            <input type="text" id="inpKet" class="form-control form-control-sm" placeholder="Catatan...">
                        </div>

                        <div id="boxTotal" class="rounded-3 p-3 mb-4 d-none" style="background:#eff6ff;border:1.5px solid #93c5fd;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Total</span>
                                <span id="lblDetail" class="text-muted" style="font-size:11px;"></span>
                            </div>
                            <div id="lblTotal" style="font-size:28px;font-weight:800;color:#1d4ed8;letter-spacing:-1px;">Rp 0</div>
                        </div>

                        <button class="btn btn-primary w-100 fw-semibold" id="btnSimpan" onclick="simpan()" disabled>
                            Simpan Transaksi
                        </button>
                    </div>
                </div>
            </div>

            <!-- Riwayat -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header px-4 py-3 d-flex align-items-center justify-content-between">
                        <span class="fw-semibold">Riwayat Hari Ini</span>
                        <span id="totalHariIni" class="badge bg-primary" style="font-size:11px;">Rp 0</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="font-size:13px;">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Jenis</th>
                                    <th class="text-end">Lembar</th>
                                    <th class="text-end">Harga/Lbr</th>
                                    <th class="text-end">Total</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="riwayatBody">
                                <tr><td colspan="6" class="text-center text-muted py-4">Memuat...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Struk Modal -->
<div class="modal fade" id="modalStruk" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Transaksi Berhasil</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center px-4 pb-4">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:48px;height:48px;">
                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <div id="strukInvoice" class="text-muted mb-1" style="font-size:12px;"></div>
                <div class="text-muted mb-1" style="font-size:12px;">Total Pendapatan</div>
                <div id="strukTotal" style="font-size:28px;font-weight:800;color:#1d4ed8;letter-spacing:-1px;"></div>
            </div>
        </div>
    </div>
</div>

<?php
$inline_js = "
const API = '/kasir/backend/api/print.php';
const HARGA = { print: 1000, fotokopi: 400 };
let jenisAktif = '';

function pilihJenis(jenis) {
    jenisAktif = jenis;
    document.getElementById('btnPrint').classList.toggle('btn-primary', jenis === 'print');
    document.getElementById('btnPrint').classList.toggle('btn-outline-primary', jenis !== 'print');
    document.getElementById('btnFotokopi').classList.toggle('btn-primary', jenis === 'fotokopi');
    document.getElementById('btnFotokopi').classList.toggle('btn-outline-primary', jenis !== 'fotokopi');
    hitungTotal();
}

function hitungTotal() {
    if (!jenisAktif) return;
    const lembar = parseInt(document.getElementById('inpLembar').value) || 0;
    if (lembar <= 0) {
        document.getElementById('boxTotal').classList.add('d-none');
        document.getElementById('btnSimpan').disabled = true;
        return;
    }
    const harga  = HARGA[jenisAktif];
    const total  = lembar * harga;
    const label  = jenisAktif === 'print' ? 'Print' : 'Fotokopi';
    document.getElementById('lblDetail').textContent = lembar + ' lbr x ' + rupiah(harga);
    document.getElementById('lblTotal').textContent  = rupiah(total);
    document.getElementById('boxTotal').classList.remove('d-none');
    document.getElementById('btnSimpan').disabled = false;
}

async function simpan() {
    if (!jenisAktif) return;
    const lembar    = parseInt(document.getElementById('inpLembar').value) || 0;
    const keterangan = document.getElementById('inpKet').value;
    if (lembar <= 0) return;

    const btn = document.getElementById('btnSimpan');
    btn.disabled = true;
    btn.textContent = 'Menyimpan...';

    try {
        const res = await fetch(API + '?action=simpan', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ jenis: jenisAktif, jumlah_lembar: lembar, keterangan })
        });
        const d = await res.json();
        if (!d.success) throw new Error(d.message);

        document.getElementById('strukInvoice').textContent = d.no_invoice;
        document.getElementById('strukTotal').textContent   = rupiah(d.total);
        openModal('modalStruk');

        document.getElementById('inpLembar').value = '';
        document.getElementById('inpKet').value    = '';
        document.getElementById('boxTotal').classList.add('d-none');
        document.getElementById('btnSimpan').disabled = true;
        loadRiwayat();
    } catch(e) {
        showToast(e.message || 'Terjadi kesalahan', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Simpan Transaksi';
    }
}

async function loadRiwayat() {
    const today = new Date().toISOString().split('T')[0];
    const res = await fetch(API + '?action=list&tgl_awal=' + today + '&tgl_akhir=' + today);
    const d = await res.json();
    if (!d.success) return;

    const tbody = document.getElementById('riwayatBody');
    if (!d.data.length) {
        tbody.innerHTML = '<tr><td colspan=\"6\" class=\"text-center text-muted py-4\">Belum ada transaksi hari ini</td></tr>';
        document.getElementById('totalHariIni').textContent = rupiah(0);
        return;
    }

    const total = d.data.reduce((s, r) => s + parseFloat(r.total), 0);
    document.getElementById('totalHariIni').textContent = rupiah(total);

    tbody.innerHTML = d.data.map(r => {
        const jam   = new Date(r.created_at).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'});
        const label = r.jenis === 'print' ? 'Print' : 'Fotokopi';
        const ket   = r.keterangan || '—';
        return '<tr>' +
            '<td>' + jam + '</td>' +
            '<td><span class=\"badge ' + (r.jenis === 'print' ? 'bg-primary' : 'bg-secondary') + '\">' + label + '</span></td>' +
            '<td class=\"text-end\">' + r.jumlah_lembar + ' lbr</td>' +
            '<td class=\"text-end text-muted\">' + rupiah(r.harga_per_lembar) + '</td>' +
            '<td class=\"text-end fw-semibold\">' + rupiah(r.total) + '</td>' +
            '<td class=\"text-muted\">' + ket + '</td>' +
            '</tr>';
    }).join('');
}

loadRiwayat();
";
require_once __DIR__ . '/includes/footer.php';
?>
