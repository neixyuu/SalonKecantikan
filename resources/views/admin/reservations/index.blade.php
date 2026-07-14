@extends('layouts.app')

@section('title', 'Verifikasi Reservasi')
@section('page-title', 'Verifikasi Reservasi')

@section('content')

<div class="mb-5 flex gap-2 flex-wrap">
    <a href="/admin/reservations" class="btn-nude text-xs py-2 px-4 {{ !request('status') ? 'btn-nude-filled' : '' }}">Semua</a>
    <a href="/admin/reservations?status=pending" class="btn-nude text-xs py-2 px-4 {{ request('status') === 'pending' ? 'btn-nude-filled' : '' }}">Pending</a>
    <a href="/admin/reservations?status=approved" class="btn-nude text-xs py-2 px-4 {{ request('status') === 'approved' ? 'btn-nude-filled' : '' }}">Approved</a>
    <a href="/admin/reservations?status=rejected" class="btn-nude text-xs py-2 px-4 {{ request('status') === 'rejected' ? 'btn-nude-filled' : '' }}">Rejected</a>
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
                        </td>
                        <td class="text-xs text-charcoal-light max-w-xs">{{ $res->notes ?? '—' }}</td>
                        <td>{!! $res->status_badge !!}</td>
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
                        <td>
                            @if($res->status === 'pending')
                                {{-- Masih pending: tampilkan tombol aksi --}}
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

                            @elseif($res->status === 'approved')
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
                        <td colspan="8" class="text-center py-10 text-charcoal-light text-sm">Tidak ada reservasi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $reservations->withQueryString()->links() }}</div>

@endsection
