<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
            'action' => 'required|in:approve,reject',
        ]);

        if ($user->role !== 'pelanggan') {
            return back()->with('error', 'User bukan pelanggan.');
        }

        $user->update([
            'account_status' => $validated['action'] === 'approve' ? 'verified' : 'rejected',
        ]);

        $message = $validated['action'] === 'approve'
            ? "Akun {$user->name} berhasil diverifikasi."
            : "Akun {$user->name} berhasil ditolak.";

        return back()->with('success', $message);
    }
}
