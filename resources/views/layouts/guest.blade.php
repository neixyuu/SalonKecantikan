<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(session('success'))
        <meta name="flash-success" content="{{ session('success') }}">
    @endif
    @if(session('error'))
        <meta name="flash-error" content="{{ session('error') }}">
    @endif

    <title>@yield('title', 'Masuk') — Nude Beauty Salon</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<body style="background-color: var(--color-cream);">

    <div class="min-h-screen flex">
        {{-- Left decorative panel --}}
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
            <div class="absolute inset-0" style="background: linear-gradient(135deg, var(--color-blush) 0%, var(--color-tan) 60%, var(--color-cream-dark) 100%);"></div>
            <div class="relative z-10 flex flex-col justify-between p-12 w-full">
                <a href="/" class="block">
                    <span class="font-serif text-4xl italic text-charcoal">Nude</span>
                    <span class="block text-xs tracking-widest uppercase text-charcoal/50 mt-1">Beauty Salon</span>
                </a>
                <div>
                    <blockquote class="font-serif text-2xl italic text-charcoal/80 leading-relaxed mb-6">
                        "Kecantikan sejati berasal dari perawatan yang tulus."
                    </blockquote>
                    <div class="flex gap-2">
                        <div class="w-8 h-0.5 bg-charcoal/40"></div>
                        <div class="w-4 h-0.5 bg-charcoal/20"></div>
                    </div>
                </div>
                <p class="text-xs text-charcoal/40 tracking-wide">© {{ date('Y') }} Nude Beauty Salon</p>
            </div>
        </div>

        {{-- Right: Form --}}
        <div class="flex-1 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                {{-- Mobile Logo --}}
                <div class="lg:hidden mb-8 text-center">
                    <a href="/" class="inline-block">
                        <span class="font-serif text-3xl italic text-charcoal">Nude</span>
                        <span class="block text-xs tracking-widest uppercase text-tan-dark mt-0.5">Beauty Salon</span>
                    </a>
                </div>

                @yield('content')
            </div>
        </div>
    </div>

</body>
</html>
