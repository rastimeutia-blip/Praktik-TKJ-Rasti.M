<?php
require_once __DIR__ . '/backend/config/auth.php';
cekAdmin();
$page_title = 'Data Produk';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="main-area">
    <div class="topbar">
        <h1 class="topbar-title">Data Produk</h1>
        <div class="ms-auto d-flex gap-2 align-items-center">
            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari produk..." oninput="filterProduk(this.value)" style="width:200px;">
            <button class="btn btn-primary btn-sm" onclick="openModal('modalTambah')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah
            </button>
        </div>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>Kode</th><th>Nama Produk</th><th>Kategori</th><th class="text-end">Harga</th><th class="text-end">Stok</th><th class="text-center">Aksi</th></tr>
                    </thead>
                    <tbody id="produkBody">
                        <tr><td colspan="6" class="text-center text-muted py-4">Memuat...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Tambah Produk</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label fw-semibold" style="font-size:13px;">Kode Produk</label><input type="text" id="addKode" class="form-control" placeholder="Contoh: PRD011"></div>
                <div class="mb-3"><label class="form-label fw-semibold" style="font-size:13px;">Nama Produk</label><input type="text" id="addNama" class="form-control" placeholder="Nama produk"></div>
                <div class="mb-3"><label class="form-label fw-semibold" style="font-size:13px;">Kategori</label><input type="text" id="addKategori" class="form-control" placeholder="Makanan, Minuman, dll"></div>
                <div class="row g-3">
                    <div class="col"><label class="form-label fw-semibold" style="font-size:13px;">Harga (Rp)</label><input type="number" id="addHarga" class="form-control" placeholder="0" min="0"></div>
                    <div class="col"><label class="form-label fw-semibold" style="font-size:13px;">Stok</label><input type="number" id="addStok" class="form-control" placeholder="0" min="0"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="tambahProduk()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Edit Produk</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <input type="hidden" id="editId">
                <div class="mb-3"><label class="form-label fw-semibold" style="font-size:13px;">Kode Produk</label><input type="text" id="editKode" class="form-control" disabled style="background:#f8fafc;"></div>
                <div class="mb-3"><label class="form-label fw-semibold" style="font-size:13px;">Nama Produk</label><input type="text" id="editNama" class="form-control"></div>
                <div class="mb-3"><label class="form-label fw-semibold" style="font-size:13px;">Kategori</label><input type="text" id="editKategori" class="form-control"></div>
                <div class="row g-3">
                    <div class="col"><label class="form-label fw-semibold" style="font-size:13px;">Harga (Rp)</label><input type="number" id="editHarga" class="form-control" min="0"></div>
                    <div class="col"><label class="form-label fw-semibold" style="font-size:13px;">Stok</label><input type="number" id="editStok" class="form-control" min="0"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="editProduk()">Update</button>
            </div>
        </div>
    </div>
</div>

<?php
$inline_js = "
const API = '/kasir/backend/api/products.php';
let allProduk = [];

async function loadProduk() {
    const res = await fetch(API + '?action=list');
    const d = await res.json();
    if (!d.success) return;
    allProduk = d.data;
    renderTable(allProduk);
}

function renderTable(list) {
    const tbody = document.getElementById('produkBody');
    if (!list.length) { tbody.innerHTML = '<tr><td colspan=\"6\" class=\"text-center text-muted py-4\">Tidak ada produk</td></tr>'; return; }
    tbody.innerHTML = list.map(p => `
        <tr>
            <td><span class=\"badge bg-primary fw-normal\">\${p.kode}</span></td>
            <td class=\"fw-semibold\">\${p.nama}</td>
            <td class=\"text-muted\">\${p.kategori || '—'}</td>
            <td class=\"text-end\">\${rupiah(p.harga)}</td>
            <td class=\"text-end\"><span class=\"badge \${parseInt(p.stok)<=10?'bg-danger':'bg-success'}\">\${p.stok}</span></td>
            <td class=\"text-center\">
                <div class=\"d-flex gap-1 justify-content-center\">
                    <button class=\"btn btn-outline-secondary btn-sm\" onclick=\"openEdit(\${JSON.stringify(p).replace(/\"/g,'&quot;')})\">Edit</button>
                    <button class=\"btn btn-danger btn-sm\" onclick=\"hapusProduk(\${p.id})\">Hapus</button>
                </div>
            </td>
        </tr>
    `).join('');
}

function filterProduk(q) {
    renderTable(allProduk.filter(p => p.nama.toLowerCase().includes(q.toLowerCase()) || p.kode.toLowerCase().includes(q.toLowerCase())));
}

async function tambahProduk() {
    const body = { kode: document.getElementById('addKode').value, nama: document.getElementById('addNama').value, kategori: document.getElementById('addKategori').value, harga: document.getElementById('addHarga').value, stok: document.getElementById('addStok').value };
    const res = await fetch(API + '?action=tambah', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
    const d = await res.json();
    showToast(d.message, d.success ? 'success' : 'error');
    if (d.success) { closeModal('modalTambah'); loadProduk(); }
}

function openEdit(p) {
    document.getElementById('editId').value = p.id;
    document.getElementById('editKode').value = p.kode;
    document.getElementById('editNama').value = p.nama;
    document.getElementById('editKategori').value = p.kategori || '';
    document.getElementById('editHarga').value = p.harga;
    document.getElementById('editStok').value = p.stok;
    openModal('modalEdit');
}

async function editProduk() {
    const body = { id: document.getElementById('editId').value, nama: document.getElementById('editNama').value, kategori: document.getElementById('editKategori').value, harga: document.getElementById('editHarga').value, stok: document.getElementById('editStok').value };
    const res = await fetch(API + '?action=edit', { method:'PUT', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
    const d = await res.json();
    showToast(d.message, d.success ? 'success' : 'error');
    if (d.success) { closeModal('modalEdit'); loadProduk(); }
}

async function hapusProduk(id) {
    konfirmasiHapus('Yakin hapus produk ini?', async () => {
        const res = await fetch(API + '?action=hapus&id=' + id, { method:'DELETE' });
        const d = await res.json();
        showToast(d.message, d.success ? 'success' : 'error');
        if (d.success) loadProduk();
    });
}

loadProduk();
";
require_once __DIR__ . '/includes/footer.php';
?>
