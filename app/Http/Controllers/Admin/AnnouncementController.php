<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('admin')->latest()->paginate(10);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'required|string',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'video_url' => 'nullable|url|max:255',
        ], [
            'title.required'   => 'Judul pengumuman wajib diisi.',
            'content.required' => 'Isi pengumuman wajib diisi.',
            'image.image'      => 'File harus berupa gambar.',
            'image.max'        => 'Ukuran gambar maksimal 2MB.',
            'video_url.url'    => 'Format URL video tidak valid.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('announcements', 'public');
        }

        Announcement::create([
            'admin_id'  => auth()->id(),
            'title'     => $validated['title'],
            'content'   => $validated['content'],
            'image'     => $imagePath,
            'video_url' => $validated['video_url'] ?? null,
        ]);

        return redirect('/admin/announcements')
            ->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'required|string',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'video_url' => 'nullable|url|max:255',
        ]);

        $imagePath = $announcement->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('announcements', 'public');
        }

        $announcement->update([
            'title'     => $validated['title'],
            'content'   => $validated['content'],
            'image'     => $imagePath,
            'video_url' => $validated['video_url'] ?? null,
        ]);

        return redirect('/admin/announcements')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect('/admin/announcements')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
