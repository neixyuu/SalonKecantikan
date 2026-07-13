<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showForm()
    {
        if (auth()->check()) {
            return $this->redirectByRole();
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone'    => 'nullable|string|max:20',
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah digunakan.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'password'       => Hash::make($validated['password']),
            'phone'          => $validated['phone'] ?? null,
            'role'           => 'pelanggan',
            'account_status' => 'pending',
        ]);

        return redirect('/login')
            ->with('success', 'Pendaftaran berhasil! Akun Anda sedang menunggu verifikasi admin.');
    }

    private function redirectByRole()
    {
        return auth()->user()->role === 'admin'
            ? redirect('/admin/dashboard')
            : redirect('/dashboard');
    }
}
