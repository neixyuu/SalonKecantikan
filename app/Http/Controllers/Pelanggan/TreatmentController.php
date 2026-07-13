<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Treatment;

class TreatmentController extends Controller
{
    public function index()
    {
        $treatments = Treatment::latest()->get();
        return view('pelanggan.treatments.index', compact('treatments'));
    }
}
