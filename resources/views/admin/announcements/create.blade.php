@extends('layouts.app')

@section('title', 'Buat Pengumuman')
@section('page-title', 'Buat Pengumuman')

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

    <form method="POST" action="/admin/announcements" enctype="multipart/form-data" class="space-y-5 border border-graymedium p-6" style="background:var(--color-white);">
        @csrf

        <div>
            <label for="title" class="form-label">Judul Pengumuman <span class="text-red-500">*</span></label>
            <input type="text" id="title" name="title" value="{{ old('title') }}"
                   class="form-input {{ $errors->has('title') ? 'error' : '' }}"
                   placeholder="Judul pengumuman" required>
            @error('title')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="content" class="form-label">Isi Pengumuman <span class="text-red-500">*</span></label>
            <textarea id="content" name="content" rows="6"
                      class="form-input {{ $errors->has('content') ? 'error' : '' }}"
                      placeholder="Tulis isi pengumuman di sini..." required>{{ old('content') }}</textarea>
            @error('content')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="image" class="form-label">Gambar <span class="text-charcoal/30">(opsional)</span></label>
            <input type="file" id="image" name="image" accept="image/*"
                   class="form-input {{ $errors->has('image') ? 'error' : '' }}"
                   onchange="previewImage(this)">
            @error('image')<p class="form-error">{{ $message }}</p>@enderror
            <div id="preview-container" class="mt-2 hidden">
                <img id="preview-img" src="" alt="Preview" style="max-height:200px; object-fit:cover; border:1px solid var(--color-graymedium);">
            </div>
        </div>

        <div>
            <label for="video_url" class="form-label">URL Video YouTube <span class="text-charcoal/30">(opsional)</span></label>
            <input type="url" id="video_url" name="video_url" value="{{ old('video_url') }}"
                   class="form-input {{ $errors->has('video_url') ? 'error' : '' }}"
                   placeholder="https://www.youtube.com/watch?v=...">
            @error('video_url')<p class="form-error">{{ $message }}</p>@enderror
            <p class="text-xs text-charcoal-light mt-1">Paste URL YouTube lengkap, akan dikonversi otomatis ke embed.</p>
        </div>

        <div class="flex gap-3 pt-2">
            <a href="/admin/announcements" class="btn-nude text-xs py-3 px-6">← Batal</a>
            <button type="submit" class="btn-nude-filled text-xs py-3 px-8">Simpan Pengumuman →</button>
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
