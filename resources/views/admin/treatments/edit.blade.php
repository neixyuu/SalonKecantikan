@extends('layouts.app')

@section('title', 'Edit Treatment')
@section('page-title', 'Edit Treatment')

@section('content')

<div class="max-w-2xl">

    @if($errors->any())
        <div class="alert alert-error mb-6">
            <ul class="list-disc list-inside space-y-1 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/admin/treatments/{{ $treatment->id }}" enctype="multipart/form-data" class="space-y-5 border border-graymedium p-6" style="background:var(--color-white);">
        @csrf @method('PUT')

        <div>
            <label for="name" class="form-label">Nama Treatment <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $treatment->name) }}"
                   class="form-input {{ $errors->has('name') ? 'error' : '' }}" required>
            @error('name')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="price" class="form-label">Harga (Rp) <span class="text-red-500">*</span></label>
                <input type="number" id="price" name="price" value="{{ old('price', $treatment->price) }}"
                       class="form-input" min="0" required>
                @error('price')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="duration" class="form-label">Durasi <span class="text-red-500">*</span></label>
                <input type="text" id="duration" name="duration" value="{{ old('duration', $treatment->duration) }}"
                       class="form-input" required>
                @error('duration')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="description" class="form-label">Deskripsi <span class="text-red-500">*</span></label>
            <textarea id="description" name="description" rows="4"
                      class="form-input" required>{{ old('description', $treatment->description) }}</textarea>
            @error('description')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="image" class="form-label">Ganti Gambar <span class="text-charcoal/30">(biarkan kosong jika tidak diubah)</span></label>
            @if($treatment->image)
                <div class="mb-2">
                    <p class="text-xs text-charcoal-light mb-1">Gambar saat ini:</p>
                    <img src="{{ $treatment->image_url }}" alt="Current" style="max-height:160px; object-fit:cover; border:1px solid var(--color-graymedium);">
                </div>
            @endif
            <input type="file" id="image" name="image" accept="image/*" class="form-input" onchange="previewImage(this)">
            <div id="preview-container" class="mt-2 hidden">
                <img id="preview-img" src="" alt="Preview" style="max-height:160px; object-fit:cover;">
            </div>
            @error('image')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-3 pt-2">
            <a href="/admin/treatments" class="btn-nude text-xs py-3 px-6">← Batal</a>
            <button type="submit" class="btn-nude-filled text-xs py-3 px-8">Update Treatment →</button>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('preview-container').classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

@endsection
