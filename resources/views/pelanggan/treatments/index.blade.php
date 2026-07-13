@extends('layouts.app')

@section('title', 'Layanan Kami')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-12">

    <div class="text-center mb-12 fade-in">
        <span class="section-label">Koleksi Layanan</span>
        <h1 class="font-serif text-4xl text-charcoal">Get To Know Our Beauty Services</h1>
        <p class="text-sm text-charcoal-light mt-3 max-w-lg mx-auto tracking-wide">
            Pilih layanan perawatan yang paling cocok untuk Anda dan buat reservasi langsung dari sini.
        </p>
    </div>

    @if($treatments->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($treatments as $treatment)
                <div class="treatment-card fade-in">
                    {{-- Gambar --}}
                    <div class="overflow-hidden" style="background-color: var(--color-graylight);">
                        @if($treatment->image)
                            <img src="{{ $treatment->image_url }}" alt="{{ $treatment->name }}" style="width:100%; height:260px; object-fit:cover;" class="transition-transform duration-500 hover:scale-105">
                        @else
                            <div class="img-placeholder" style="height:260px;">
                                <span>{{ $treatment->name }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="p-6 text-center">
                        <h2 class="font-serif text-xl text-charcoal mb-1">{{ $treatment->name }}</h2>
                        <div class="flex justify-center gap-4 mb-3">
                            <span class="text-xs text-charcoal-light tracking-wide">{{ $treatment->duration }}</span>
                            <span class="text-xs font-semibold" style="color: var(--color-tan-dark);">{{ $treatment->formatted_price }}</span>
                        </div>
                        <p class="text-xs text-charcoal-light leading-relaxed mb-5">{{ Str::limit($treatment->description, 100) }}</p>
                        <a href="/reservations/create?treatment_id={{ $treatment->id }}"
                           class="btn-nude w-full justify-center text-xs py-2.5 block">
                            Book Now →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-20 border border-dashed border-graymedium" style="background:var(--color-graylight);">
            <p class="font-serif text-2xl text-charcoal/40 mb-2">Belum Ada Layanan</p>
            <p class="text-xs text-charcoal-light tracking-wide">Layanan akan segera ditambahkan oleh admin.</p>
        </div>
    @endif

</div>
@endsection
