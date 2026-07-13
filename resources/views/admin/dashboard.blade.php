@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    <div class="stat-card fade-in">
        <p class="text-xs tracking-widest uppercase text-charcoal-light mb-2">Total Pelanggan</p>
        <p class="font-serif text-4xl text-charcoal">{{ $stats['total_users'] }}</p>
        @if($stats['pending_users'] > 0)
            <p class="text-xs text-amber-600 mt-1">{{ $stats['pending_users'] }} menunggu verifikasi</p>
        @endif
    </div>
    <div class="stat-card fade-in">
        <p class="text-xs tracking-widest uppercase text-charcoal-light mb-2">Total Reservasi</p>
        <p class="font-serif text-4xl text-charcoal">{{ $stats['total_reservations'] }}</p>
        @if($stats['pending_reservations'] > 0)
            <p class="text-xs text-amber-600 mt-1">{{ $stats['pending_reservations'] }} menunggu verifikasi</p>
        @endif
    </div>
    <div class="stat-card fade-in">
        <p class="text-xs tracking-widest uppercase text-charcoal-light mb-2">Total Pembayaran</p>
        <p class="font-serif text-4xl text-charcoal">{{ $stats['total_payments'] }}</p>
        @if($stats['pending_payments'] > 0)
            <p class="text-xs text-amber-600 mt-1">{{ $stats['pending_payments'] }} menunggu verifikasi</p>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Recent Reservations --}}
    <div class="border border-graymedium" style="background:var(--color-white);">
        <div class="px-5 py-4 border-b border-graymedium flex items-center justify-between">
            <h2 class="font-serif text-lg text-charcoal">Reservasi Terbaru</h2>
            <a href="/admin/reservations" class="text-xs text-charcoal-light hover:text-tan-dark tracking-wide">Semua →</a>
        </div>
        <div class="divide-y divide-graymedium">
            @forelse($recentReservations as $res)
                <div class="px-5 py-3 flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-charcoal">{{ $res->user->name }}</p>
                        <p class="text-xs text-charcoal-light">{{ $res->treatment->name }} · {{ $res->schedule_date->format('d M Y') }}</p>
                    </div>
                    {!! $res->status_badge !!}
                </div>
            @empty
                <div class="px-5 py-8 text-center">
                    <p class="text-xs text-charcoal-light tracking-wide">Belum ada reservasi</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Recent Users --}}
    <div class="border border-graymedium" style="background:var(--color-white);">
        <div class="px-5 py-4 border-b border-graymedium flex items-center justify-between">
            <h2 class="font-serif text-lg text-charcoal">Pendaftaran Terbaru</h2>
            <a href="/admin/users" class="text-xs text-charcoal-light hover:text-tan-dark tracking-wide">Semua →</a>
        </div>
        <div class="divide-y divide-graymedium">
            @forelse($recentUsers as $user)
                <div class="px-5 py-3 flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-charcoal">{{ $user->name }}</p>
                        <p class="text-xs text-charcoal-light">{{ $user->email }}</p>
                    </div>
                    @if($user->account_status === 'pending')
                        <span class="badge-pending">Pending</span>
                    @elseif($user->account_status === 'verified')
                        <span class="badge-approved">Verified</span>
                    @else
                        <span class="badge-rejected">Rejected</span>
                    @endif
                </div>
            @empty
                <div class="px-5 py-8 text-center">
                    <p class="text-xs text-charcoal-light tracking-wide">Belum ada pelanggan</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

{{-- Quick Actions --}}
<div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-3">
    <a href="/admin/users?status=pending" class="border border-graymedium p-4 text-center hover:border-tan transition-colors group" style="background:var(--color-white);">
        <div class="text-2xl mb-2">👤</div>
        <p class="text-xs tracking-widest uppercase text-charcoal-light group-hover:text-charcoal">Verifikasi Akun</p>
        @if($stats['pending_users'] > 0)
            <span class="mt-1 badge-pending text-xs block">{{ $stats['pending_users'] }} pending</span>
        @endif
    </a>
    <a href="/admin/reservations?status=pending" class="border border-graymedium p-4 text-center hover:border-tan transition-colors group" style="background:var(--color-white);">
        <div class="text-2xl mb-2">📅</div>
        <p class="text-xs tracking-widest uppercase text-charcoal-light group-hover:text-charcoal">Verifikasi Reservasi</p>
        @if($stats['pending_reservations'] > 0)
            <span class="mt-1 badge-pending text-xs block">{{ $stats['pending_reservations'] }} pending</span>
        @endif
    </a>
    <a href="/admin/payments?status=pending" class="border border-graymedium p-4 text-center hover:border-tan transition-colors group" style="background:var(--color-white);">
        <div class="text-2xl mb-2">💳</div>
        <p class="text-xs tracking-widest uppercase text-charcoal-light group-hover:text-charcoal">Verifikasi Bayar</p>
        @if($stats['pending_payments'] > 0)
            <span class="mt-1 badge-pending text-xs block">{{ $stats['pending_payments'] }} pending</span>
        @endif
    </a>
    <a href="/admin/announcements/create" class="border border-graymedium p-4 text-center hover:border-tan transition-colors group" style="background:var(--color-white);">
        <div class="text-2xl mb-2">📢</div>
        <p class="text-xs tracking-widest uppercase text-charcoal-light group-hover:text-charcoal">Buat Pengumuman</p>
    </a>
</div>

@endsection
