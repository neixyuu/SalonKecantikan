@extends('layouts.app')

@section('title', 'Nude Beauty Salon — Salon Kecantikan Premium')
@section('meta_description', 'Salon kecantikan premium dengan layanan facial, massage, hair treatment, dan lebih banyak lagi. Reservasi mudah online.')

@section('content')

{{-- ── Hero Section ── --}}
<section class="relative overflow-hidden" style="min-height: 520px; background: linear-gradient(135deg, var(--color-cream) 0%, var(--color-cream-dark) 100%);">
    {{-- Background decorative --}}
    <div class="absolute inset-0 flex">
        <div class="w-full md:w-1/2"></div>
        <div class="hidden md:block w-1/2 relative" style="background: linear-gradient(135deg, var(--color-blush) 0%, var(--color-tan) 100%); opacity: 0.4;"></div>
    </div>

    <div class="relative max-w-6xl mx-auto px-6 py-20 md:py-32 flex items-center">
        <div class="max-w-xl fade-in">
            <span class="section-label">Discover Your Beauty</span>
            <h1 class="font-serif text-5xl md:text-6xl text-charcoal leading-tight mb-6">
                Rawat Kecantikan<br>
                <em class="text-tan-dark">Alami</em> Anda
            </h1>
            <p class="text-sm text-charcoal-light leading-relaxed mb-10 max-w-sm">
                Salon kecantikan premium dengan sentuhan personal. Dari facial mewah hingga perawatan tubuh — kami hadir untuk merawat kecantikan terbaik Anda.
            </p>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="/register" class="btn-nude-filled inline-flex items-center gap-2 py-3 px-8">
                    Reservasi Sekarang →
                </a>
                <a href="#layanan" class="btn-nude inline-flex items-center gap-2 py-3 px-8">
                    Lihat Layanan
                </a>
            </div>
        </div>
    </div>

    {{-- Decorative circles --}}
    <div class="absolute top-10 right-10 w-64 h-64 rounded-full opacity-10" style="background: radial-gradient(circle, var(--color-tan) 0%, transparent 70%);"></div>
    <div class="absolute bottom-0 right-1/4 w-40 h-40 rounded-full opacity-15" style="background: radial-gradient(circle, var(--color-blush) 0%, transparent 70%);"></div>
</section>

{{-- ── Promo Banners ── --}}
<section class="max-w-6xl mx-auto px-6 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="relative overflow-hidden p-8 flex flex-col justify-center" style="min-height: 220px; background: linear-gradient(135deg, var(--color-blush) 0%, var(--color-blush-dark) 100%);">
            <span class="section-label text-charcoal/60">Perawatan Wajah</span>
            <h3 class="font-serif text-2xl text-charcoal mb-3">Facial Treatment<br>Terbaik Kami</h3>
            <a href="{{ auth()->check() ? '/treatments' : '/register' }}" class="btn-nude inline-flex self-start mt-2 text-xs">Reservasi →</a>
        </div>
        <div class="relative overflow-hidden p-8 flex flex-col justify-center" style="min-height: 220px; background: linear-gradient(135deg, var(--color-graylight) 0%, var(--color-graymedium) 100%);">
            <span class="section-label">Relaksasi Tubuh</span>
            <h3 class="font-serif text-2xl text-charcoal mb-3">Massage &<br>Body Care</h3>
            <a href="{{ auth()->check() ? '/treatments' : '/register' }}" class="btn-nude inline-flex self-start mt-2 text-xs">Reservasi →</a>
        </div>
    </div>
</section>

