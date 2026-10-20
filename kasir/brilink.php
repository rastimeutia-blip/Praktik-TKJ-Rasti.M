<?php
require_once __DIR__ . '/backend/config/auth.php';
cekLogin();
$page_title = 'BRI Link';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="main-area">
    <div class="topbar">
        <h1 class="topbar-title">BRI Link</h1>
        <span class="topbar-sub">Catat biaya admin transaksi BRI Link</span>
    </div>

    <div class="page-content">
        <div class="row g-3">

            <!-- Form Transaksi -->
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header px-4 py-3 fw-semibold">Input Transaksi</div>
                    <div class="card-body p-4">

                        <!-- Jenis Transaksi -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:12px;">Jenis Transaksi</label>
                            <select id="selJenis" class="form-select form-select-sm" onchange="onJenisChange()">
                                <option value="">-- Pilih Jenis --</option>
                                <option value="stor_tunai">Stor Tunai</option>
                                <option value="tarik_tunai">Tarik Tunai</option>
                                <option value="pembayaran">Pembayaran</option>
                                <option value="transfer">Transfer ke Rek Lain</option>
                                <option value="dompet_digital">Dompet Digital</option>
                            </select>
                        </div>

                        <!-- Sub-jenis: Pembayaran -->
                        <div id="grpPembayaran" class="mb-3 d-none">
                            <label class="form-label fw-semibold" style="font-size:12px;">Jenis Pembayaran</label>
                            <select id="selPembayaran" class="form-select form-select-sm" onchange="hitungFee()">
                                <option value="">-- Pilih --</option>
                                <option value="stor_pinjaman">Stor Pinjaman (Rp 5.000)</option>
                                <option value="briva">Briva (Rp 5.000)</option>
                                <option value="motor">Pembayaran Motor (Rp 5.000)</option>
                                <option value="bpjs">BPJS (Rp 2.500)</option>
                                <option value="token_listrik">Token Listrik (Rp 2.000)</option>
                            </select>
                        </div>

                        <!-- Sub-jenis: Dompet Digital -->
                        <div id="grpDompet" class="mb-3 d-none">
                            <label class="form-label fw-semibold" style="font-size:12px;">Platform</label>
                            <select id="selPlatform" class="form-select form-select-sm mb-2" onchange="hitungFee()">
                                <option value="">-- Pilih Platform --</option>
                                <option value="dana">Dana</option>
                                <option value="gopay">GoPay</option>
                                <option value="ovo">OVO</option>
                                <option value="shopeepay">ShopeePay</option>
                                <option value="linkaja">LinkAja</option>
                            </select>
                            <label class="form-label fw-semibold" style="font-size:12px;">Jenis</label>
                            <select id="selDompetJenis" class="form-select form-select-sm" onchange="hitungFee()">
                                <option value="topup">Top Up</option>
                                <option value="tarik">Tarik Tunai</option>
                            </select>
                        </div>

                        <!-- Nominal -->
                        <div id="grpNominal" class="mb-3 d-none">
                            <label class="form-label fw-semibold" style="font-size:12px;">Nominal Transaksi (Rp)</label>
                            <input type="number" id="inpNominal" class="form-control form-control-sm" placeholder="0" min="0" step="1000" oninput="hitungFee()">
                            <div id="infoRange" class="form-text text-muted mt-1" style="font-size:11px;"></div>
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:12px;">Keterangan <span class="text-muted">(opsional)</span></label>
                            <input type="text" id="inpKeterangan" class="form-control form-control-sm" placeholder="Nama nasabah / catatan">
                        </div>

                        <!-- Biaya Admin Preview -->
                        <div id="boxFee" class="rounded-3 p-3 mb-4 d-none" style="background:#f0fdf4;border:1.5px solid #86efac;">
                            <div class="text-muted mb-1" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Biaya Admin</div>
                            <div id="lblFee" style="font-size:26px;font-weight:800;color:#16a34a;letter-spacing:-0.5px;">Rp 0</div>
                        </div>

                        <div id="boxFeeWarning" class="alert alert-warning py-2 d-none" style="font-size:12px;">
                            Nominal di luar rentang yang didukung. Periksa kembali.
                        </div>

                        <button class="btn btn-primary w-100 fw-semibold" id="btnSimpan" onclick="simpan()" disabled>
                            Simpan Transaksi
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabel Referensi & Riwayat -->
            <div class="col-lg-7">

                <!-- Tabel Referensi Admin Fee -->
                <div id="refCard" class="card mb-3 d-none">
                    <div class="card-header px-4 py-3 fw-semibold d-flex align-items-center gap-2">
                        <span id="refTitle">Tabel Biaya Admin</span>
                    </div>
                    <div class="card-body p-0">
                        <div id="refBody" class="table-responsive"></div>
                    </div>
                </div>

                <!-- Riwayat Hari Ini -->
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
                                    <th>Sub Jenis</th>
                                    <th class="text-end">Nominal</th>
                                    <th class="text-end">Biaya Admin</th>
                                </tr>
                            </thead>
                            <tbody id="riwayatBody">
                                <tr><td colspan="5" class="text-center text-muted py-4">Memuat...</td></tr>
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
                <div class="text-muted mb-1" style="font-size:12px;">Biaya Admin Tercatat</div>
                <div id="strukFee" style="font-size:28px;font-weight:800;color:#16a34a;letter-spacing:-1px;"></div>
            </div>
        </div>
    </div>
