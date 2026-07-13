<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Reservation;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $activeReservation = Reservation::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->with(['treatment', 'payment'])
            ->latest()
            ->first();

        $reservationCount = Reservation::where('user_id', $user->id)->count();
        $pendingCount     = Reservation::where('user_id', $user->id)->where('status', 'pending')->count();
        $approvedCount    = Reservation::where('user_id', $user->id)->where('status', 'approved')->count();

        $announcements = Announcement::latest()->take(3)->get();

        return view('pelanggan.dashboard', compact(
            'user',
            'activeReservation',
            'reservationCount',
            'pendingCount',
            'approvedCount',
            'announcements'
        ));
    }
}
