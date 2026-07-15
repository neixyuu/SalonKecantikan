@extends('layouts.app')

@section('title', 'Dashboard Saya')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-12">

    {{-- Welcome --}}
    <div class="mb-10 fade-in">
        <span class="section-label">Selamat Datang</span>
        <h1 class="font-serif text-4xl text-charcoal">{{ $user->name }}</h1>
        <p class="text-sm text-charcoal-light mt-1 tracking-wide">Kelola reservasi dan perawatan kecantikan Anda</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
        <div class="stat-card fade-in">
            <p class="text-xs tracking-widest uppercase text-charcoal-light mb-2">Total Reservasi</p>
            <p class="font-serif text-4xl text-charcoal">{{ $reservationCount }}</p>
        </div>
        <div class="stat-card fade-in">
            <p class="text-xs tracking-widest uppercase text-charcoal-light mb-2">Menunggu Konfirmasi</p>
            <p class="font-serif text-4xl" style="color: var(--color-tan-dark);">{{ $pendingCount }}</p>
        </div>
        <div class="stat-card fade-in">
            <p class="text-xs tracking-widest uppercase text-charcoal-light mb-2">Disetujui</p>
            <p class="font-serif text-4xl" style="color: #059669;">{{ $approvedCount }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Active Reservation --}}
        <div class="lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-serif text-2xl text-charcoal">Reservasi Aktif</h2>
                <a href="/reservations/create" class="btn-nude text-xs py-2 px-5">Reservasi Baru →</a>
            </div>

            @if($activeReservation)
                <div class="border border-graymedium p-6" style="background: var(--color-white);">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <span class="section-label">Treatment</span>
                            <h3 class="font-serif text-2xl text-charcoal">{{ $activeReservation->treatment->name }}</h3>
                        </div>
                        {!! $activeReservation->status_badge !!}
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-xs tracking-widest uppercase text-charcoal-light mb-1">Tanggal</p>
                            <p class="text-sm font-medium">{{ $activeReservation->schedule_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs tracking-widest uppercase text-charcoal-light mb-1">Jam</p>
                            <p class="text-sm font-medium">{{ substr($activeReservation->schedule_time, 0, 5) }} WIB</p>
                        </div>
                        <div>
                            <p class="text-xs tracking-widest uppercase text-charcoal-light mb-1">Harga</p>
                            <p class="text-sm font-medium">{{ $activeReservation->treatment->formatted_price }}</p>
                        </div>
                        <div>
                            <p class="text-xs tracking-widest uppercase text-charcoal-light mb-1">Durasi</p>
                            <p class="text-sm font-medium">{{ $activeReservation->treatment->duration }}</p>
                        </div>
                    </div>

                    @if($activeReservation->status === 'approved' && !$activeReservation->payment)
                        <a href="/payments/create/{{ $activeReservation->id }}" class="btn-nude-tan text-xs py-2.5 px-6 inline-flex items-center">
                            Upload Bukti Pembayaran →
                        </a>
                    @elseif($activeReservation->payment)
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center gap-3">
                                <span class="text-xs tracking-wide text-charcoal-light">Status Pembayaran:</span>
                                @if($activeReservation->payment->status === 'pending')
                                    <span class="badge-pending">Menunggu Verifikasi</span>
                                @elseif($activeReservation->payment->status === 'approved')
                                    <span class="badge-approved">Pembayaran Diterima ✓</span>
                                @else
                                    <span class="badge-rejected">Pembayaran Ditolak</span>
                                @endif
                            </div>
                            {{-- Alasan penolakan pembayaran --}}
                            @if($activeReservation->payment->status === 'rejected' && $activeReservation->payment->rejection_reason)
                                <div class="border border-red-200 bg-red-50 px-4 py-3 mt-1">
                                    <p class="text-xs font-semibold text-red-500 tracking-wide uppercase mb-1">Alasan Penolakan Bukti Bayar</p>
                                    <p class="text-sm text-red-700 leading-relaxed">{{ $activeReservation->payment->rejection_reason }}</p>
                                    <a href="/payments/create/{{ $activeReservation->id }}" class="btn-nude-tan text-xs py-2 px-5 inline-flex items-center mt-3">
                                        Upload Ulang Bukti →
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @else
                <div class="border border-dashed border-graymedium p-12 text-center" style="background: var(--color-graylight);">
                    <p class="font-serif text-xl text-charcoal/40 mb-4">Belum Ada Reservasi Aktif</p>
                    <a href="/reservations/create" class="btn-nude text-xs">Buat Reservasi →</a>
                </div>
            @endif
        </div>

        {{-- Pengumuman Terbaru --}}
        <div>
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-serif text-2xl text-charcoal">Pengumuman</h2>
                <a href="/announcements" class="text-xs text-charcoal-light hover:text-tan-dark tracking-wide">Semua →</a>
            </div>

            @if($announcements->isNotEmpty())
                <div class="space-y-3">
                    @foreach($announcements as $announcement)
                        <div class="p-4 border border-graymedium" style="background: var(--color-white);">
                            <p class="text-xs text-charcoal-light tracking-wide mb-1">{{ $announcement->created_at->format('d M Y') }}</p>
                            <h4 class="font-serif text-base text-charcoal leading-tight mb-1">{{ $announcement->title }}</h4>
                            <p class="text-xs text-charcoal-light leading-relaxed line-clamp-2">{{ $announcement->content }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-6 text-center border border-graymedium">
                    <p class="text-xs text-charcoal-light tracking-wide">Belum ada pengumuman</p>
                </div>
            @endif
        </div>

    </div>

    {{-- Quick Links --}}
    <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-3">
        <a href="/treatments" class="border border-graymedium p-4 text-center hover:border-tan transition-colors group" style="background:var(--color-white);">
            <div class="text-2xl mb-2">💆</div>
            <p class="text-xs tracking-widest uppercase text-charcoal-light group-hover:text-charcoal">Layanan</p>
        </a>
        <a href="/reservations" class="border border-graymedium p-4 text-center hover:border-tan transition-colors group" style="background:var(--color-white);">
            <div class="text-2xl mb-2">📅</div>
            <p class="text-xs tracking-widest uppercase text-charcoal-light group-hover:text-charcoal">Riwayat</p>
        </a>
        <a href="/announcements" class="border border-graymedium p-4 text-center hover:border-tan transition-colors group" style="background:var(--color-white);">
            <div class="text-2xl mb-2">📢</div>
            <p class="text-xs tracking-widest uppercase text-charcoal-light group-hover:text-charcoal">Pengumuman</p>
        </a>
        <a href="/status-akun" class="border border-graymedium p-4 text-center hover:border-tan transition-colors group" style="background:var(--color-white);">
            <div class="text-2xl mb-2">👤</div>
            <p class="text-xs tracking-widest uppercase text-charcoal-light group-hover:text-charcoal">Status Akun</p>
        </a>
    </div>

</div>
@endsection
