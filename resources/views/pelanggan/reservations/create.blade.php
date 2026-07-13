@extends('layouts.app')

@section('title', 'Buat Reservasi')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-12">

    <div class="mb-8 fade-in">
        <span class="section-label">Reservasi Baru</span>
        <h1 class="font-serif text-4xl text-charcoal">Pilih Jadwal Perawatan</h1>
        <p class="text-sm text-charcoal-light mt-1 tracking-wide">Pilih treatment dan waktu yang nyaman untuk Anda</p>
    </div>

    @if($errors->any())
        <div class="alert alert-error mb-6">
            <ul class="list-disc list-inside space-y-1 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/reservations" class="space-y-6 fade-in">
        @csrf

        {{-- Pilih Treatment --}}
        <div>
            <label class="form-label">Treatment <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-2">
                @foreach($treatments as $treatment)
                    <label class="cursor-pointer">
                        <input type="radio" name="treatment_id" value="{{ $treatment->id }}"
                               class="sr-only peer"
                               {{ (old('treatment_id') == $treatment->id || ($selectedTreatment && $selectedTreatment->id == $treatment->id)) ? 'checked' : '' }}>
                        <div class="border border-graymedium p-4 peer-checked:border-tan peer-checked:bg-blush/20 transition-all"
                             style="background:var(--color-white);">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-sm text-charcoal">{{ $treatment->name }}</p>
                                    <p class="text-xs text-charcoal-light mt-0.5">{{ $treatment->duration }}</p>
                                </div>
                                <span class="text-xs font-semibold" style="color:var(--color-tan-dark);">{{ $treatment->formatted_price }}</span>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
            @error('treatment_id')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Tanggal --}}
            <div>
                <label for="schedule_date" class="form-label">Tanggal <span class="text-red-500">*</span></label>
                <input
                    type="date"
                    id="schedule_date"
                    name="schedule_date"
                    value="{{ old('schedule_date') }}"
                    min="{{ date('Y-m-d') }}"
                    class="form-input {{ $errors->has('schedule_date') ? 'error' : '' }}"
                    required
                >
                @error('schedule_date')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jam --}}
            <div>
                <label for="schedule_time" class="form-label">Jam <span class="text-red-500">*</span></label>
                <select id="schedule_time" name="schedule_time" class="form-input {{ $errors->has('schedule_time') ? 'error' : '' }}" required>
                    <option value="">-- Pilih Jam --</option>
                    @php
                        $times = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
                                  '13:00', '13:30', '14:00', '14:30', '15:00', '15:30',
                                  '16:00', '16:30', '17:00', '17:30', '18:00', '18:30',
                                  '19:00', '19:30'];
                    @endphp
                    @foreach($times as $time)
                        <option value="{{ $time }}" {{ old('schedule_time') == $time ? 'selected' : '' }}>
                            {{ $time }} WIB
                        </option>
                    @endforeach
                </select>
                @error('schedule_time')
                    <p class="form-error">{{ $message }}</p>
                @enderror
                <p class="text-xs text-charcoal-light mt-1">Jam operasional: 09.00 – 20.00 WIB</p>
            </div>
        </div>

        {{-- Catatan --}}
        <div>
            <label for="notes" class="form-label">Catatan <span class="text-charcoal/30">(opsional)</span></label>
            <textarea
                id="notes"
                name="notes"
                rows="3"
                class="form-input"
                placeholder="Kondisi kulit, permintaan khusus, alergi, dll."
            >{{ old('notes') }}</textarea>
        </div>

        {{-- Info Pending --}}
        <div class="p-4 border border-blush" style="background-color: rgba(243,217,212,0.2);">
            <p class="text-xs text-charcoal-light leading-relaxed">
                <span class="font-semibold" style="color:var(--color-tan-dark);">Catatan:</span>
                Setelah mengirim reservasi, admin akan memeriksa dan menyetujui jadwal Anda dalam 1×24 jam. Anda bisa upload bukti pembayaran setelah reservasi disetujui.
            </p>
        </div>

        <div class="flex gap-3">
            <a href="/reservations" class="btn-nude text-xs py-3 px-6">← Batal</a>
            <button type="submit" class="btn-nude-filled text-xs py-3 px-8">Kirim Reservasi →</button>
        </div>
    </form>

</div>
@endsection
