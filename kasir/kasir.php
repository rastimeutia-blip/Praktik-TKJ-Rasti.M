<?php
require_once __DIR__ . '/backend/config/auth.php';
cekLogin();
$page_title = 'Kasir';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="main-area">
    <div class="topbar">
        <h1 class="topbar-title">Kasir</h1>
        <div class="ms-auto" style="width:220px;">
            <input type="text" id="searchProduk" class="form-control form-control-sm" placeholder="Cari produk...">
        </div>
    </div>

    <div class="page-content" style="padding-bottom:0;">
        <div class="kasir-layout">
            <!-- Kiri: filter + produk -->
            <div class="produk-area">
                <div class="filter-kategori" id="filterKategori"></div>
                <div class="produk-grid" id="produkGrid">
                    <div class="text-muted text-center" style="grid-column:1/-1;padding:40px;">Memuat produk...</div>
                </div>
            </div>

            <!-- Keranjang -->
            <div class="keranjang">
                <div class="keranjang-header">
                    <h3>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;display:inline;vertical-align:middle;margin-right:6px;"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 001.99 1.61h9.72a2 2 0 001.99-1.61L23 6H6"/></svg>
                        Keranjang
                    </h3>
                    <button class="btn btn-outline-secondary btn-sm" onclick="clearCart()">Hapus Semua</button>
                </div>

                <div class="keranjang-items" id="keranjangItems">
                    <div class="empty-cart">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 001.99 1.61h9.72a2 2 0 001.99-1.61L23 6H6"/></svg>
                        <p>Keranjang masih kosong</p>
                    </div>
                </div>

                <div class="keranjang-footer">
                    <div class="total-row">
                        <span class="total-label">Total Pembayaran</span>
                        <span class="total-value" id="totalBayar">Rp 0</span>
                    </div>
                    <button class="btn btn-primary w-100 py-2 fw-semibold" onclick="openModal('modalBayar')" id="btnBayar" disabled>
                        Proses Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Bayar -->
<div class="modal fade" id="modalBayar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Proses Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="rounded-3 p-3 mb-3" style="background:#eff6ff;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted" style="font-size:13px;">Total yang harus dibayar</span>
                        <span id="modalTotal" style="font-size:22px;font-weight:800;color:#2563eb;">Rp 0</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:13px;">Uang Diterima (Rp)</label>
                    <input type="number" id="inputBayar" class="form-control" placeholder="0" min="0" oninput="hitungKembalian()">
                </div>
                <div class="d-flex gap-2 flex-wrap mb-3" id="nominalCepat"></div>
                <div class="rounded-3 p-3 d-flex justify-content-between align-items-center" style="background:#f8fafc;">
                    <span class="text-muted" style="font-size:13px;">Kembalian</span>
                    <span id="labelKembalian" style="font-size:18px;font-weight:800;color:#059669;">Rp 0</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success btn-sm fw-semibold" onclick="prosesBayar()" id="btnProses">
                    Selesaikan Transaksi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Struk -->
<div class="modal fade" id="modalStruk" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transaksi Berhasil</h5>
                <button type="button" class="btn-close" onclick="closeModal('modalStruk');loadProduk()"></button>
            </div>
            <div class="modal-body">
                <div class="struk" id="strukContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="printStruk()">Cetak</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="closeModal('modalStruk');loadProduk()">Transaksi Baru</button>
            </div>
        </div>
    </div>
</div>

<?php
$inline_js = "
let cart = [];
let allProduk = [];
let activeKategori = 'Semua';
const PRODUK_API = '/kasir/backend/api/products.php';
const TRX_API    = '/kasir/backend/api/transactions.php';
const KAT_COLORS = ['#2563eb','#f59e0b','#06b6d4','#8b5cf6','#10b981','#ef4444','#f97316'];

function normKat(k) {
    return (k || '').trim().toLowerCase().replace(/(?:^|\s)\S/g, c => c.toUpperCase());
}

async function loadProduk() {
    const res = await fetch(PRODUK_API + '?action=list');
    const d = await res.json();
    if (!d.success) return;
    allProduk = d.data;
    renderFilterKategori();
    applyFilter();
}

function renderFilterKategori() {
    const raw = [...new Set(allProduk.map(p => normKat(p.kategori)).filter(Boolean))].sort();
    const kategoris = ['Semua', ...raw];
    document.getElementById('filterKategori').innerHTML = kategoris.map((k, i) => {
        const color = KAT_COLORS[i % KAT_COLORS.length];
        const isActive = k === activeKategori;
        const style = isActive
            ? `background:\${color};border-color:\${color};`
            : `color:\${color};border-color:\${color}44;`;
        return `<button class=\"filter-btn \${isActive ? 'active' : ''}\" style=\"\${style}\" onclick=\"setKategori('\${k}')\">\${k}</button>`;
    }).join('');
}

