@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
    <div class="fade-in">
        <div class="mb-8">
            <h1 class="font-serif text-3xl text-charcoal">Selamat Datang</h1>
            <p class="text-sm text-charcoal-light mt-1 tracking-wide">Masuk ke akun Anda untuk melanjutkan</p>
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

        <form method="POST" action="/login" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="form-input {{ $errors->has('email') ? 'error' : '' }}" placeholder="email@contoh.com"
                    autocomplete="email" required>
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="form-label">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password"
                        class="form-input pr-10 {{ $errors->has('password') ? 'error' : '' }}" placeholder="••••••••"
                        autocomplete="current-password" required>
                    <button type="button" onclick="togglePassword('password', this)"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-charcoal-light hover:text-charcoal">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="remember" name="remember" class="mr-2">
                <label for="remember" class="text-xs text-charcoal-light tracking-wide cursor-pointer">Ingat saya</label>
            </div>

            <button type="submit" class="btn-nude-filled w-full justify-center py-3 text-sm mt-2">
                Masuk →
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-xs text-charcoal-light tracking-wide">
                Belum punya akun?
                <a href="/register" class="text-tan-dark font-medium hover:underline">Daftar sekarang</a>
            </p>
        </div>

        <!-- {{-- Demo credentials --}}
                <div class="mt-8 p-4 border border-graymedium" style="background-color: var(--color-graylight);">
                    <p class="text-xs tracking-wider uppercase text-charcoal-light font-semibold mb-2">Demo Admin</p>
                    <p class="text-xs text-charcoal-light">Email: <code class="bg-white px-1">admin@salon.com</code></p>
                    <p class="text-xs text-charcoal-light mt-1">Password: <code class="bg-white px-1">password123</code></p>
                </div> -->
    </div>

    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
@endsection