@extends('layouts.app')

@section('title', 'Kelola Treatment')
@section('page-title', 'Treatment')

@section('content')

<div class="mb-5 flex justify-end">
    <a href="/admin/treatments/create" class="btn-nude-filled text-xs py-2.5 px-6">+ Treatment Baru</a>
</div>

<div class="border border-graymedium" style="background:var(--color-white);">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Gambar</th>
                    <th>Nama</th>
                    <th>Durasi</th>
                    <th>Harga</th>
                    <th>Reservasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($treatments as $treatment)
                    <tr>
                        <td class="text-charcoal-light">{{ $loop->iteration + ($treatments->currentPage() - 1) * $treatments->perPage() }}</td>
                        <td>
                            @if($treatment->image)
                                <img src="{{ $treatment->image_url }}" alt="" style="width:60px; height:45px; object-fit:cover;">
                            @else
                                <div style="width:60px; height:45px; background:var(--color-blush); display:flex; align-items:center; justify-content:center;">
                                    <span class="text-xs text-charcoal-light">No img</span>
                                </div>
                            @endif
                        </td>
                        <td>
                            <p class="font-medium text-sm">{{ $treatment->name }}</p>
                            <p class="text-xs text-charcoal-light mt-0.5">{{ Str::limit($treatment->description, 50) }}</p>
                        </td>
                        <td class="text-sm">{{ $treatment->duration }}</td>
                        <td class="font-semibold text-sm" style="color:var(--color-tan-dark);">{{ $treatment->formatted_price }}</td>
                        <td class="text-sm text-charcoal-light">{{ $treatment->reservations->count() ?? 0 }}</td>
                        <td>
                            <div class="flex gap-2">
                                <a href="/admin/treatments/{{ $treatment->id }}/edit" class="btn-nude text-xs py-1.5 px-3">Edit</a>
                                <form id="del-tr-{{ $treatment->id }}" method="POST" action="/admin/treatments/{{ $treatment->id }}">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button" class="btn-nude btn-danger text-xs py-1.5 px-3"
                                        onclick="confirmDelete('del-tr-{{ $treatment->id }}', '{{ $treatment->name }}')">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-charcoal-light text-sm">Belum ada treatment</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $treatments->links() }}</div>

@endsection
