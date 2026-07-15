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
window.confirmAction = function(formId, action, name) {
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
};

// Konfirmasi hapus
window.confirmDelete = function(formId, name) {
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
};

// Konfirmasi TOLAK dengan input alasan (textarea)
window.confirmReject = function(formId, name, withEmail = false) {
    const emailNote = withEmail
        ? '<p style="font-size:12px; color:#9B8E85; margin-top:8px;">Alasan ini akan dikirimkan ke email pelanggan yang bersangkutan.</p>'
        : '<p style="font-size:12px; color:#9B8E85; margin-top:8px;">Alasan ini akan ditampilkan kepada pelanggan di halaman mereka.</p>';

    Swal.fire({
        title: 'Tolak?',
        html: `
            <p style="font-size:14px; color:#6B5E52; margin-bottom:12px;">Anda akan menolak <strong>${name}</strong>.</p>
            <textarea id="swal-rejection-reason"
                placeholder="Tulis alasan penolakan di sini..."
                style="width:100%; min-height:100px; padding:10px 12px; border:1px solid #E0D8D0;
                       font-size:13px; color:#3A3530; background:#FDFBF9; resize:vertical;
                       font-family:inherit; outline:none; border-radius:0;"
            ></textarea>
            ${emailNote}
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Tolak',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#5A5A5A',
        background: '#FDFBF9',
        color: '#2B2B2B',
        preConfirm: () => {
            return document.getElementById('swal-rejection-reason').value.trim();
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(formId);
            // Hapus input lama jika ada (hindari duplikat)
            const existing = form.querySelector('input[name="rejection_reason"]');
            if (existing) existing.remove();
            // Injeksi alasan ke form sebagai hidden input
            const input = document.createElement('input');
            input.type  = 'hidden';
            input.name  = 'rejection_reason';
            input.value = result.value || '';
            form.appendChild(input);
            form.submit();
        }
    });
};

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
