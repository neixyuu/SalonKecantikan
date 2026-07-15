@extends('layouts.app')

@section('title', 'Ganti Jadwal Reservasi')

@section('content')
    <div class="max-w-2xl mx-auto px-6 py-12">

        <div class="mb-8 fade-in">
            <a href="/reservations" class="text-xs text-charcoal-light hover:text-charcoal tracking-wide mb-4 inline-flex items-center gap-1">
                ← Kembali ke Reservasi
            </a>
            <span class="section-label block mt-3">Ubah Jadwal</span>
            <h1 class="font-serif text-4xl text-charcoal">Ganti Jadwal Reservasi</h1>
        </div>

        {{-- Info Reservasi Saat Ini --}}
        <div class="border border-graymedium p-5 mb-6 fade-in" style="background:var(--color-graylight);">
            <p class="text-xs tracking-widest uppercase text-charcoal-light mb-2">Jadwal Saat Ini</p>
            <h3 class="font-serif text-xl text-charcoal mb-1">{{ $reservation->treatment->name }}</h3>
            <p class="text-sm text-charcoal-light">
                {{ $reservation->schedule_date->format('d M Y') }} · {{ substr($reservation->schedule_time, 0, 5) }} WIB
            </p>
        </div>

        {{-- Form Ganti Jadwal --}}
        <form method="POST" action="/reservations/{{ $reservation->id }}/reschedule-request"
              class="space-y-5 border border-graymedium p-6 fade-in" style="background:var(--color-white);">
            @csrf

            <p class="text-xs text-charcoal-light tracking-wide leading-relaxed border-l-2 border-tan pl-3"
               style="border-color:var(--color-tan);">
                Permintaan ganti jadwal akan dikirim ke admin untuk dikonfirmasi terlebih dahulu sebelum berlaku.
            </p>

            <div>
                <label for="reschedule_date" class="form-label">Tanggal Baru <span class="text-red-500">*</span></label>
                <input type="date" id="reschedule_date" name="reschedule_date"
                       min="{{ date('Y-m-d') }}"
                       value="{{ old('reschedule_date') }}"
                       class="form-input {{ $errors->has('reschedule_date') ? 'error' : '' }}" required>
                @error('reschedule_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="reschedule_time" class="form-label">Jam Baru <span class="text-red-500">*</span></label>
                <select id="reschedule_time" name="reschedule_time"
                        class="form-input {{ $errors->has('reschedule_time') ? 'error' : '' }}" required>
                    <option value="">— Pilih Jam —</option>
                    @foreach(['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'] as $time)
                        <option value="{{ $time }}" {{ old('reschedule_time') === $time ? 'selected' : '' }}>
                            {{ $time }} WIB
                        </option>
                    @endforeach
                </select>
                @error('reschedule_time')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-nude-filled py-2.5 px-8 text-sm">
                    Ajukan Permintaan →
                </button>
                <a href="/reservations" class="btn-nude py-2.5 px-6 text-sm">Batal</a>
            </div>
        </form>

    </div>
@endsection
