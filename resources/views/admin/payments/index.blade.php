@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran')
@section('page-title', 'Verifikasi Pembayaran')

@section('content')

    <div class="mb-5 flex gap-2 flex-wrap">
        <a href="/admin/payments"
            class="btn-nude text-xs py-2 px-4 {{ !request('status') ? 'btn-nude-filled' : '' }}">Semua</a>
        <a href="/admin/payments?status=pending"
            class="btn-nude text-xs py-2 px-4 {{ request('status') === 'pending' ? 'btn-nude-filled' : '' }}">Pending</a>
        <a href="/admin/payments?status=approved"
            class="btn-nude text-xs py-2 px-4 {{ request('status') === 'approved' ? 'btn-nude-filled' : '' }}">Approved</a>
        <a href="/admin/payments?status=rejected"
            class="btn-nude text-xs py-2 px-4 {{ request('status') === 'rejected' ? 'btn-nude-filled' : '' }}">Rejected</a>
    </div>

    <div class="border border-graymedium" style="background:var(--color-white);">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pelanggan</th>
                        <th>Treatment</th>
                        <th>Nominal</th>
                        <th>Bukti Transfer</th>
                        <th>Tgl Upload</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td class="text-charcoal-light">{{ $payment->id }}</td>
                            <td>
                                <p class="font-medium text-sm">{{ $payment->reservation->user->name }}</p>
                                <p class="text-xs text-charcoal-light">Res. #{{ $payment->reservation->id }}</p>
                            </td>
                            <td class="text-sm">{{ $payment->reservation->treatment->name }}</td>
                            <td class="font-semibold text-sm" style="color:var(--color-tan-dark);">
                                {{ $payment->formatted_amount }}</td>
                            <td>
                                <button type="button" onclick="openImageModal('{{ $payment->proof_image_url }}')"
                                    class="block hover:opacity-80 transition-opacity cursor-pointer">
                                    <img src="{{ $payment->proof_image_url }}" alt="Bukti"
                                        style="width:80px; height:60px; object-fit:cover; border:1px solid var(--color-graymedium);">
                                </button>
                            </td>
                            <td class="text-xs text-charcoal-light">{{ $payment->created_at->format('d M Y') }}</td>
                            <td>
                                @if($payment->status === 'pending')
                                    <span class="badge-pending">Pending</span>
                                @elseif($payment->status === 'approved')
                                    <span class="badge-approved">Approved</span>
                                @else
                                    <span class="badge-rejected">Rejected</span>
                                @endif
                            </td>
                            <td>
                                @if($payment->status === 'pending')
                                    {{-- Masih pending: tampilkan tombol aksi --}}
                                    <div class="flex gap-2">
                                        <form id="approve-pay-{{ $payment->id }}" method="POST"
                                            action="/admin/payments/{{ $payment->id }}/verify">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="approve">
                                        </form>
                                        <button type="button" class="btn-nude text-xs py-1.5 px-3"
                                            style="border-color:#059669; color:#059669;"
                                            onclick="confirmAction('approve-pay-{{ $payment->id }}', 'approve', 'Pembayaran #{{ $payment->id }}')">
                                            Setujui
                                        </button>

                                        <form id="reject-pay-{{ $payment->id }}" method="POST"
                                            action="/admin/payments/{{ $payment->id }}/verify">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="reject">
                                        </form>
                                        <button type="button" class="btn-nude btn-danger text-xs py-1.5 px-3"
                                            onclick="confirmReject('reject-pay-{{ $payment->id }}', 'Pembayaran #{{ $payment->id }}')">
                                            Tolak
                                        </button>
                                    </div>

                                @elseif($payment->status === 'approved')
                                    {{-- Sudah disetujui: histori --}}
                                    <div
                                        class="flex items-center gap-1.5 px-3 py-1.5 border border-emerald-200 bg-emerald-50 w-fit">
                                        <svg class="w-3.5 h-3.5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="text-xs font-medium text-emerald-700 tracking-wide">Disetujui</span>
                                    </div>

                                @else
                                    {{-- Sudah ditolak: histori --}}
                                    <div class="flex items-center gap-1.5 px-3 py-1.5 border border-red-200 bg-red-50 w-fit">
                                        <svg class="w-3.5 h-3.5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span class="text-xs font-medium text-red-600 tracking-wide">Ditolak</span>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-charcoal-light text-sm">Tidak ada data pembayaran</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $payments->withQueryString()->links() }}</div>

    {{-- Modal Image Overlay --}}
    <div id="imageModal"
        class="fixed inset-0 z-[60] hidden bg-black/70 flex items-center justify-center p-4 transition-opacity duration-300 opacity-0 backdrop-blur-sm">
        <div class="relative max-w-3xl w-full bg-white border border-graymedium p-2 shadow-2xl flex flex-col transform transition-transform duration-300 scale-95"
            id="imageModalContent">
            {{-- Header / Close button --}}
            <div class="flex justify-between items-center px-4 py-3 border-b border-graymedium mb-4 bg-[#FDFBF9]">
                <h3 class="font-serif text-lg text-charcoal tracking-wide">Bukti Transfer</h3>
                <button type="button" onclick="closeImageModal()"
                    class="text-charcoal-light hover:text-charcoal transition-colors focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Image Container --}}
            <div class="overflow-auto flex justify-center p-2 bg-graylight/30" style="max-height: 75vh;">
                <img id="modalImage" src="" alt="Bukti Transfer" class="max-w-full h-auto object-contain">
            </div>
        </div>
    </div>

    <script>
        function openImageModal(url) {
            const modal = document.getElementById('imageModal');
            const modalContent = document.getElementById('imageModalContent');
            const img = document.getElementById('modalImage');

            img.src = url;
            modal.classList.remove('hidden');

            // Trigger reflow for transition
            void modal.offsetWidth;

            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            const modalContent = document.getElementById('imageModalContent');

            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
                document.getElementById('modalImage').src = ''; // clear image
            }, 300);
        }

        // Close modal on background click
        document.getElementById('imageModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !document.getElementById('imageModal').classList.contains('hidden')) {
                closeImageModal();
            }
        });
    </script>

@endsection