</div>

<?php
$inline_js = "
const API = '/kasir/backend/api/brilink.php';

const REF_TABLES = {
    stor_tunai: {
        title: 'Biaya Admin — Stor Tunai',
        headers: ['Nominal', 'Biaya Admin'],
        rows: [
            ['Rp 100.000 – Rp 2.500.000', 'Rp 5.000'],
            ['Rp 2.500.001 – Rp 7.000.000', 'Rp 7.000'],
            ['Rp 7.000.001 – Rp 10.000.000', 'Rp 10.000'],
            ['Rp 10.000.001 – Rp 11.000.000', 'Rp 11.000'],
            ['Rp 11.000.001 – Rp 12.000.000', 'Rp 12.000'],
        ]
    },
    tarik_tunai: {
        title: 'Biaya Admin — Tarik Tunai',
        headers: ['Nominal', 'Biaya Admin'],
        rows: [
            ['Rp 50.000 – Rp 100.000', 'Rp 2.000'],
            ['Rp 100.001 – Rp 499.999', 'Rp 3.000'],
            ['Rp 500.000 – Rp 850.000', 'Rp 4.000'],
            ['Rp 850.001 – Rp 1.500.000', 'Rp 5.000'],
            ['Rp 1.500.001 – Rp 2.500.000', 'Rp 6.000'],
            ['Rp 2.500.001 – Rp 3.500.000', 'Rp 7.000'],
            ['Rp 3.500.001 – Rp 4.500.000', 'Rp 9.000'],
            ['Rp 4.500.001 – Rp 5.000.000', 'Rp 10.000'],
        ]
    },
    pembayaran: {
        title: 'Biaya Admin — Pembayaran',
        headers: ['Jenis', 'Biaya Admin'],
        rows: [
            ['Stor Pinjaman', 'Rp 5.000'],
            ['Briva', 'Rp 5.000'],
            ['Pembayaran Motor', 'Rp 5.000'],
            ['BPJS', 'Rp 2.500'],
            ['Token Listrik', 'Rp 2.000'],
        ]
    },
    transfer: {
        title: 'Biaya Admin — Transfer ke Rek Lain',
        headers: ['Nominal', 'Biaya Admin'],
        rows: [
            ['s/d Rp 2.000.000', 'Rp 10.000'],
            ['Rp 2.000.001 – Rp 3.500.000', 'Rp 15.000'],
            ['Rp 3.500.001 – Rp 5.000.000', 'Rp 20.000'],
        ]
    },
    dompet_digital: {
        title: 'Biaya Admin — Dompet Digital',
        headers: ['Platform', 'Top Up', 'Tarik Tunai'],
        rows: [
            ['Dana', 'Rp 3.000', 'Rp 3.000'],
            ['GoPay', 'Rp 3.000', 'Rp 3.000'],
            ['OVO', 'Rp 3.000', 'Rp 3.000'],
            ['ShopeePay', 'Rp 3.000', 'Rp 3.000'],
            ['LinkAja', 'Rp 3.000', 'Rp 3.000'],
        ]
    }
};

