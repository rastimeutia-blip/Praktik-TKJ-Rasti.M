<?php
require_once __DIR__ . '/backend/config/auth.php';
cekAdmin();
$page_title = 'Laporan Penjualan';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="main-area">
    <div class="topbar">
        <h1 class="topbar-title">Laporan Penjualan</h1>
        <div class="ms-auto d-flex gap-2 align-items-center">
            <button class="btn btn-success btn-sm fw-semibold" onclick="exportExcel()" id="btnExcel" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;margin-right:4px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                Export Excel
            </button>
        </div>
    </div>

    <div class="page-content">
        <div class="card mb-4">
            <div class="card-body d-flex align-items-end gap-3 flex-wrap py-3">
                <div><label class="form-label fw-semibold mb-1" style="font-size:12px;">Tanggal Awal</label><input type="date" id="tglAwal" class="form-control form-control-sm" style="width:155px;"></div>
                <div><label class="form-label fw-semibold mb-1" style="font-size:12px;">Tanggal Akhir</label><input type="date" id="tglAkhir" class="form-control form-control-sm" style="width:155px;"></div>
                <button class="btn btn-primary btn-sm" onclick="loadLaporan()">Generate</button>
            </div>
        </div>

        <div class="row g-3 mb-4" id="summaryCards" style="display:none!important;">
            <div class="col-md-4"><div class="card text-center"><div class="card-body py-4"><div class="text-muted mb-1" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Total Transaksi Kasir</div><div id="sumTrx" style="font-size:28px;font-weight:800;color:#0f172a;letter-spacing:-1px;">0</div></div></div></div>
            <div class="col-md-4"><div class="card text-center"><div class="card-body py-4"><div class="text-muted mb-1" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Pendapatan Kasir</div><div id="sumPendapatan" style="font-size:22px;font-weight:800;color:#0f172a;letter-spacing:-0.5px;">Rp 0</div></div></div></div>
            <div class="col-md-4"><div class="card text-center"><div class="card-body py-4"><div class="text-muted mb-1" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Rata-rata / Transaksi</div><div id="sumRata" style="font-size:22px;font-weight:800;color:#0f172a;letter-spacing:-0.5px;">Rp 0</div></div></div></div>
        </div>

        <!-- BRI Link Summary -->
        <div class="row g-3 mb-3" id="summaryBri" style="display:none!important;">
            <div class="col-12"><div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#0284c7;padding:2px 0 4px;">BRI Link</div></div>
            <div class="col-md-6"><div class="card text-center" style="border-top:3px solid #0284c7;"><div class="card-body py-3"><div class="text-muted mb-1" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Transaksi BRI Link</div><div id="sumBriTrx" style="font-size:24px;font-weight:800;color:#0284c7;letter-spacing:-1px;">0</div></div></div></div>
            <div class="col-md-6"><div class="card text-center" style="border-top:3px solid #0284c7;"><div class="card-body py-3"><div class="text-muted mb-1" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Pendapatan BRI Link</div><div id="sumBriPendapatan" style="font-size:20px;font-weight:800;color:#0284c7;letter-spacing:-0.5px;">Rp 0</div></div></div></div>
        </div>

        <!-- Print Summary -->
        <div class="row g-3 mb-4" id="summaryPrint" style="display:none!important;">
            <div class="col-12"><div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#7c3aed;padding:2px 0 4px;">Jasa Print</div></div>
            <div class="col-md-4"><div class="card text-center" style="border-top:3px solid #7c3aed;"><div class="card-body py-3"><div class="text-muted mb-1" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Transaksi Print</div><div id="sumPrtTrx" style="font-size:24px;font-weight:800;color:#7c3aed;letter-spacing:-1px;">0</div></div></div></div>
            <div class="col-md-4"><div class="card text-center" style="border-top:3px solid #7c3aed;"><div class="card-body py-3"><div class="text-muted mb-1" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Pendapatan Print</div><div id="sumPrtPendapatan" style="font-size:20px;font-weight:800;color:#7c3aed;letter-spacing:-0.5px;">Rp 0</div></div></div></div>
            <div class="col-md-4"><div class="card text-center" style="border-top:3px solid #16a34a;"><div class="card-body py-3"><div class="text-muted mb-1" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Total Semua Pendapatan</div><div id="sumGrandTotal" style="font-size:20px;font-weight:800;color:#16a34a;letter-spacing:-0.5px;">Rp 0</div></div></div></div>
        </div>

        <div class="row g-3 mb-4" id="grafikSection" style="display:none!important;">
            <div class="col-lg-7"><div class="card h-100"><div class="card-header px-4 py-3">Pendapatan Harian</div><div class="card-body"><canvas id="grafikHarian" height="200"></canvas></div></div></div>
            <div class="col-lg-5">
                <div class="card h-100"><div class="card-header px-4 py-3">Produk Terlaris</div>
                    <div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>#</th><th>Produk</th><th class="text-end">Qty</th><th class="text-end">Omzet</th></tr></thead><tbody id="produkLarisBody"><tr><td colspan="4" class="text-center text-muted py-3">—</td></tr></tbody></table></div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4" id="briSection" style="display:none;">
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-header px-4 py-3">Pendapatan BRI Link per Jenis</div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr><th>Jenis</th><th class="text-end">Transaksi</th><th class="text-end">Pendapatan</th></tr></thead>
                            <tbody id="briJenisBody"><tr><td colspan="3" class="text-center text-muted py-3">—</td></tr></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-header px-4 py-3">Rincian Jasa Print</div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="font-size:12px;">
                            <thead><tr><th>Waktu</th><th>Jenis</th><th class="text-end">Lembar</th><th class="text-end">Total</th><th>Keterangan</th></tr></thead>
                            <tbody id="printBody"><tr><td colspan="5" class="text-center text-muted py-3">—</td></tr></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" id="tabelSection" style="display:none;">
            <div class="card-header px-4 py-3">Rincian Per Hari (Kasir)</div>
            <div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Tanggal</th><th class="text-end">Jumlah Transaksi</th><th class="text-end">Total Pendapatan</th></tr></thead><tbody id="hariBody"><tr><td colspan="3" class="text-center text-muted py-3">—</td></tr></tbody></table></div>
        </div>
    </div>
