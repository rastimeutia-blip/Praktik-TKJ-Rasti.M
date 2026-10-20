<?php
require_once __DIR__ . '/backend/config/auth.php';
cekAdmin();
$page_title = 'Kelola Pengguna';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="main-area">
    <div class="topbar">
        <h1 class="topbar-title">Kelola Pengguna</h1>
        <button class="btn btn-primary btn-sm ms-auto" onclick="openModal('modalTambah')">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Pengguna
        </button>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Username</th><th>Nama Lengkap</th><th class="text-center">Role</th><th>Dibuat</th><th class="text-center">Aksi</th></tr></thead>
                    <tbody id="tblBody"><tr><td colspan="5" class="text-center text-muted py-4">Memuat...</td></tr></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Tambah Pengguna</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label fw-semibold" style="font-size:13px;">Username</label><input class="form-control" id="a-username" placeholder="contoh: kasir2"></div>
                <div class="mb-3"><label class="form-label fw-semibold" style="font-size:13px;">Nama Lengkap</label><input class="form-control" id="a-nama" placeholder="Nama lengkap"></div>
                <div class="mb-3"><label class="form-label fw-semibold" style="font-size:13px;">Role</label><select class="form-select" id="a-role"><option value="kasir">Kasir</option><option value="admin">Admin</option></select></div>
                <div class="mb-1"><label class="form-label fw-semibold" style="font-size:13px;">Password</label><input class="form-control" id="a-password" type="password" placeholder="Minimal 6 karakter"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="tambah()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Edit Pengguna</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
<input type="hidden" id="e-id">
                 <div class="mb-3"><label class="form-label fw-semibold" style="font-size:13px;">Username</label><input class="form-control" id="e-username"></div>
                <div class="mb-3"><label class="form-label fw-semibold" style="font-size:13px;">Nama Lengkap</label><input class="form-control" id="e-nama"></div>
                <div class="mb-3"><label class="form-label fw-semibold" style="font-size:13px;">Role</label><select class="form-select" id="e-role"><option value="kasir">Kasir</option><option value="admin">Admin</option></select></div>
                <div class="mb-1"><label class="form-label fw-semibold" style="font-size:13px;">Password Baru <span class="fw-normal text-muted">(kosongkan jika tidak diubah)</span></label><input class="form-control" id="e-password" type="password" placeholder="Isi untuk ganti password"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="edit()">Update</button>
            </div>
        </div>
    </div>
</div>

<?php
$inline_js = "
const API = '/kasir/backend/api/users.php';

async function load() {
    const res = await fetch(API + '?act=list');
    const d   = await res.json();
    const tb  = document.getElementById('tblBody');
    if (!d.ok) { tb.innerHTML = '<tr><td colspan=\"5\" class=\"text-center text-muted py-4\">Gagal memuat data</td></tr>'; return; }
    if (!d.data.length) { tb.innerHTML = '<tr><td colspan=\"5\" class=\"text-center text-muted py-4\">Belum ada pengguna</td></tr>'; return; }
    tb.innerHTML = d.data.map(u => `
        <tr>
            <td class=\"fw-semibold\">\${u.username}</td>
            <td>\${u.nama}</td>
            <td class=\"text-center\"><span class=\"badge \${u.role==='admin'?'bg-primary':'bg-success'} fw-normal\">\${u.role}</span></td>
            <td class=\"text-muted\" style=\"font-size:12px;\">\${new Date(u.created_at).toLocaleDateString('id-ID',{day:'numeric',month:'short',year:'numeric'})}</td>
            <td class=\"text-center\">
                <div class=\"d-flex gap-1 justify-content-center\">
                    <button class=\"btn btn-outline-secondary btn-sm\" onclick='openEdit(\${JSON.stringify(u)})'>Edit</button>
                    <button class=\"btn btn-danger btn-sm\" onclick=\"hapus(\${u.id},'\${u.username}')\">Hapus</button>
                </div>
            </td>
        </tr>
    `).join('');
}

async function tambah() {
    const body = { username: document.getElementById('a-username').value.trim(), nama: document.getElementById('a-nama').value.trim(), role: document.getElementById('a-role').value, password: document.getElementById('a-password').value };
    if (!body.username || !body.nama || !body.password) { showToast('Semua field wajib diisi','error'); return; }
    const res = await fetch(API + '?act=tambah', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
    const d   = await res.json();
    showToast(d.msg, d.ok ? 'success' : 'error');
    if (d.ok) { closeModal('modalTambah'); ['a-username','a-nama','a-password'].forEach(id=>document.getElementById(id).value=''); document.getElementById('a-role').value='kasir'; load(); }
}

function openEdit(u) {
    document.getElementById('e-id').value       = u.id;
    document.getElementById('e-username').value = u.username;
    document.getElementById('e-nama').value     = u.nama;
    document.getElementById('e-role').value     = u.role;
    document.getElementById('e-password').value = '';
    openModal('modalEdit');
}

async function edit() {
     const body = { id: document.getElementById('e-id').value, username: document.getElementById('e-username').value.trim(), nama: document.getElementById('e-nama').value.trim(), role: document.getElementById('e-role').value, password: document.getElementById('e-password').value };
     const res = await fetch(API + '?act=edit', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
     const d   = await res.json();
     showToast(d.msg, d.ok ? 'success' : 'error');
     if (d.ok) { closeModal('modalEdit'); load(); }
 }

function hapus(id, username) {
    konfirmasiHapus('Hapus pengguna ' + username + '?', async () => {
        const res = await fetch(API + '?act=hapus', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({id}) });
        const d   = await res.json();
        showToast(d.msg, d.ok ? 'success' : 'error');
        if (d.ok) load();
    });
}

load();
";
require_once __DIR__ . '/includes/footer.php';
?>