{{-- ── Services Section ── --}}
<section id="layanan" class="max-w-6xl mx-auto px-6 py-16">
    <div class="text-center mb-12">
        <span class="section-label">Koleksi Layanan</span>
        <h2 class="font-serif text-4xl text-charcoal">Get To Know Our Beauty Services</h2>
    </div>

    @if($treatments->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($treatments as $treatment)
                <div class="treatment-card fade-in">
                    {{-- Image --}}
                    <div class="overflow-hidden" style="background-color: var(--color-graylight);">
                        @if($treatment->image)
                            <img src="{{ $treatment->image_url }}" alt="{{ $treatment->name }}" class="w-full" style="height:260px; object-fit:cover;">
                        @else
                            <div class="img-placeholder" style="height:260px;">
                                <span>{{ $treatment->name }}</span>
                            </div>
                        @endif
                    </div>
                    {{-- Info --}}
                    <div class="p-6 text-center">
                        <h3 class="font-serif text-xl text-charcoal mb-1">{{ $treatment->name }}</h3>
                        <div class="flex justify-center gap-4 mb-4">
                            <span class="text-xs text-charcoal-light tracking-wide">{{ $treatment->duration }}</span>
                            <span class="text-xs font-semibold" style="color: var(--color-tan-dark);">{{ $treatment->formatted_price }}</span>
                        </div>
                        <a href="{{ auth()->check() ? '/reservations/create?treatment_id=' . $treatment->id : '/register' }}"
                           class="btn-nude w-full justify-center text-xs py-2.5">
                            Book Now →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-10">
            <a href="{{ auth()->check() ? '/treatments' : '/register' }}" class="btn-nude text-sm py-3 px-8">
                Lihat Semua Layanan →
            </a>
        </div>
    @else
        <div class="text-center py-16">
            <p class="text-charcoal-light text-sm tracking-wide">Layanan akan segera tersedia</p>
        </div>
    @endif
</section>

{{-- ── Gallery Strip ── --}}
<section class="py-8" id="tentang">
    <div class="max-w-6xl mx-auto px-6 mb-6">
        <p class="text-center text-xs tracking-widest uppercase text-charcoal-light">♦ Join the Nude Beauty Community</p>
    </div>
    <div class="flex gap-1 overflow-hidden px-6 max-w-6xl mx-auto">
        @php
        $galleryColors = ['#F3D9D4', '#E8C4BC', '#D9A98E', '#F5F0EB', '#F3D9D4', '#E8C4BC'];
        $galleryLabels = ['Facial', 'Massage', 'Hair Care', 'Nail Art', 'Body Spa', 'Beauty'];
        @endphp
        @for($i = 0; $i < 6; $i++)
            <div class="flex-1 min-w-0" style="height: 120px; background-color: {{ $galleryColors[$i] }}; display:flex; align-items:center; justify-content:center;">
                <span class="text-xs tracking-wider uppercase text-charcoal/40">{{ $galleryLabels[$i] }}</span>
            </div>
        @endfor
    </div>
</section>

{{-- ── Announcements ── --}}
@if($announcements->isNotEmpty())
<section class="max-w-6xl mx-auto px-6 py-16 border-t border-graymedium">
    <div class="mb-8">
        <span class="section-label">Info Terkini</span>
        <h2 class="font-serif text-3xl text-charcoal">Pengumuman</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($announcements as $announcement)
            <div class="p-6 border border-graymedium" style="background-color: var(--color-white);">
                <p class="text-xs text-charcoal-light tracking-widest uppercase mb-2">{{ $announcement->created_at->format('d M Y') }}</p>
                <h3 class="font-serif text-xl text-charcoal mb-2">{{ $announcement->title }}</h3>
                <p class="text-sm text-charcoal-light leading-relaxed line-clamp-3">{{ $announcement->content }}</p>
            </div>
        @endforeach
    </div>
    @auth
        <div class="mt-6">
            <a href="/announcements" class="btn-nude text-xs">Lihat Semua Pengumuman →</a>
        </div>
    @endauth
</section>
@endif

{{-- ── CTA Section ── --}}
<section class="py-20" style="background: linear-gradient(135deg, var(--color-charcoal) 0%, #404040 100%);">
    <div class="max-w-2xl mx-auto px-6 text-center">
        <span class="section-label text-tan">Mulai Sekarang</span>
        <h2 class="font-serif text-4xl text-cream mb-4">Siap Tampil<br>Lebih Cantik?</h2>
        <p class="text-sm text-cream/60 mb-8 tracking-wide">Daftarkan akun Anda dan mulai reservasi layanan salon premium kami.</p>
        <a href="/register" class="btn-nude text-cream border-cream hover:bg-cream hover:text-charcoal py-3 px-10">
            Daftar Gratis →
        </a>
    </div>
</section>

@endsection
