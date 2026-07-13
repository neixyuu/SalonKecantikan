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

        $reservations = $query->latest()->paginate(10);
        return view('admin.reservations.index', compact('reservations'));
    }

    public function verify(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        $reservation->update([
            'status' => $validated['action'] === 'approve' ? 'approved' : 'rejected',
        ]);

        $message = $validated['action'] === 'approve'
            ? "Reservasi #{$reservation->id} berhasil disetujui."
            : "Reservasi #{$reservation->id} berhasil ditolak.";

        return back()->with('success', $message);
    }
}
