// ── Modal helpers (Bootstrap 5) ──
function openModal(id) {
    bootstrap.Modal.getOrCreateInstance(document.getElementById(id)).show();
}
function closeModal(id) {
    bootstrap.Modal.getInstance(document.getElementById(id))?.hide();
}

// ── Toast ──
function showToast(msg, type = '') {
    const el = document.getElementById('app-toast');
    el.textContent = msg;
    el.className = 'show ' + type;
    clearTimeout(el._t);
    el._t = setTimeout(() => el.className = '', 3000);
}

// ── Format Rupiah ──
function rupiah(n) {
    return 'Rp ' + Number(n).toLocaleString('id-ID');
}

// ── Konfirmasi hapus ──
function konfirmasiHapus(msg, cb) {
    if (confirm(msg || 'Yakin ingin menghapus data ini?')) cb();
}
