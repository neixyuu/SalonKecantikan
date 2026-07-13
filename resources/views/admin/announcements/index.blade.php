@extends('layouts.app')

@section('title', 'Kelola Pengumuman')
@section('page-title', 'Pengumuman')

@section('content')

<div class="mb-5 flex justify-end">
    <a href="/admin/announcements/create" class="btn-nude-filled text-xs py-2.5 px-6">+ Pengumuman Baru</a>
</div>

<div class="border border-graymedium" style="background:var(--color-white);">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Judul</th>
                    <th>Gambar</th>
                    <th>Video</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($announcements as $ann)
                    <tr>
                        <td class="text-charcoal-light">{{ $loop->iteration + ($announcements->currentPage() - 1) * $announcements->perPage() }}</td>
                        <td>
                            <p class="font-medium text-sm">{{ $ann->title }}</p>
                            <p class="text-xs text-charcoal-light mt-0.5">{{ Str::limit($ann->content, 60) }}</p>
                        </td>
                        <td>
                            @if($ann->image)
                                <img src="{{ $ann->image_url }}" alt="" style="width:60px; height:45px; object-fit:cover;">
                            @else
                                <span class="text-xs text-charcoal-light">—</span>
                            @endif
                        </td>
                        <td>
                            @if($ann->video_url)
                                <span class="badge-approved text-xs">Ada</span>
                            @else
                                <span class="text-xs text-charcoal-light">—</span>
                            @endif
                        </td>
                        <td class="text-xs text-charcoal-light">{{ $ann->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="flex gap-2">
                                <a href="/admin/announcements/{{ $ann->id }}/edit" class="btn-nude text-xs py-1.5 px-3">Edit</a>
                                <form id="del-ann-{{ $ann->id }}" method="POST" action="/admin/announcements/{{ $ann->id }}">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button" class="btn-nude btn-danger text-xs py-1.5 px-3"
                                        onclick="confirmDelete('del-ann-{{ $ann->id }}', '{{ $ann->title }}')">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-charcoal-light text-sm">Belum ada pengumuman</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $announcements->links() }}</div>

@endsection
