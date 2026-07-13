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
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex gap-4 items-start">
                            {{-- Icon --}}
                            <div class="w-12 h-12 flex items-center justify-center flex-shrink-0" style="background:var(--color-blush);">
                                <span class="text-xl">💆</span>
                            </div>
                            <div>
                                <h3 class="font-serif text-lg text-charcoal">{{ $reservation->treatment->name }}</h3>
                                <p class="text-xs text-charcoal-light tracking-wide mt-0.5">
                                    {{ $reservation->schedule_date->format('d M Y') }} · {{ substr($reservation->schedule_time, 0, 5) }} WIB
                                </p>
                                <p class="text-xs text-charcoal-light mt-0.5">{{ $reservation->treatment->formatted_price }}</p>
                                @if($reservation->notes)
                                    <p class="text-xs text-charcoal-light mt-1 italic">"{{ $reservation->notes }}"</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col items-start md:items-end gap-2">
                            {!! $reservation->status_badge !!}

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
                            @endif

                            @if($reservation->status === 'approved' && !$reservation->payment)
                                <a href="/payments/create/{{ $reservation->id }}" class="btn-nude-tan text-xs py-1.5 px-4">
                                    Upload Bukti →
                                </a>
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
