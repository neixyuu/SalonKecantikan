<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Treatment;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::where('user_id', auth()->id())
            ->with(['treatment', 'payment'])
            ->latest()
            ->get();

        return view('pelanggan.reservations.index', compact('reservations'));
    }

    public function create(Request $request)
    {
        $treatments        = Treatment::all();
        $selectedTreatment = null;

        if ($request->has('treatment_id')) {
            $selectedTreatment = Treatment::find($request->treatment_id);
        }

        return view('pelanggan.reservations.create', compact('treatments', 'selectedTreatment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'treatment_id'  => 'required|exists:treatments,id',
            'schedule_date' => 'required|date|after_or_equal:today',
            'schedule_time' => 'required',
            'notes'         => 'nullable|string|max:500',
        ], [
            'treatment_id.required'        => 'Pilih treatment terlebih dahulu.',
            'treatment_id.exists'          => 'Treatment tidak valid.',
            'schedule_date.required'       => 'Tanggal jadwal wajib diisi.',
            'schedule_date.after_or_equal' => 'Tanggal jadwal minimal hari ini.',
            'schedule_time.required'       => 'Jam jadwal wajib diisi.',
        ]);

        Reservation::create([
            'user_id'      => auth()->id(),
            'treatment_id' => $validated['treatment_id'],
            'schedule_date'=> $validated['schedule_date'],
            'schedule_time'=> $validated['schedule_time'],
            'notes'        => $validated['notes'] ?? null,
            'status'       => 'pending',
        ]);

        return redirect('/reservations')
            ->with('success', 'Reservasi berhasil dibuat! Menunggu konfirmasi admin.');
    }

    /**
     * Tampilkan form reschedule.
     */
    public function rescheduleForm(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id()) abort(403);

        if ($reservation->status !== 'approved') {
            return redirect('/reservations')->with('error', 'Hanya reservasi yang sudah disetujui yang bisa diganti jadwalnya.');
        }

        return view('pelanggan.reservations.reschedule', compact('reservation'));
    }

    /**
     * Pelanggan mengajukan permintaan ganti jadwal.
     */
    public function rescheduleRequest(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id()) abort(403);

        if ($reservation->status !== 'approved') {
            return back()->with('error', 'Hanya reservasi yang sudah disetujui yang bisa diganti jadwalnya.');
        }

        $validated = $request->validate([
            'reschedule_date' => 'required|date|after_or_equal:today',
            'reschedule_time' => 'required',
        ], [
            'reschedule_date.required'        => 'Tanggal baru wajib diisi.',
            'reschedule_date.after_or_equal'  => 'Tanggal baru minimal hari ini.',
            'reschedule_time.required'        => 'Jam baru wajib diisi.',
        ]);

        $reservation->update([
            'reschedule_requested_date' => $validated['reschedule_date'],
            'reschedule_requested_time' => $validated['reschedule_time'],
            'cancel_requested_at'       => null,
        ]);

        return redirect('/reservations')
            ->with('success', 'Permintaan ganti jadwal berhasil diajukan. Menunggu konfirmasi admin.');
    }
}
