<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm()
    {
        if (auth()->check()) {
            return $this->redirectByRole();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return $this->redirectByRole();
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Email atau password salah.');
    }

    private function redirectByRole()
    {
        return auth()->user()->role === 'admin'
            ? redirect('/admin/dashboard')->with('success', 'Selamat datang, ' . auth()->user()->name . '!')
            : redirect('/dashboard')->with('success', 'Selamat datang, ' . auth()->user()->name . '!');
    }
}
