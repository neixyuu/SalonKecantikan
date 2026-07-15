<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AccountRejectedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserVerificationController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'pelanggan');

        if ($request->filled('status')) {
            $query->where('account_status', $request->status);
        }

        $users = $query->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function verify(Request $request, User $user)
    {
        $validated = $request->validate([
            'action'           => 'required|in:approve,reject',
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        if ($user->role !== 'pelanggan') {
            return back()->with('error', 'User bukan pelanggan.');
        }

        // Guard: jangan proses ulang jika sudah diputuskan
        if ($user->account_status !== 'pending') {
            return back()->with('error', 'Akun ini sudah pernah diverifikasi sebelumnya.');
        }

        $user->update([
            'account_status' => $validated['action'] === 'approve' ? 'verified' : 'rejected',
        ]);

        if ($validated['action'] === 'reject') {
            $reason = $validated['rejection_reason'] ?? 'Tidak ada alasan yang diberikan.';
            try {
                Mail::to($user->email)->send(new AccountRejectedMail($user, $reason));
            } catch (\Exception $e) {
                // Gagal kirim email tidak menghentikan proses utama
                \Log::warning("Gagal mengirim email penolakan ke {$user->email}: " . $e->getMessage());
            }
        }

        $message = $validated['action'] === 'approve'
            ? "Akun {$user->name} berhasil diverifikasi."
            : "Akun {$user->name} berhasil ditolak. Email notifikasi telah dikirim.";

        return back()->with('success', $message);
    }
}
