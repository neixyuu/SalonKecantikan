@extends('layouts.app')

@section('title', 'Verifikasi Reservasi')
@section('page-title', 'Verifikasi Reservasi')

@section('content')

{{-- Alert permintaan ganti jadwal aktif --}}
@if($pendingRescheduleCount > 0)
<div class="mb-5 p-4 border-l-4 fade-in" style="background:rgba(251,191,36,0.1); border-color:#F59E0B;">
    <div class="flex items-center gap-2 flex-wrap">
        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-sm font-medium text-amber-800">Terdapat permintaan yang perlu ditindaklanjuti:</span>
        <a href="?request_type=reschedule" class="text-xs px-2.5 py-1 border border-amber-400 text-amber-700 hover:bg-amber-100 transition-colors">
            📅 {{ $pendingRescheduleCount }} Permintaan Ganti Jadwal
        </a>
    </div>
</div>
@endif

{{-- Filter Tabs --}}
<div class="mb-5 flex gap-2 flex-wrap">
    <a href="/admin/reservations" class="btn-nude text-xs py-2 px-4 {{ !request('status') && !request('request_type') ? 'btn-nude-filled' : '' }}">Semua</a>
    <a href="/admin/reservations?status=pending" class="btn-nude text-xs py-2 px-4 {{ request('status') === 'pending' ? 'btn-nude-filled' : '' }}">Pending</a>
    <a href="/admin/reservations?status=approved" class="btn-nude text-xs py-2 px-4 {{ request('status') === 'approved' ? 'btn-nude-filled' : '' }}">Approved</a>
    <a href="/admin/reservations?status=rejected" class="btn-nude text-xs py-2 px-4 {{ request('status') === 'rejected' ? 'btn-nude-filled' : '' }}">Rejected</a>
    <a href="/admin/reservations?status=cancelled" class="btn-nude text-xs py-2 px-4 {{ request('status') === 'cancelled' ? 'btn-nude-filled' : '' }}">Cancelled</a>
    @if($pendingRescheduleCount > 0)
        <a href="/admin/reservations?request_type=reschedule" class="text-xs py-2 px-4 border {{ request('request_type') === 'reschedule' ? 'bg-amber-600 text-white border-amber-600' : 'border-amber-400 text-amber-700 hover:bg-amber-50' }} transition-colors">
            📅 Req. Jadwal ({{ $pendingRescheduleCount }})
        </a>
    @endif
</div>

