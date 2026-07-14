@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran')
@section('page-title', 'Verifikasi Pembayaran')

@section('content')

<div class="mb-5 flex gap-2 flex-wrap">
    <a href="/admin/payments" class="btn-nude text-xs py-2 px-4 {{ !request('status') ? 'btn-nude-filled' : '' }}">Semua</a>
    <a href="/admin/payments?status=pending" class="btn-nude text-xs py-2 px-4 {{ request('status') === 'pending' ? 'btn-nude-filled' : '' }}">Pending</a>
    <a href="/admin/payments?status=approved" class="btn-nude text-xs py-2 px-4 {{ request('status') === 'approved' ? 'btn-nude-filled' : '' }}">Approved</a>
    <a href="/admin/payments?status=rejected" class="btn-nude text-xs py-2 px-4 {{ request('status') === 'rejected' ? 'btn-nude-filled' : '' }}">Rejected</a>
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
                        <td class="font-semibold text-sm" style="color:var(--color-tan-dark);">{{ $payment->formatted_amount }}</td>
                        <td>
                            <a href="{{ $payment->proof_image_url }}" target="_blank"
                               class="block hover:opacity-80 transition-opacity">
                                <img src="{{ $payment->proof_image_url }}" alt="Bukti"
                                     style="width:80px; height:60px; object-fit:cover; border:1px solid var(--color-graymedium);">
                            </a>
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
                                    <form id="approve-pay-{{ $payment->id }}" method="POST" action="/admin/payments/{{ $payment->id }}/verify">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="action" value="approve">
                                    </form>
                                    <button type="button" class="btn-nude text-xs py-1.5 px-3"
                                            style="border-color:#059669; color:#059669;"
                                            onclick="confirmAction('approve-pay-{{ $payment->id }}', 'approve', 'Pembayaran #{{ $payment->id }}')">
                                        Setujui
                                    </button>

                                    <form id="reject-pay-{{ $payment->id }}" method="POST" action="/admin/payments/{{ $payment->id }}/verify">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="action" value="reject">
                                    </form>
                                    <button type="button" class="btn-nude btn-danger text-xs py-1.5 px-3"
                                            onclick="confirmAction('reject-pay-{{ $payment->id }}', 'reject', 'Pembayaran #{{ $payment->id }}')">
                                        Tolak
                                    </button>
                                </div>

                            @elseif($payment->status === 'approved')
                                {{-- Sudah disetujui: histori --}}
                                <div class="flex items-center gap-1.5 px-3 py-1.5 border border-emerald-200 bg-emerald-50 w-fit">
                                    <svg class="w-3.5 h-3.5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-xs font-medium text-emerald-700 tracking-wide">Disetujui</span>
                                </div>

                            @else
                                {{-- Sudah ditolak: histori --}}
                                <div class="flex items-center gap-1.5 px-3 py-1.5 border border-red-200 bg-red-50 w-fit">
                                    <svg class="w-3.5 h-3.5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
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

@endsection