function onJenisChange() {
    const jenis = document.getElementById('selJenis').value;
    document.getElementById('grpPembayaran').classList.toggle('d-none', jenis !== 'pembayaran');
    document.getElementById('grpDompet').classList.toggle('d-none', jenis !== 'dompet_digital');
    document.getElementById('grpNominal').classList.toggle('d-none', ['pembayaran','dompet_digital',''].includes(jenis));

    // Show reference table
    const ref = REF_TABLES[jenis];
    const refCard = document.getElementById('refCard');
    if (ref) {
        document.getElementById('refTitle').textContent = ref.title;
        const cols = ref.headers.length;
        const ths = ref.headers.map(h => '<th>' + h + '</th>').join('');
        const trs = ref.rows.map(r => '<tr>' + r.map(c => '<td>' + c + '</td>').join('') + '</tr>').join('');
        document.getElementById('refBody').innerHTML = '<table class=\"table table-sm mb-0\" style=\"font-size:12px;\"><thead><tr>' + ths + '</tr></thead><tbody>' + trs + '</tbody></table>';
        refCard.classList.remove('d-none');
    } else {
        refCard.classList.add('d-none');
    }

    // Reset fee display
    resetFee();
    hitungFee();
}

function resetFee() {
    document.getElementById('boxFee').classList.add('d-none');
    document.getElementById('boxFeeWarning').classList.add('d-none');
    document.getElementById('btnSimpan').disabled = true;
    document.getElementById('inpNominal').value = '';
    document.getElementById('infoRange').textContent = '';
}

function hitungFee() {
    const jenis = document.getElementById('selJenis').value;
    if (!jenis) return;

    let sub_jenis = '';
    let nominal = 0;

    if (jenis === 'pembayaran') {
        sub_jenis = document.getElementById('selPembayaran').value;
        if (!sub_jenis) { resetFee(); return; }
    } else if (jenis === 'dompet_digital') {
        const platform = document.getElementById('selPlatform').value;
        const djenis   = document.getElementById('selDompetJenis').value;
        if (!platform) { resetFee(); return; }
        sub_jenis = platform + '_' + djenis;
    } else {
        nominal = parseFloat(document.getElementById('inpNominal').value) || 0;
        if (nominal <= 0) { resetFee(); return; }
    }

    const fee = calcFee(jenis, sub_jenis, nominal);
    const boxFee = document.getElementById('boxFee');
    const boxWarn = document.getElementById('boxFeeWarning');

    if (fee > 0) {
        document.getElementById('lblFee').textContent = rupiah(fee);
        boxFee.classList.remove('d-none');
        boxWarn.classList.add('d-none');
        document.getElementById('btnSimpan').disabled = false;
    } else {
        boxFee.classList.add('d-none');
        boxWarn.classList.remove('d-none');
        document.getElementById('btnSimpan').disabled = true;
    }
}

function calcFee(jenis, sub_jenis, nominal) {
    switch (jenis) {
        case 'stor_tunai':
            if (nominal >= 100000   && nominal <= 2500000)  return 5000;
            if (nominal > 2500000   && nominal <= 7000000)  return 7000;
            if (nominal > 7000000   && nominal <= 10000000) return 10000;
            if (nominal > 10000000  && nominal <= 11000000) return 11000;
            if (nominal > 11000000  && nominal <= 12000000) return 12000;
            return 0;
        case 'tarik_tunai':
            if (nominal >= 50000  && nominal <= 100000)   return 2000;
            if (nominal > 100000  && nominal < 500000)    return 3000;
            if (nominal >= 500000 && nominal <= 850000)   return 4000;
            if (nominal > 850000  && nominal <= 1500000)  return 5000;
            if (nominal > 1500000 && nominal <= 2500000)  return 6000;
            if (nominal > 2500000 && nominal <= 3500000)  return 7000;
            if (nominal > 3500000 && nominal <= 4500000)  return 9000;
            if (nominal > 4500000 && nominal <= 5000000)  return 10000;
            return 0;
        case 'pembayaran': {
            const fees = {stor_pinjaman:5000,briva:5000,motor:5000,bpjs:2500,token_listrik:2000};
            return fees[sub_jenis] ?? 0;
        }
        case 'transfer':
            if (nominal <= 2000000) return 10000;
            if (nominal <= 3500000) return 15000;
            if (nominal <= 5000000) return 20000;
            return 0;
        case 'dompet_digital':
            return 3000;
    }
    return 0;
}