function setKategori(k) {
    activeKategori = k;
    renderFilterKategori();
    applyFilter();
}

function applyFilter() {
    const q = document.getElementById('searchProduk').value.toLowerCase();
    let list = allProduk;
    if (activeKategori !== 'Semua') list = list.filter(p => normKat(p.kategori) === activeKategori);
    if (q) list = list.filter(p => p.nama.toLowerCase().includes(q) || p.kode.toLowerCase().includes(q));
    renderProduk(list);
}

function renderProduk(list) {
    const grid = document.getElementById('produkGrid');
    if (!list.length) {
        grid.innerHTML = '<div class=\"text-muted text-center\" style=\"grid-column:1/-1;padding:40px;\">Produk tidak ditemukan</div>';
        return;
    }
    grid.innerHTML = list.map(p => `
        <div class=\"produk-card \${p.stok <= 0 ? 'stok-habis' : ''}\" onclick=\"addToCart(\${p.id})\">
            <div class=\"p-kode\">\${p.kode}</div>
            <div class=\"p-nama\">\${p.nama}</div>
            <div class=\"p-harga\">\${rupiah(p.harga)}</div>
            <div class=\"p-stok\">Stok: \${p.stok}</div>
        </div>
    `).join('');
}

function addToCart(id) {
    const p = allProduk.find(x => x.id == id);
    if (!p || p.stok <= 0) return;
    const existing = cart.find(x => x.produk_id == id);
    if (existing) {
        if (existing.qty >= p.stok) { showToast('Stok tidak mencukupi','error'); return; }
        existing.qty++;
        existing.subtotal = existing.qty * existing.harga;
    } else {
        cart.push({ produk_id: p.id, nama: p.nama, harga: parseFloat(p.harga), qty: 1, subtotal: parseFloat(p.harga), stok: parseInt(p.stok) });
    }
    renderCart();
}

function removeFromCart(id) {
    cart = cart.filter(x => x.produk_id != id);
    renderCart();
}

function changeQty(id, delta) {
    const item = cart.find(x => x.produk_id == id);
    if (!item) return;
    item.qty += delta;
    if (item.qty <= 0) { removeFromCart(id); return; }
    if (item.qty > item.stok) { item.qty = item.stok; showToast('Stok maksimum','error'); }
    item.subtotal = item.qty * item.harga;
    renderCart();
}

function clearCart() {
    cart = [];
    renderCart();
}

function renderCart() {
    const el  = document.getElementById('keranjangItems');
    const btn = document.getElementById('btnBayar');
    const total = cart.reduce((s,i) => s + i.subtotal, 0);
    document.getElementById('totalBayar').textContent = rupiah(total);

    if (!cart.length) {
        el.innerHTML = `<div class=\"empty-cart\">
            <svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><circle cx=\"9\" cy=\"21\" r=\"1\"/><circle cx=\"20\" cy=\"21\" r=\"1\"/><path d=\"M1 1h4l2.68 13.39a2 2 0 001.99 1.61h9.72a2 2 0 001.99-1.61L23 6H6\"/></svg>
            <p>Keranjang masih kosong</p>
        </div>`;
        btn.disabled = true;
        return;
    }

    btn.disabled = false;
    el.innerHTML = cart.map(item => `
        <div class=\"cart-item\">
            <div class=\"item-info\">
                <div class=\"item-nama\">\${item.nama}</div>
                <div class=\"item-harga\">\${rupiah(item.harga)}</div>
            </div>
            <div class=\"qty-control\">
                <button class=\"qty-btn\" onclick=\"changeQty(\${item.produk_id}, -1)\">&#x2212;</button>
                <span class=\"qty-val\">\${item.qty}</span>
                <button class=\"qty-btn\" onclick=\"changeQty(\${item.produk_id}, 1)\">+</button>
            </div>
            <div class=\"cart-subtotal\">\${rupiah(item.subtotal)}</div>
        </div>
    `).join('');
}

function openModalBayar() {
    const total = cart.reduce((s,i) => s + i.subtotal, 0);
    document.getElementById('modalTotal').textContent = rupiah(total);
    document.getElementById('inputBayar').value = '';
    document.getElementById('labelKembalian').textContent = 'Rp 0';

    const extras = [50000, 100000, 200000, 500000].filter(v => v >= total);
    const all = [...new Set([total, ...extras])].slice(0, 4);
    document.getElementById('nominalCepat').innerHTML = all.map(n =>
        `<button class=\"btn btn-outline-secondary btn-sm\" onclick=\"setNominal(\${n})\">\${rupiah(n)}</button>`
    ).join('');

    openModal('modalBayar');
}

