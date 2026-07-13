@extends('layouts.app')

@section('title', 'Status Akun')

@section('content')
<div class="max-w-2xl mx-auto px-6 py-16">
    <div class="mb-8">
        <span class="section-label">Akun Saya</span>
        <h1 class="font-serif text-4xl text-charcoal">Status Pendaftaran</h1>
    </div>

    {{-- Status Card --}}
    <div class="border border-graymedium p-8 text-center fade-in" style="background: var(--color-white);">
        @if($user->account_status === 'pending')
            <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6" style="background-color: #FEF3C7;">
                <svg class="w-10 h-10" style="color: #92400E;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="badge-pending text-sm mb-4 mx-auto block w-fit">Menunggu Verifikasi</span>
            <h2 class="font-serif text-2xl text-charcoal mb-3">Pendaftaran Anda Sedang Diproses</h2>
            <p class="text-sm text-charcoal-light leading-relaxed max-w-sm mx-auto">
                Akun Anda sedang dalam proses verifikasi oleh admin kami. Biasanya membutuhkan waktu 1×24 jam. Anda akan mendapatkan akses penuh setelah akun diverifikasi.
            </p>

        @elseif($user->account_status === 'verified')
            <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6" style="background-color: #D1FAE5;">
                <svg class="w-10 h-10" style="color: #065F46;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <span class="badge-approved text-sm mb-4 mx-auto block w-fit">Terverifikasi</span>
            <h2 class="font-serif text-2xl text-charcoal mb-3">Akun Anda Telah Diverifikasi!</h2>
            <p class="text-sm text-charcoal-light leading-relaxed max-w-sm mx-auto mb-6">
                Selamat! Akun Anda telah aktif dan Anda memiliki akses penuh ke semua layanan salon kami.
            </p>
            <a href="/dashboard" class="btn-nude-filled text-xs py-3 px-8">Ke Dashboard →</a>

        @elseif($user->account_status === 'rejected')
            <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6" style="background-color: #FEE2E2;">
                <svg class="w-10 h-10" style="color: #991B1B;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="badge-rejected text-sm mb-4 mx-auto block w-fit">Ditolak</span>
            <h2 class="font-serif text-2xl text-charcoal mb-3">Pendaftaran Ditolak</h2>
            <p class="text-sm text-charcoal-light leading-relaxed max-w-sm mx-auto mb-6">
                Maaf, pendaftaran Anda tidak dapat kami setujui. Silakan hubungi kami untuk informasi lebih lanjut atau daftar dengan data yang benar.
            </p>
        @endif
    </div>

    {{-- Info Card --}}
    <div class="mt-6 border border-graymedium p-6" style="background: var(--color-graylight);">
        <h3 class="text-xs tracking-widest uppercase text-charcoal-light font-semibold mb-4">Informasi Akun</h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center py-2 border-b border-graymedium">
                <span class="text-xs tracking-wide text-charcoal-light uppercase">Nama</span>
                <span class="text-sm font-medium text-charcoal">{{ $user->name }}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-graymedium">
                <span class="text-xs tracking-wide text-charcoal-light uppercase">Email</span>
                <span class="text-sm font-medium text-charcoal">{{ $user->email }}</span>
            </div>
            @if($user->phone)
            <div class="flex justify-between items-center py-2 border-b border-graymedium">
                <span class="text-xs tracking-wide text-charcoal-light uppercase">Telepon</span>
                <span class="text-sm font-medium text-charcoal">{{ $user->phone }}</span>
            </div>
            @endif
            <div class="flex justify-between items-center py-2">
                <span class="text-xs tracking-wide text-charcoal-light uppercase">Terdaftar</span>
                <span class="text-sm font-medium text-charcoal">{{ $user->created_at->format('d M Y') }}</span>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="btn-danger w-full justify-center text-xs py-3">Logout</button>
        </form>
    </div>
</div>
@endsection
