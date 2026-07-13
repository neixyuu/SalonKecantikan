<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'        => User::where('role', 'pelanggan')->count(),
            'pending_users'      => User::where('role', 'pelanggan')->where('account_status', 'pending')->count(),
            'total_reservations' => Reservation::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'total_payments'     => Payment::count(),
            'pending_payments'   => Payment::where('status', 'pending')->count(),
        ];

        $recentReservations = Reservation::with(['user', 'treatment'])
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::where('role', 'pelanggan')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentReservations', 'recentUsers'));
    }
}
