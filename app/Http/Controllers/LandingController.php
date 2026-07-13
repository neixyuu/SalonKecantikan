<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Treatment;

class LandingController extends Controller
{
    public function index()
    {
        $treatments    = Treatment::take(3)->get();
        $announcements = Announcement::latest()->take(2)->get();

        return view('landing', compact('treatments', 'announcements'));
    }
}
