@extends('layouts.app')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
<div class="max-w-2xl mx-auto px-6 py-12">

    <div class="mb-8 fade-in">
        <span class="section-label">Pembayaran</span>
        <h1 class="font-serif text-4xl text-charcoal">Upload Bukti Bayar</h1>
        <p class="text-sm text-charcoal-light mt-1 tracking-wide">Reservasi #{{ $reservation->id }} telah disetujui</p>
    </div>

    {{-- Reservation Summary --}}
    <div class="border border-graymedium p-5 mb-6 fade-in" style="background:var(--color-white);">
        <p class="text-xs tracking-widest uppercase text-charcoal-light mb-3">Ringkasan Reservasi</p>
        <div class="space-y-2">
            <div class="flex justify-between items-center">
                <span class="text-xs text-charcoal-light uppercase tracking-wide">Treatment</span>
                <span class="font-serif text-base text-charcoal">{{ $reservation->treatment->name }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-xs text-charcoal-light uppercase tracking-wide">Tanggal</span>
                <span class="text-sm text-charcoal">{{ $reservation->schedule_date->format('d M Y') }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-xs text-charcoal-light uppercase tracking-wide">Jam</span>
                <span class="text-sm text-charcoal">{{ substr($reservation->schedule_time, 0, 5) }} WIB</span>
            </div>
            <div class="flex justify-between items-center border-t border-graymedium pt-2 mt-2">
                <span class="text-xs font-semibold tracking-widest uppercase text-charcoal-light">Total</span>
                <span class="font-serif text-xl text-charcoal font-semibold">{{ $reservation->treatment->formatted_price }}</span>
            </div>
        </div>
    </div>

    {{-- Upload Form --}}
    @if($errors->any())
        <div class="alert alert-error mb-6">
            <ul class="list-disc list-inside space-y-1 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/payments" enctype="multipart/form-data" class="space-y-5 fade-in">
        @csrf
        <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">

        <div>
            <label for="amount" class="form-label">Nominal Transfer (Rp) <span class="text-red-500">*</span></label>
            <input
                type="number"
                id="amount"
                name="amount"
                value="{{ old('amount', $reservation->treatment->price) }}"
                class="form-input {{ $errors->has('amount') ? 'error' : '' }}"
                min="1"
                placeholder="250000"
                required
            >
            @error('amount')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="proof_image" class="form-label">Bukti Transfer <span class="text-red-500">*</span></label>
            <div class="border-2 border-dashed border-graymedium p-8 text-center cursor-pointer hover:border-tan transition-colors"
                 id="drop-zone"
                 onclick="document.getElementById('proof_image').click()">
                <svg class="w-8 h-8 mx-auto mb-3 text-charcoal-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <p class="text-sm text-charcoal-light">Klik untuk upload gambar bukti transfer</p>
                <p class="text-xs text-charcoal/40 mt-1">JPG, JPEG, PNG · Maks. 2MB</p>
                <p id="file-name" class="text-xs text-tan-dark mt-2 font-medium hidden"></p>
            </div>
            <input type="file" id="proof_image" name="proof_image" accept="image/*"
                   class="hidden" onchange="showFileName(this)"
                   required>
            @error('proof_image')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="p-4 border border-blush" style="background-color: rgba(243,217,212,0.2);">
            <p class="text-xs text-charcoal-light leading-relaxed">
                <span class="font-semibold" style="color:var(--color-tan-dark);">Rekening Pembayaran:</span><br>
                BCA · 1234567890 · Atas Nama: AETH Clinic<br>
                <span class="mt-1 block">Upload screenshot/foto bukti transfer setelah melakukan pembayaran.</span>
            </p>
        </div>

        <div class="flex gap-3">
            <a href="/reservations" class="btn-nude text-xs py-3 px-6">← Batal</a>
            <button type="submit" class="btn-nude-filled text-xs py-3 px-8">Upload Bukti →</button>
        </div>
    </form>

</div>

<script>
function showFileName(input) {
    const label = document.getElementById('file-name');
    if (input.files && input.files[0]) {
        label.textContent = '✓ ' + input.files[0].name;
        label.classList.remove('hidden');
    }
}
</script>
@endsection
