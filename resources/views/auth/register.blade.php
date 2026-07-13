@extends('layouts.guest')

@section('title', 'Daftar Akun')

@section('content')
<div class="fade-in">
    <div class="mb-8">
        <h1 class="font-serif text-3xl text-charcoal">Buat Akun Baru</h1>
        <p class="text-sm text-charcoal-light mt-1 tracking-wide">Daftar dan nikmati layanan salon eksklusif kami</p>
    </div>

    @if($errors->any())
        <div class="alert alert-error mb-6">
            <ul class="list-disc list-inside space-y-1 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/register" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="form-label">Nama Lengkap</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                placeholder="Nama Anda"
                autocomplete="name"
                required
            >
            @error('name')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="form-label">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                placeholder="email@contoh.com"
                autocomplete="email"
                required
            >
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="phone" class="form-label">Nomor Telepon <span class="text-charcoal/30">(opsional)</span></label>
            <input
                type="tel"
                id="phone"
                name="phone"
                value="{{ old('phone') }}"
                class="form-input {{ $errors->has('phone') ? 'error' : '' }}"
                placeholder="08xxxxxxxxxx"
            >
            @error('phone')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="form-label">Password</label>
            <div class="relative">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input pr-10 {{ $errors->has('password') ? 'error' : '' }}"
                    placeholder="Min. 8 karakter"
                    autocomplete="new-password"
                    required
                >
                <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-charcoal-light hover:text-charcoal">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
            </div>
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <div class="relative">
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-input pr-10"
                    placeholder="Ulangi password"
                    autocomplete="new-password"
                    required
                >
                <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-charcoal-light hover:text-charcoal">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
            </div>
        </div>

        {{-- Info pendaftaran --}}
        <div class="p-4 border border-blush" style="background-color: rgba(243,217,212,0.2);">
            <p class="text-xs text-charcoal-light leading-relaxed">
                <span class="font-semibold text-tan-dark">Catatan:</span> Setelah mendaftar, akun Anda akan dalam status <strong>menunggu verifikasi</strong> hingga disetujui oleh admin kami.
            </p>
        </div>

        <button type="submit" class="btn-nude-filled w-full justify-center py-3 text-sm mt-2">
            Daftar Sekarang →
        </button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-xs text-charcoal-light tracking-wide">
            Sudah punya akun?
            <a href="/login" class="text-tan-dark font-medium hover:underline">Masuk di sini</a>
        </p>
    </div>
</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endsection