<div class="border border-graymedium" style="background:var(--color-white);">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pelanggan</th>
                    <th>Treatment</th>
                    <th>Jadwal</th>
                    <th>Catatan</th>
                    <th>Status</th>
                    <th>Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $res)
                    <tr>
                        <td class="text-charcoal-light">{{ $res->id }}</td>
                        <td>
                            <p class="font-medium text-sm">{{ $res->user->name }}</p>
                            <p class="text-xs text-charcoal-light">{{ $res->user->email }}</p>
                        </td>
                        <td>
                            <p class="font-medium text-sm">{{ $res->treatment->name }}</p>
                            <p class="text-xs text-charcoal-light">{{ $res->treatment->formatted_price }}</p>
                        </td>
                        <td>
                            <p class="text-sm">{{ $res->schedule_date->format('d M Y') }}</p>
                            <p class="text-xs text-charcoal-light">{{ substr($res->schedule_time, 0, 5) }} WIB</p>
                            {{-- Tampilkan permintaan reschedule --}}
                            @if($res->hasPendingRescheduleRequest())
                                <div class="mt-1.5 px-2 py-1 border border-amber-300 bg-amber-50">
                                    <p class="text-xs text-amber-700 font-medium">→ Req. Ganti:</p>
                                    <p class="text-xs text-amber-800">{{ $res->reschedule_requested_date->format('d M Y') }}</p>
                                    <p class="text-xs text-amber-600">{{ substr($res->reschedule_requested_time, 0, 5) }} WIB</p>
                                </div>
                            @endif
                        </td>
                        <td class="text-xs text-charcoal-light max-w-xs">{{ $res->notes ?? '—' }}</td>
                        <td>
                            {!! $res->status_badge !!}
                            {{-- Tampilkan badge permintaan batal --}}
                            @if($res->hasPendingCancelRequest())
                                <div class="mt-1">
                                    <span class="text-xs px-2 py-0.5 bg-amber-100 text-amber-700 border border-amber-300">Req. Batal</span>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($res->payment)
                                @if($res->payment->status === 'pending')
                                    <span class="badge-pending">Menunggu</span>
                                @elseif($res->payment->status === 'approved')
                                    <span class="badge-approved">Lunas</span>
                                @else
                                    <span class="badge-rejected">Ditolak</span>
                                @endif
                            @else
                                <span class="text-xs text-charcoal-light">—</span>
                            @endif
                        </td>

                        {{-- KOLOM AKSI --}}
                        <td>
                            {{-- [A] Status PENDING: Approve / Reject awal --}}
                            @if($res->status === 'pending')
                                <div class="flex gap-2">
                                    <form id="approve-res-{{ $res->id }}" method="POST" action="/admin/reservations/{{ $res->id }}/verify">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="action" value="approve">
                                    </form>
                                    <button type="button" class="btn-nude text-xs py-1.5 px-3"
                                            style="border-color:#059669; color:#059669;"
                                            onclick="confirmAction('approve-res-{{ $res->id }}', 'approve', 'Reservasi #{{ $res->id }}')">
                                        Setujui
                                    </button>

                                    <form id="reject-res-{{ $res->id }}" method="POST" action="/admin/reservations/{{ $res->id }}/verify">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="action" value="reject">
                                    </form>
                                    <button type="button" class="btn-nude btn-danger text-xs py-1.5 px-3"
                                            onclick="confirmAction('reject-res-{{ $res->id }}', 'reject', 'Reservasi #{{ $res->id }}')">
                                        Tolak
                                    </button>
                                </div>

                            {{-- [B] Status APPROVED: Kelola permintaan atau batalkan --}}
                            @elseif($res->status === 'approved')
                                <div class="flex flex-col gap-2">

                                    {{-- Handle permintaan PEMBATALAN --}}
                                    {{-- Handle permintaan GANTI JADWAL --}}
                                    @if($res->hasPendingRescheduleRequest())
                                        <div class="flex gap-2">
                                            <form id="approve-reschedule-{{ $res->id }}" method="POST" action="/admin/reservations/{{ $res->id }}/handle-reschedule">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="action" value="approve">
                                            </form>
                                            <button type="button" class="btn-nude text-xs py-1 px-2.5"
                                                    style="border-color:#059669; color:#059669;"
                                                    onclick="confirmAction('approve-reschedule-{{ $res->id }}', 'approve', 'ganti jadwal Res #{{ $res->id }}')">
                                                ✓ Setujui Jadwal
                                            </button>

                                            <form id="reject-reschedule-{{ $res->id }}" method="POST" action="/admin/reservations/{{ $res->id }}/handle-reschedule">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="action" value="reject">
                                            </form>
                                            <button type="button" class="btn-nude btn-danger text-xs py-1 px-2.5"
                                                    onclick="confirmAction('reject-reschedule-{{ $res->id }}', 'reject', 'ganti jadwal Res #{{ $res->id }}')">
                                                ✕ Tolak
                                            </button>
                                        </div>

                                    {{-- Tidak ada permintaan aktif: Tombol Batalkan oleh Admin --}}
                                    {{-- Hanya muncul jika pembayaran belum ada, sedang diproses, atau ditolak --}}
                                    @else
                                        @if(!$res->payment || in_array($res->payment->status, ['pending', 'rejected']))
                                            <button type="button"
                                                    class="btn-nude btn-danger text-xs py-1.5 px-3 whitespace-nowrap"
                                                    onclick="openCancelModal({{ $res->id }})">
                                                Batalkan Reservasi
                                            </button>
                                        @else
                                            {{-- Pembayaran sudah lunas: aksi tidak tersedia --}}
                                            <div class="flex items-center gap-1.5 px-3 py-1.5 border border-emerald-200 bg-emerald-50 w-fit">
                                                <svg class="w-3.5 h-3.5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <span class="text-xs font-medium text-emerald-700 tracking-wide">Selesai</span>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                            {{-- [C] Sudah diputuskan (approved via cancel/rejected/cancelled): Badge histori --}}
                            @elseif($res->status === 'approved')
                                <div class="flex items-center gap-1.5 px-3 py-1.5 border border-emerald-200 bg-emerald-50 w-fit">
                                    <svg class="w-3.5 h-3.5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-xs font-medium text-emerald-700 tracking-wide">Disetujui</span>
                                </div>

                            @elseif($res->status === 'rejected')
                                <div class="flex items-center gap-1.5 px-3 py-1.5 border border-red-200 bg-red-50 w-fit">
                                    <svg class="w-3.5 h-3.5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span class="text-xs font-medium text-red-600 tracking-wide">Ditolak</span>
                                </div>

                            @else
                                {{-- cancelled --}}
                                <div class="flex items-center gap-1.5 px-3 py-1.5 border border-red-200 bg-red-50 w-fit">
                                    <svg class="w-3.5 h-3.5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span class="text-xs font-medium text-red-600 tracking-wide">Dibatalkan</span>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-10 text-charcoal-light text-sm">Tidak ada reservasi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $reservations->withQueryString()->links() }}</div>

