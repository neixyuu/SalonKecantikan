<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;

class StatusAkunController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('pelanggan.status-akun', compact('user'));
    }
}