</div>

<?php
$inline_js = "
const API = '/kasir/backend/api/reports.php';
let chartInstance = null;
let laporanData = null;
const today = new Date().toISOString().split('T')[0];
const firstDay = today.slice(0,7) + '-01';
document.getElementById('tglAwal').value  = firstDay;
document.getElementById('tglAkhir').value = today;

async function loadLaporan() {
    const tglAwal  = document.getElementById('tglAwal').value;
    const tglAkhir = document.getElementById('tglAkhir').value;
    const res = await fetch(API + `?action=laporan&tgl_awal=\${tglAwal}&tgl_akhir=\${tglAkhir}`);
    const d   = await res.json();
    if (!d.success) { showToast('Gagal memuat laporan','error'); return; }
    laporanData = d;
    laporanData.tglAwal  = tglAwal;
    laporanData.tglAkhir = tglAkhir;
    document.getElementById('btnExcel').disabled = false;

    const kasirPendapatan = parseFloat(d.summary.total_pendapatan);
    const briPendapatan   = parseFloat(d.summary_bri?.total_pendapatan ?? 0);
    const printPendapatan = parseFloat(d.summary_print?.total_pendapatan ?? 0);
    const grandTotal      = kasirPendapatan + briPendapatan + printPendapatan;

    document.getElementById('sumTrx').textContent           = d.summary.total_transaksi;
    document.getElementById('sumPendapatan').textContent    = rupiah(kasirPendapatan);
    document.getElementById('sumRata').textContent          = rupiah(Math.round(d.summary.rata_rata));
    document.getElementById('sumBriTrx').textContent        = d.summary_bri?.total_transaksi ?? 0;
    document.getElementById('sumBriPendapatan').textContent = rupiah(briPendapatan);
    document.getElementById('sumPrtTrx').textContent        = d.summary_print?.total_transaksi ?? 0;
    document.getElementById('sumPrtPendapatan').textContent = rupiah(printPendapatan);
    document.getElementById('sumGrandTotal').textContent    = rupiah(grandTotal);

    document.getElementById('summaryCards').style.display  = '';
    document.getElementById('summaryBri').style.display    = '';
    document.getElementById('summaryPrint').style.display  = '';
    document.getElementById('grafikSection').style.display = '';
    document.getElementById('tabelSection').style.display  = 'block';
    document.getElementById('briSection').style.display    = 'block';

    const JENIS_LABEL = {stor_tunai:'Stor Tunai',tarik_tunai:'Tarik Tunai',pembayaran:'Pembayaran',transfer:'Transfer',dompet_digital:'Dompet Digital'};
    document.getElementById('briJenisBody').innerHTML = !d.brilink_per_jenis?.length
        ? '<tr><td colspan=\"3\" class=\"text-center text-muted py-3\">Tidak ada transaksi BRI Link</td></tr>'
        : d.brilink_per_jenis.map(r => '<tr><td>'+(JENIS_LABEL[r.jenis]||r.jenis)+'</td><td class=\"text-end text-muted\">'+r.jml+'x</td><td class=\"text-end fw-semibold\" style=\"color:#0284c7;\">'+rupiah(r.total)+'</td></tr>').join('');

    document.getElementById('printBody').innerHTML = !d.print_detail?.length
        ? '<tr><td colspan=\"5\" class=\"text-center text-muted py-3\">Tidak ada transaksi</td></tr>'
        : d.print_detail.map(r => {
            const jam = new Date(r.created_at).toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'});
            const tgl = new Date(r.created_at).toLocaleDateString('id-ID',{day:'numeric',month:'short'});
            return '<tr><td>'+tgl+' '+jam+'</td><td>'+(r.jenis==='print'?'Print':'Fotokopi')+'</td><td class=\"text-end\">'+r.jumlah_lembar+' lbr</td><td class=\"text-end fw-semibold\">'+rupiah(r.total)+'</td><td class=\"text-muted\">'+(r.keterangan||'—')+'</td></tr>';
          }).join('');

    if (chartInstance) chartInstance.destroy();
    chartInstance = new Chart(document.getElementById('grafikHarian').getContext('2d'), {
        type:'line',
        data:{ labels: d.per_hari.map(h=>new Date(h.tgl).toLocaleDateString('id-ID',{day:'numeric',month:'short'})), datasets:[{ label:'Pendapatan', data:d.per_hari.map(h=>h.total), borderColor:'#2563eb', backgroundColor:'rgba(37,99,235,0.08)', tension:0.4, fill:true, pointBackgroundColor:'#2563eb', pointRadius:4 }] },
        options:{ responsive:true, plugins:{legend:{display:false}}, scales:{ y:{beginAtZero:true,ticks:{callback:v=>'Rp '+Number(v).toLocaleString('id-ID'),font:{family:'Inter',size:11}},grid:{color:'#f1f5f9'}}, x:{ticks:{font:{family:'Inter',size:11}},grid:{display:false}} } }
    });

    document.getElementById('produkLarisBody').innerHTML = !d.produk_terlaris.length
        ? '<tr><td colspan=\"4\" class=\"text-center text-muted py-3\">Belum ada data</td></tr>'
        : d.produk_terlaris.map((p,i) => `<tr><td>\${i+1}</td><td class=\"fw-semibold\">\${p.nama}<br><small class=\"text-muted\">\${p.kode}</small></td><td class=\"text-end\">\${p.total_qty} pcs</td><td class=\"text-end fw-semibold\">\${rupiah(p.total_omzet)}</td></tr>`).join('');

    document.getElementById('hariBody').innerHTML = !d.per_hari.length
        ? '<tr><td colspan=\"3\" class=\"text-center text-muted py-3\">Tidak ada transaksi</td></tr>'
        : d.per_hari.map(h => `<tr><td>\${new Date(h.tgl).toLocaleDateString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric'})}</td><td class=\"text-end text-muted\">\${h.jml_trx} transaksi</td><td class=\"text-end fw-semibold\">\${rupiah(h.total)}</td></tr>`).join('');
}