{{-- Modal: Batalkan Reservasi oleh Admin --}}
<div id="cancelAdminModal" class="fixed inset-0 z-[60] hidden bg-black/60 flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300 opacity-0">
    <div id="cancelAdminModalContent" class="relative bg-white border border-graymedium shadow-2xl w-full max-w-md transform transition-transform duration-300 scale-95">
        <div class="flex justify-between items-center px-5 py-4 border-b border-graymedium" style="background:#FDFBF9;">
            <h3 class="font-serif text-lg text-charcoal">Batalkan Reservasi</h3>
            <button type="button" onclick="closeCancelModal()" class="text-charcoal-light hover:text-charcoal">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="cancelAdminForm" method="POST" class="p-5 space-y-4">
            @csrf @method('PATCH')
            <p class="text-sm text-charcoal-light leading-relaxed">
                Anda akan membatalkan reservasi ini. Pelanggan akan menerima notifikasi alasan pembatalan.
            </p>
            <div>
                <label class="form-label">Alasan Pembatalan <span class="text-red-500">*</span></label>
                <textarea name="cancellation_reason" rows="4"
                          placeholder="Contoh: Jadwal penuh, harap hubungi kami untuk penjadwalan ulang..."
                          class="form-input resize-none" required></textarea>
            </div>
            <div class="flex gap-3 pt-1">
                <button type="submit" class="btn-nude py-2.5 px-6 text-sm"
                        style="background:var(--color-charcoal); color:var(--color-cream); border-color:var(--color-charcoal);">
                    Konfirmasi Batalkan
                </button>
                <button type="button" onclick="closeCancelModal()" class="btn-nude py-2.5 px-6 text-sm">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCancelModal(reservationId) {
        const modal = document.getElementById('cancelAdminModal');
        const modalContent = document.getElementById('cancelAdminModalContent');
        const form = document.getElementById('cancelAdminForm');

        form.action = `/admin/reservations/${reservationId}/cancel-by-admin`;
        modal.classList.remove('hidden');
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        modal.classList.add('opacity-100');
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }

    function closeCancelModal() {
        const modal = document.getElementById('cancelAdminModal');
        const modalContent = document.getElementById('cancelAdminModalContent');
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    document.getElementById('cancelAdminModal').addEventListener('click', function(e) {
        if (e.target === this) closeCancelModal();
    });
</script>

@endsection
