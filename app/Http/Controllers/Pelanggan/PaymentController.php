<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function create(Reservation $reservation)
    {
        // Pastikan reservasi milik user yang login
        if ($reservation->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        // Hanya reservasi yang approved yang boleh upload bukti
        if ($reservation->status !== 'approved') {
            return redirect('/reservations')
                ->with('error', 'Reservasi harus disetujui terlebih dahulu sebelum upload bukti bayar.');
        }

        // Cek apakah sudah ada payment
        if ($reservation->payment) {
            return redirect('/reservations')
                ->with('error', 'Bukti pembayaran sudah pernah diupload.');
        }

        $reservation->load('treatment');
        return view('pelanggan.payments.create', compact('reservation'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'proof_image'    => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'amount'         => 'required|numeric|min:1',
        ], [
            'proof_image.required' => 'Bukti pembayaran wajib diupload.',
            'proof_image.image'    => 'File harus berupa gambar.',
            'proof_image.mimes'    => 'Format gambar: jpg, jpeg, atau png.',
            'proof_image.max'      => 'Ukuran gambar maksimal 2MB.',
            'amount.required'      => 'Nominal pembayaran wajib diisi.',
            'amount.numeric'       => 'Nominal harus berupa angka.',
        ]);

        $reservation = Reservation::findOrFail($validated['reservation_id']);

        if ($reservation->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $path = $request->file('proof_image')->store('payments', 'public');

        Payment::create([
            'reservation_id' => $reservation->id,
            'proof_image'    => $path,
            'amount'         => $validated['amount'],
            'status'         => 'pending',
        ]);

        return redirect('/reservations')
            ->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.');
    }
}
