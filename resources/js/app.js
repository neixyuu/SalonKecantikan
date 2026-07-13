// SweetAlert2 Flash Messages
document.addEventListener('DOMContentLoaded', function () {
    // Ambil flash messages dari meta tags (diisi oleh layout)
    const successMsg = document.querySelector('meta[name="flash-success"]')?.content;
    const errorMsg   = document.querySelector('meta[name="flash-error"]')?.content;
    const infoMsg    = document.querySelector('meta[name="flash-info"]')?.content;

    if (successMsg) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: successMsg,
            timer: 2800,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            background: '#FDFBF9',
            color: '#2B2B2B',
            iconColor: '#059669',
        });
    }

    if (errorMsg) {
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: errorMsg,
            timer: 3500,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            background: '#FDFBF9',
            color: '#2B2B2B',
        });
    }

    if (infoMsg) {
        Swal.fire({
            icon: 'info',
            title: 'Info',
            text: infoMsg,
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            background: '#FDFBF9',
            color: '#2B2B2B',
        });
    }
});

// Konfirmasi SweetAlert sebelum approve/reject
function confirmAction(formId, action, name) {
    const isApprove = action === 'approve';
    Swal.fire({
        title: isApprove ? 'Setujui?' : 'Tolak?',
        text: isApprove
            ? `Anda akan menyetujui ${name}. Lanjutkan?`
            : `Anda akan menolak ${name}. Lanjutkan?`,
        icon: isApprove ? 'question' : 'warning',
        showCancelButton: true,
        confirmButtonText: isApprove ? 'Ya, Setujui' : 'Ya, Tolak',
        cancelButtonText: 'Batal',
        confirmButtonColor: isApprove ? '#059669' : '#DC2626',
        cancelButtonColor: '#5A5A5A',
        background: '#FDFBF9',
        color: '#2B2B2B',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

// Konfirmasi hapus
function confirmDelete(formId, name) {
    Swal.fire({
        title: 'Hapus?',
        text: `"${name}" akan dihapus secara permanen.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#5A5A5A',
        background: '#FDFBF9',
        color: '#2B2B2B',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

// Mobile sidebar toggle
const sidebarToggle = document.getElementById('sidebar-toggle');
const sidebar       = document.getElementById('admin-sidebar');
const overlay       = document.getElementById('sidebar-overlay');

if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        overlay?.classList.toggle('hidden');
    });

    overlay?.addEventListener('click', () => {
        sidebar.classList.remove('open');
        overlay.classList.add('hidden');
    });
}
