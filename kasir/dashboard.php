<?php
require_once __DIR__ . '/backend/config/auth.php';
cekLogin();
$page_title = 'Dashboard';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="main-area">
    <div class="topbar">
        <h1 class="topbar-title">Dashboard</h1>
        <span class="topbar-sub"><?= date('l, d F Y') ?></span>
    </div>

    <div class="page-content">
        <div class="row g-3 mb-3">
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon indigo">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                    </div>
                    <div>
                        <div class="stat-label">Pendapatan Hari Ini</div>
                        <div class="stat-value" id="pendapatanHari">—</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon emerald">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                    </div>
                    <div>
                        <div class="stat-label">Transaksi Hari Ini</div>
                        <div class="stat-value" id="trxHari">—</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon amber">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    </div>
                    <div>
                        <div class="stat-label">Pendapatan Bulan Ini</div>
                        <div class="stat-value" id="pendapatanBulan">—</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon rose">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>
                    <div>
                        <div class="stat-label">Stok Menipis</div>
                        <div class="stat-value" id="stokMenipis">—</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BRI Link Stats -->
        <div class="row g-3 mb-4">
            <div class="col-12"><div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#64748b;padding:2px 0 4px;">BRI Link</div></div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card" style="border-left:3px solid #0284c7;">
                    <div class="stat-icon" style="background:#e0f2fe;color:#0284c7;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                    </div>
                    <div>
                        <div class="stat-label">BRI Link Hari Ini</div>
                        <div class="stat-value" id="brilinkHari" style="font-size:18px;">—</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card" style="border-left:3px solid #0284c7;">
                    <div class="stat-icon" style="background:#e0f2fe;color:#0284c7;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    </div>
                    <div>
                        <div class="stat-label">BRI Link Bulan Ini</div>
                        <div class="stat-value" id="brilinkBulan" style="font-size:18px;">—</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card" style="border-left:3px solid #7c3aed;">
                    <div class="stat-icon" style="background:#ede9fe;color:#7c3aed;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    </div>
                    <div>
                        <div class="stat-label">Jasa Print Hari Ini</div>
                        <div class="stat-value" id="printHari" style="font-size:18px;">—</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card" style="border-left:3px solid #7c3aed;">
                    <div class="stat-icon" style="background:#ede9fe;color:#7c3aed;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    </div>
                    <div>
                        <div class="stat-label">Jasa Print Bulan Ini</div>
                        <div class="stat-value" id="printBulan" style="font-size:18px;">—</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-header px-4 py-3">Pendapatan 7 Hari Terakhir</div>
                    <div class="card-body">
                        <canvas id="grafikPendapatan" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-header px-4 py-3">Produk Terlaris Bulan Ini</div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr><th>#</th><th>Produk</th><th class="text-end">Terjual</th></tr></thead>
                            <tbody id="topProdukBody">
                                <tr><td colspan="3" class="text-center text-muted py-4">Memuat...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$extra_js = 'https://cdn.jsdelivr.net/npm/chart.js';
$inline_js = "
const API = '/kasir/backend/api/reports.php';

async function loadDashboard() {
    const res = await fetch(API + '?action=dashboard');
    const d = await res.json();
    if (!d.success) return;

    document.getElementById('pendapatanHari').textContent  = rupiah(d.pendapatan_hari);
    document.getElementById('trxHari').textContent         = d.trx_hari + ' transaksi';
    document.getElementById('pendapatanBulan').textContent = rupiah(d.pendapatan_bulan);
    document.getElementById('stokMenipis').textContent     = d.stok_menipis + ' produk';
    document.getElementById('brilinkHari').textContent     = rupiah(d.brilink_hari ?? 0);
    document.getElementById('brilinkBulan').textContent    = rupiah(d.brilink_bulan ?? 0);
    document.getElementById('printHari').textContent       = rupiah(d.print_hari ?? 0);
    document.getElementById('printBulan').textContent      = rupiah(d.print_bulan ?? 0);

    const labels = d.grafik.map(g => new Date(g.tgl).toLocaleDateString('id-ID',{day:'numeric',month:'short'}));
    new Chart(document.getElementById('grafikPendapatan').getContext('2d'), {
        type: 'bar',
        data: {
            labels,
            datasets: [
                { label:'Kasir', data: d.grafik.map(g=>g.total), backgroundColor:'rgba(37,99,235,0.12)', borderColor:'#2563eb', borderWidth:2, borderRadius:4 },
                { label:'BRI Link', data: d.grafik.map(g=>g.total_bri||0), backgroundColor:'rgba(2,132,199,0.12)', borderColor:'#0284c7', borderWidth:2, borderRadius:4 },
                { label:'Jasa Print', data: d.grafik.map(g=>g.total_prt||0), backgroundColor:'rgba(124,58,237,0.12)', borderColor:'#7c3aed', borderWidth:2, borderRadius:4 }
            ]
        },
        options: {
            responsive:true,
            plugins:{ legend:{ display:true, labels:{ font:{family:'Inter',size:11} } } },
            scales:{
                y:{ beginAtZero:true, ticks:{ callback: v => 'Rp '+Number(v).toLocaleString('id-ID'), font:{family:'Inter',size:11} }, grid:{color:'#f1f5f9'} },
                x:{ ticks:{ font:{family:'Inter',size:11} }, grid:{display:false} }
            }
        }
    });

    const tbody = document.getElementById('topProdukBody');
    tbody.innerHTML = !d.top_produk.length
        ? '<tr><td colspan=\"3\" class=\"text-center text-muted py-3\">Belum ada data</td></tr>'
        : d.top_produk.map((p,i) => `<tr><td>\${i+1}</td><td class=\"fw-semibold\">\${p.nama}</td><td class=\"text-end\">\${p.total_terjual} pcs</td></tr>`).join('');
}

loadDashboard();
";
require_once __DIR__ . '/includes/footer.php';
?>
