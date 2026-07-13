<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('admin')->latest()->get();
        return view('pelanggan.announcements.index', compact('announcements'));
    }
}