async function simpan() {
    const jenis = document.getElementById('selJenis').value;
    if (!jenis) return;

    let sub_jenis = '';
    let nominal = 0;

    if (jenis === 'pembayaran') {
        sub_jenis = document.getElementById('selPembayaran').value;
    } else if (jenis === 'dompet_digital') {
        const platform = document.getElementById('selPlatform').value;
        const djenis   = document.getElementById('selDompetJenis').value;
        sub_jenis = platform + '_' + djenis;
    } else {
        nominal = parseFloat(document.getElementById('inpNominal').value) || 0;
    }

    const keterangan = document.getElementById('inpKeterangan').value;
    const btnSimpan = document.getElementById('btnSimpan');
    btnSimpan.disabled = true;
    btnSimpan.textContent = 'Menyimpan...';

    try {
        const res = await fetch(API + '?action=simpan', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ jenis, sub_jenis, nominal, keterangan })
        });
        const d = await res.json();
        if (!d.success) throw new Error(d.message);

        document.getElementById('strukInvoice').textContent = d.no_invoice;
        document.getElementById('strukFee').textContent = rupiah(d.biaya_admin);
        openModal('modalStruk');

        // Reset form
        document.getElementById('selJenis').value = '';
        document.getElementById('inpNominal').value = '';
        document.getElementById('inpKeterangan').value = '';
        document.getElementById('selPembayaran').value = '';
        document.getElementById('selPlatform').value = '';
        document.getElementById('selDompetJenis').value = 'topup';
        document.getElementById('grpPembayaran').classList.add('d-none');
        document.getElementById('grpDompet').classList.add('d-none');
        document.getElementById('grpNominal').classList.add('d-none');
        document.getElementById('refCard').classList.add('d-none');
        document.getElementById('boxFee').classList.add('d-none');

        loadRiwayat();
    } catch(e) {
        showToast(e.message || 'Terjadi kesalahan', 'error');
    } finally {
        btnSimpan.disabled = false;
        btnSimpan.textContent = 'Simpan Transaksi';
    }
}

const JENIS_LABEL = {
    stor_tunai: 'Stor Tunai',
    tarik_tunai: 'Tarik Tunai',
    pembayaran: 'Pembayaran',
    transfer: 'Transfer',
    dompet_digital: 'Dompet Digital',
};

const SUB_LABEL = {
    stor_pinjaman: 'Stor Pinjaman',
    briva: 'Briva',
    motor: 'Pmbyr Motor',
    bpjs: 'BPJS',
    token_listrik: 'Token Listrik',
    dana_topup: 'Dana (Top Up)',
    dana_tarik: 'Dana (Tarik)',
    gopay_topup: 'GoPay (Top Up)',
    gopay_tarik: 'GoPay (Tarik)',
    ovo_topup: 'OVO (Top Up)',
    ovo_tarik: 'OVO (Tarik)',
    shopeepay_topup: 'ShopeePay (Top Up)',
    shopeepay_tarik: 'ShopeePay (Tarik)',
    linkaja_topup: 'LinkAja (Top Up)',
    linkaja_tarik: 'LinkAja (Tarik)',
};

async function loadRiwayat() {
    const today = new Date().toISOString().split('T')[0];
    const res = await fetch(API + '?action=list&tgl_awal=' + today + '&tgl_akhir=' + today);
    const d = await res.json();
    if (!d.success) return;

    const tbody = document.getElementById('riwayatBody');
    if (!d.data.length) {
        tbody.innerHTML = '<tr><td colspan=\"5\" class=\"text-center text-muted py-4\">Belum ada transaksi hari ini</td></tr>';
        document.getElementById('totalHariIni').textContent = rupiah(0);
        return;
    }

    const total = d.data.reduce((s, r) => s + parseFloat(r.biaya_admin), 0);
    document.getElementById('totalHariIni').textContent = rupiah(total);

    tbody.innerHTML = d.data.map(r => {
        const jam = new Date(r.created_at).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'});
        const subLabel = r.sub_jenis ? (SUB_LABEL[r.sub_jenis] || r.sub_jenis) : '—';
        return '<tr>' +
            '<td>' + jam + '</td>' +
            '<td>' + (JENIS_LABEL[r.jenis] || r.jenis) + '</td>' +
            '<td class=\"text-muted\">' + subLabel + '</td>' +
            '<td class=\"text-end\">' + (r.nominal > 0 ? rupiah(r.nominal) : '—') + '</td>' +
            '<td class=\"text-end fw-semibold text-success\">' + rupiah(r.biaya_admin) + '</td>' +
            '</tr>';
    }).join('');
}

loadRiwayat();
";
require_once __DIR__ . '/includes/footer.php';
?>
