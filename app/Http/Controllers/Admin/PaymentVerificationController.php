<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentVerificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['reservation.user', 'reservation.treatment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }

    public function verify(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        $payment->update([
            'status' => $validated['action'] === 'approve' ? 'approved' : 'rejected',
        ]);

        $message = $validated['action'] === 'approve'
            ? "Pembayaran #{$payment->id} berhasil diverifikasi."
            : "Pembayaran #{$payment->id} berhasil ditolak.";

        return back()->with('success', $message);
    }
}