function fmtTgl(str) {
    return new Date(str).toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'});
}
function fmtRp(n) { return 'Rp ' + Number(n).toLocaleString('id-ID'); }

async function exportExcel() {
    if (!laporanData) return;
    if (!window.XLSX) {
        await new Promise((res, rej) => {
            const s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js';
            s.onload = res; s.onerror = rej;
            document.head.appendChild(s);
        });
    }
    const d = laporanData;
    const wb = XLSX.utils.book_new();

    // ── Sheet 1: Ringkasan ────────────────────────────────────────
    const periodeStr    = fmtTgl(d.tglAwal) + ' s/d ' + fmtTgl(d.tglAkhir);
    const kasirTotal    = Number(d.summary.total_pendapatan);
    const briTotal      = Number(d.summary_bri?.total_pendapatan ?? 0);
    const printTotal    = Number(d.summary_print?.total_pendapatan ?? 0);
    const grandTotalXls = kasirTotal + briTotal + printTotal;

    const s1 = [
        ['LAPORAN PENDAPATAN — KASIRKU'],
        ['Periode: ' + periodeStr],
        ['Dicetak: ' + new Date().toLocaleString('id-ID')],
        [],
        ['RINGKASAN PENDAPATAN'],
        ['Sumber', '', 'Transaksi', 'Total (Rp)'],
        ['Kasir (Penjualan)', '', Number(d.summary.total_transaksi), kasirTotal],
        ['BRI Link (Biaya Admin)', '', Number(d.summary_bri?.total_transaksi ?? 0), briTotal],
        ['Jasa Print & Fotokopi', '', Number(d.summary_print?.total_transaksi ?? 0), printTotal],
        ['', '', 'GRAND TOTAL', grandTotalXls],
        [],
        ['Rata-rata / Transaksi Kasir', '', '', Math.round(Number(d.summary.rata_rata))],
        [],
        ['PENDAPATAN KASIR PER HARI'],
        ['No', 'Tanggal', 'Jumlah Transaksi', 'Total Pendapatan (Rp)'],
        ...d.per_hari.map((h,i) => [
            i+1,
            new Date(h.tgl).toLocaleDateString('id-ID',{weekday:'long',day:'2-digit',month:'long',year:'numeric'}),
            Number(h.jml_trx),
            Number(h.total)
        ]),
        [],
        ['', '', 'TOTAL', d.per_hari.reduce((s,h)=>s+Number(h.total),0)]
    ];
    const ws1 = XLSX.utils.aoa_to_sheet(s1);
    ws1['!cols'] = [{wch:30},{wch:4},{wch:22},{wch:24}];
    ws1['!merges'] = [
        {s:{r:0,c:0}, e:{r:0,c:3}},
        {s:{r:1,c:0}, e:{r:1,c:3}},
        {s:{r:2,c:0}, e:{r:2,c:3}},
        {s:{r:4,c:0}, e:{r:4,c:3}},
        {s:{r:13,c:0}, e:{r:13,c:3}},
    ];
    XLSX.utils.book_append_sheet(wb, ws1, 'Ringkasan');

    // ── Sheet 2: Produk Terlaris ──────────────────────────────────
    const s2 = [
        ['PRODUK TERLARIS — ' + periodeStr],
        [],
        ['No', 'Kode Produk', 'Nama Produk', 'Total Qty (pcs)', 'Total Omzet (Rp)'],
        ...d.produk_terlaris.map((p,i) => [
            i+1,
            p.kode,
            p.nama,
            Number(p.total_qty),
            Number(p.total_omzet)
        ]),
        [],
        ['', '', 'TOTAL',
            d.produk_terlaris.reduce((s,p)=>s+Number(p.total_qty),0),
            d.produk_terlaris.reduce((s,p)=>s+Number(p.total_omzet),0)
        ]
    ];
    const ws2 = XLSX.utils.aoa_to_sheet(s2);
    ws2['!cols'] = [{wch:4},{wch:14},{wch:28},{wch:18},{wch:22}];
    ws2['!merges'] = [{s:{r:0,c:0},e:{r:0,c:4}}];
    XLSX.utils.book_append_sheet(wb, ws2, 'Produk Terlaris');

    // ── Sheet 3: Jasa Print ──────────────────────────────────────
    const s3 = [
        ['JASA PRINT & FOTOKOPI — ' + periodeStr],
        [],
        ['No', 'Tanggal', 'Jenis', 'Jumlah Lembar', 'Harga/Lembar (Rp)', 'Total (Rp)', 'Keterangan'],
        ...(d.print_detail || []).map((p,i) => [
            i+1,
            new Date(p.created_at).toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric',hour:'2-digit',minute:'2-digit'}),
            p.jenis === 'print' ? 'Print' : 'Fotokopi',
            Number(p.jumlah_lembar),
            Number(p.harga_per_lembar),
            Number(p.total),
            p.keterangan || ''
        ]),
        [],
        ['', '', '', '', 'TOTAL', (d.print_detail||[]).reduce((s,p)=>s+Number(p.total),0), '']
    ];
    const ws3 = XLSX.utils.aoa_to_sheet(s3);
    ws3['!cols'] = [{wch:4},{wch:28},{wch:12},{wch:16},{wch:20},{wch:18},{wch:24}];
    ws3['!merges'] = [{s:{r:0,c:0},e:{r:0,c:6}}];
    XLSX.utils.book_append_sheet(wb, ws3, 'Jasa Print');

    const fname = 'Laporan_KasirKu_' + d.tglAwal + '_sd_' + d.tglAkhir + '.xlsx';
    XLSX.writeFile(wb, fname);
}

loadLaporan();
";
$extra_js = 'https://cdn.jsdelivr.net/npm/chart.js';
require_once __DIR__ . '/includes/footer.php';
?>