function setNominal(val) {
    document.getElementById('inputBayar').value = val;
    hitungKembalian();
}

function hitungKembalian() {
    const total  = cart.reduce((s,i) => s + i.subtotal, 0);
    const bayar  = parseFloat(document.getElementById('inputBayar').value) || 0;
    const kembal = bayar - total;
    document.getElementById('labelKembalian').textContent = kembal >= 0 ? rupiah(kembal) : '— (Kurang)';
    document.getElementById('labelKembalian').style.color = kembal >= 0 ? 'var(--success)' : 'var(--danger)';
    document.getElementById('btnProses').disabled = kembal < 0;
}

async function prosesBayar() {
    const total = cart.reduce((s,i) => s + i.subtotal, 0);
    const bayar = parseFloat(document.getElementById('inputBayar').value) || 0;
    if (bayar < total) { showToast('Uang bayar kurang','error'); return; }

    const btn = document.getElementById('btnProses');
    btn.disabled = true;
    btn.textContent = 'Memproses...';

    const res = await fetch(TRX_API + '?action=simpan', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({ items: cart, bayar })
    });
    const d = await res.json();
    btn.disabled = false;
    btn.textContent = 'Selesaikan Transaksi';

    if (!d.success) { showToast(d.message,'error'); return; }

    closeModal('modalBayar');
    const cartSnapshot = [...cart];
    cart = [];
    renderCart();
    tampilStruk(d, cartSnapshot);
}

function tampilStruk(d, items) {
    const tgl = new Date().toLocaleString('id-ID', {day:'2-digit',month:'2-digit',year:'numeric',hour:'2-digit',minute:'2-digit'});
    const itemRows = items.map(i =>
        `<div class=\"struk-item-name\">\${i.nama}</div>` +
        `<div class=\"struk-item-sub\"><span>\${i.qty} x \${rupiah(i.harga)}</span><span>\${rupiah(i.subtotal)}</span></div>`
    ).join('');
    document.getElementById('strukContent').innerHTML =
        `<div style=\"text-align:center;margin-bottom:12px;\">` +
            `<div style=\"font-size:16px;font-weight:800;letter-spacing:-0.3px;\">KasirKu</div>` +
            `<div style=\"font-size:11px;color:#64748b;\">Struk Pembelian</div>` +
        `</div>` +
        `<div class=\"struk-divider\"></div>` +
        `<div class=\"struk-row\"><span>No Invoice</span><span><strong>\${d.no_invoice}</strong></span></div>` +
        `<div class=\"struk-row\"><span>Tanggal</span><span>\${tgl}</span></div>` +
        `<div class=\"struk-divider\"></div>` +
        itemRows +
        `<div class=\"struk-divider\"></div>` +
        `<div class=\"struk-total\"><span>TOTAL</span><span>\${rupiah(d.total)}</span></div>` +
        `<div class=\"struk-row\"><span>Bayar</span><span>\${rupiah(d.bayar)}</span></div>` +
        `<div class=\"struk-row\" style=\"color:#059669;font-weight:700;\"><span>Kembalian</span><span>\${rupiah(d.kembalian)}</span></div>` +
        `<div class=\"struk-divider\"></div>` +
        `<div style=\"text-align:center;font-size:11px;color:#94a3b8;\">Terima kasih telah berbelanja!</div>`;
    openModal('modalStruk');
}

function printStruk() {
    const html = document.getElementById('strukContent').innerHTML;
    const w = window.open('', '_blank', 'width=380,height=560,toolbar=0,menubar=0,scrollbars=0');
    w.document.write(
        '<!DOCTYPE html><html><head><title>Struk KasirKu</title><style>' +
        'body{font-family:\"Courier New\",monospace;font-size:12px;padding:20px;color:#000;margin:0;}' +
        '.struk-row{display:flex;justify-content:space-between;margin:2px 0;}' +
        '.struk-item-name{margin-top:6px;font-size:12px;}' +
        '.struk-item-sub{display:flex;justify-content:space-between;padding-left:8px;color:#555;font-size:11px;}' +
        '.struk-divider{border:none;border-top:1px dashed #999;margin:7px 0;}' +
        '.struk-total{display:flex;justify-content:space-between;font-weight:bold;font-size:13px;margin:4px 0;}' +
        '</style></head><body>' + html + '</body></html>'
    );
    w.document.close();
    w.focus();
    setTimeout(function(){ w.print(); w.close(); }, 400);
}

document.getElementById('btnBayar').onclick = openModalBayar;

let searchTimeout;
document.getElementById('searchProduk').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => applyFilter(), 200);
});

loadProduk();
";
require_once __DIR__ . '/includes/footer.php';
?>
