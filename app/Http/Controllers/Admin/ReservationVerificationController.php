<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationVerificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['user', 'treatment', 'payment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter untuk menampilkan yang ada permintaan pending
        if ($request->filled('request_type')) {
            if ($request->request_type === 'cancel') {
                $query->whereNotNull('cancel_requested_at')->where('status', 'approved');
            } elseif ($request->request_type === 'reschedule') {
                $query->whereNotNull('reschedule_requested_date')->where('status', 'approved');
            }
        }

        $reservations = $query->latest()->paginate(10);

        // Hitung pending reschedule request untuk notifikasi
        $pendingCancelCount     = 0; // fitur tidak digunakan
        $pendingRescheduleCount = Reservation::whereNotNull('reschedule_requested_date')->where('status', 'approved')->count();

        return view('admin.reservations.index', compact('reservations', 'pendingCancelCount', 'pendingRescheduleCount'));
    }

    /**
     * Approve/Reject reservasi awal (dari status pending).
     */
    public function verify(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'action'           => 'required|in:approve,reject',
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Reservasi ini sudah pernah diproses sebelumnya.');
        }

        $updateData = [
            'status' => $validated['action'] === 'approve' ? 'approved' : 'rejected',
        ];

        if ($validated['action'] === 'reject') {
            $updateData['rejection_reason'] = $validated['rejection_reason'] ?? null;
        }

        $reservation->update($updateData);

        $message = $validated['action'] === 'approve'
            ? "Reservasi #{$reservation->id} berhasil disetujui."
            : "Reservasi #{$reservation->id} berhasil ditolak.";

        return back()->with('success', $message);
    }

    /**
     * Admin mengkonfirmasi/menolak permintaan PEMBATALAN dari pelanggan.
     */
    public function handleCancelRequest(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        if (!$reservation->hasPendingCancelRequest()) {
            return back()->with('error', 'Tidak ada permintaan pembatalan aktif untuk reservasi ini.');
        }

        if ($validated['action'] === 'approve') {
            $reservation->update([
                'status'             => 'cancelled',
                'cancel_requested_at'=> null,
                'cancellation_reason'=> 'Dibatalkan atas permintaan pelanggan.',
            ]);
            return back()->with('success', "Pembatalan reservasi #{$reservation->id} disetujui.");
        } else {
            // Tolak permintaan pembatalan → hapus request, reservasi tetap approved
            $reservation->update(['cancel_requested_at' => null]);
            return back()->with('success', "Permintaan pembatalan reservasi #{$reservation->id} ditolak. Reservasi tetap berlanjut.");
        }
    }

    /**
     * Admin mengkonfirmasi/menolak permintaan GANTI JADWAL dari pelanggan.
     */
    public function handleRescheduleRequest(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        if (!$reservation->hasPendingRescheduleRequest()) {
            return back()->with('error', 'Tidak ada permintaan ganti jadwal aktif untuk reservasi ini.');
        }

        if ($validated['action'] === 'approve') {
            $reservation->update([
                'schedule_date'             => $reservation->reschedule_requested_date,
                'schedule_time'             => $reservation->reschedule_requested_time,
                'reschedule_requested_date' => null,
                'reschedule_requested_time' => null,
            ]);
            return back()->with('success', "Ganti jadwal reservasi #{$reservation->id} disetujui. Jadwal telah diperbarui.");
        } else {
            // Tolak → hapus request
            $reservation->update([
                'reschedule_requested_date' => null,
                'reschedule_requested_time' => null,
            ]);
            return back()->with('success', "Permintaan ganti jadwal reservasi #{$reservation->id} ditolak.");
        }
    }

    /**
     * Admin membatalkan reservasi yang sudah approved dengan alasan.
     * (Digunakan ketika pembayaran bermasalah atau alasan lainnya)
     */
    public function cancelByAdmin(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:1000',
        ], [
            'cancellation_reason.required' => 'Alasan pembatalan wajib diisi.',
        ]);

        if (!in_array($reservation->status, ['approved'])) {
            return back()->with('error', 'Hanya reservasi berstatus Disetujui yang bisa dibatalkan oleh admin.');
        }

        $reservation->update([
            'status'              => 'cancelled',
            'cancellation_reason' => $validated['cancellation_reason'],
            'cancel_requested_at' => null,
        ]);

        return back()->with('success', "Reservasi #{$reservation->id} telah dibatalkan.");
    }
}
