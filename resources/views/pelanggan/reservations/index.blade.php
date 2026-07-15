@extends('layouts.app')

@section('title', 'Riwayat Reservasi')

@section('content')
    <div class="max-w-6xl mx-auto px-6 py-12">

        <div class="mb-8 flex items-end justify-between fade-in">
            <div>
                <span class="section-label">Riwayat</span>
                <h1 class="font-serif text-4xl text-charcoal">Reservasi Saya</h1>
            </div>
            <a href="/reservations/create" class="btn-nude text-xs py-2.5 px-6">Reservasi Baru →</a>
        </div>

        @if($reservations->isNotEmpty())
            <div class="space-y-4">
                @foreach($reservations as $reservation)
                    <div class="border border-graymedium p-5 fade-in" style="background:var(--color-white);">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            {{-- Info Reservasi --}}
                            <div class="flex gap-4 items-start">
                                <div class="w-12 h-12 flex items-center justify-center flex-shrink-0"
                                    style="background:var(--color-blush);">
                                    <span class="text-xl">💆</span>
                                </div>
                                <div>
                                    <h3 class="font-serif text-lg text-charcoal">{{ $reservation->treatment->name }}</h3>
                                    <p class="text-xs text-charcoal-light tracking-wide mt-0.5">
                                        {{ $reservation->schedule_date->format('d M Y') }} ·
                                        {{ substr($reservation->schedule_time, 0, 5) }} WIB
                                    </p>
                                    <p class="text-xs text-charcoal-light mt-0.5">{{ $reservation->treatment->formatted_price }}</p>
                                    @if($reservation->notes)
                                        <p class="text-xs text-charcoal-light mt-1 italic">"{{ $reservation->notes }}"</p>
                                    @endif

                                    {{-- Tampilkan permintaan reschedule yang sedang menunggu --}}
                                    @if($reservation->hasPendingRescheduleRequest())
                                        <div class="mt-2 px-3 py-2 border border-amber-300 bg-amber-50 w-fit">
                                            <p class="text-xs font-semibold text-amber-700 uppercase tracking-wide mb-0.5">Permintaan Ganti Jadwal</p>
                                            <p class="text-xs text-amber-800">
                                                → {{ $reservation->reschedule_requested_date->format('d M Y') }}
                                                · {{ substr($reservation->reschedule_requested_time, 0, 5) }} WIB
                                            </p>
                                            <p class="text-xs text-amber-600 mt-0.5">Menunggu konfirmasi admin</p>
                                        </div>
                                    @endif

                                    {{-- Tampilkan permintaan batal yang sedang menunggu --}}
                                    @if($reservation->hasPendingCancelRequest())
                                        <div class="mt-2 px-3 py-2 border border-amber-300 bg-amber-50 w-fit">
                                            <p class="text-xs font-semibold text-amber-700 uppercase tracking-wide">Permintaan Pembatalan</p>
                                            <p class="text-xs text-amber-600 mt-0.5">Menunggu konfirmasi admin</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Status & Aksi --}}
                            <div class="flex flex-col items-start md:items-end gap-2 min-w-fit">
                                {!! $reservation->status_badge !!}

                                {{-- Alasan pembatalan oleh admin --}}
                                @if($reservation->status === 'cancelled' && $reservation->cancellation_reason)
                                    <div class="w-full md:w-auto max-w-xs border border-red-200 bg-red-50 px-3 py-2">
                                        <p class="text-xs font-semibold text-red-500 tracking-wide uppercase mb-1">Alasan Pembatalan</p>
                                        <p class="text-xs text-red-700 leading-relaxed">{{ $reservation->cancellation_reason }}</p>
                                    </div>
                                @endif

                                {{-- Alasan penolakan reservasi --}}
                                @if($reservation->status === 'rejected' && $reservation->rejection_reason)
                                    <div class="w-full md:w-auto max-w-xs border border-red-200 bg-red-50 px-3 py-2">
                                        <p class="text-xs font-semibold text-red-500 tracking-wide uppercase mb-1">Alasan Penolakan</p>
                                        <p class="text-xs text-red-700 leading-relaxed">{{ $reservation->rejection_reason }}</p>
                                    </div>
                                @endif

                                {{-- Status Pembayaran --}}
                                @if($reservation->payment)
                                    <div class="text-xs text-charcoal-light">
                                        Bayar:
                                        @if($reservation->payment->status === 'pending')
                                            <span class="badge-pending ml-1">Menunggu</span>
                                        @elseif($reservation->payment->status === 'approved')
                                            <span class="badge-approved ml-1">Lunas ✓</span>
                                        @else
                                            <span class="badge-rejected ml-1">Ditolak</span>
                                        @endif
                                    </div>

                                    {{-- Alasan penolakan pembayaran --}}
                                    @if($reservation->payment->status === 'rejected' && isset($reservation->payment->rejection_reason) && $reservation->payment->rejection_reason)
                                        <div class="w-full md:w-auto max-w-xs border border-red-200 bg-red-50 px-3 py-2">
                                            <p class="text-xs font-semibold text-red-500 tracking-wide uppercase mb-1">Alasan Bukti Ditolak</p>
                                            <p class="text-xs text-red-700 leading-relaxed">{{ $reservation->payment->rejection_reason }}</p>
                                        </div>
                                    @endif

                                    {{-- Tombol Kirim Ulang Bukti jika Pembayaran Ditolak --}}
                                    @if($reservation->payment->status === 'rejected' && $reservation->status === 'approved')
                                        <a href="/payments/create/{{ $reservation->id }}" class="btn-nude-tan text-xs py-1.5 px-4">
                                            Kirim Ulang Bukti →
                                        </a>
                                    @endif
                                @endif

                                {{-- Tombol Upload Pembayaran (belum ada payment) --}}
                                @if($reservation->status === 'approved' && !$reservation->payment)
                                    <a href="/payments/create/{{ $reservation->id }}" class="btn-nude-tan text-xs py-1.5 px-4">
                                        Ke Pembayaran →
                                    </a>
                                @endif

                                {{-- Tombol Ganti Jadwal (hanya untuk approved, belum ada payment aktif, tidak ada reschedule pending) --}}
                                @if($reservation->status === 'approved' && (!$reservation->payment || $reservation->payment->status === 'rejected'))
                                    @if(!$reservation->hasPendingRescheduleRequest())
                                        <div class="flex gap-2 mt-1">
                                            <a href="/reservations/{{ $reservation->id }}/reschedule"
                                               class="btn-nude text-xs py-1.5 px-3"
                                               style="border-color: var(--color-tan-dark); color: var(--color-tan-dark);">
                                                Ganti Jadwal
                                            </a>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 border border-dashed border-graymedium" style="background:var(--color-graylight);">
                <p class="font-serif text-2xl text-charcoal/40 mb-3">Belum Ada Reservasi</p>
                <p class="text-xs text-charcoal-light tracking-wide mb-6">Buat reservasi pertama Anda sekarang</p>
                <a href="/reservations/create" class="btn-nude text-xs">Buat Reservasi →</a>
            </div>
        @endif

    </div>
@endsection