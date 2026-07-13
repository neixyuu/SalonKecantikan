@extends('layouts.app')

@section('title', 'Pengumuman')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">

    <div class="text-center mb-12 fade-in">
        <span class="section-label">Info Terbaru</span>
        <h1 class="font-serif text-4xl text-charcoal">Pengumuman</h1>
        <p class="text-sm text-charcoal-light mt-2 tracking-wide">Informasi terkini dari Nude Beauty Salon</p>
    </div>

    @if($announcements->isNotEmpty())
        <div class="space-y-6">
            @foreach($announcements as $announcement)
                <article class="border border-graymedium fade-in" style="background:var(--color-white);">
                    {{-- Image --}}
                    @if($announcement->image_url)
                        <div class="overflow-hidden" style="max-height:320px;">
                            <img src="{{ $announcement->image_url }}" alt="{{ $announcement->title }}" style="width:100%; height:320px; object-fit:cover;">
                        </div>
                    @endif

                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-4">
                            <p class="text-xs tracking-widest uppercase text-charcoal-light">{{ $announcement->created_at->format('d F Y') }}</p>
                            @if($announcement->admin)
                                <span class="text-charcoal/20">·</span>
                                <p class="text-xs text-charcoal-light">oleh {{ $announcement->admin->name }}</p>
                            @endif
                        </div>

                        <h2 class="font-serif text-2xl text-charcoal mb-4">{{ $announcement->title }}</h2>
                        <p class="text-sm text-charcoal-light leading-relaxed whitespace-pre-wrap">{{ $announcement->content }}</p>

                        {{-- Video Embed --}}
                        @if($announcement->embed_video_url)
                            <div class="mt-6" style="aspect-ratio: 16/9;">
                                <iframe
                                    src="{{ $announcement->embed_video_url }}"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                    style="width:100%; height:100%;"
                                ></iframe>
                            </div>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <div class="text-center py-20 border border-dashed border-graymedium" style="background:var(--color-graylight);">
            <p class="font-serif text-2xl text-charcoal/40 mb-2">Belum Ada Pengumuman</p>
            <p class="text-xs text-charcoal-light tracking-wide">Pengumuman dari salon akan muncul di sini</p>
        </div>
    @endif

</div>
@endsection
