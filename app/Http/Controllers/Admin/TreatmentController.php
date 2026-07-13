<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Treatment;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index()
    {
        $treatments = Treatment::latest()->paginate(10);
        return view('admin.treatments.index', compact('treatments'));
    }

    public function create()
    {
        return view('admin.treatments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'duration'    => 'required|string|max:50',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'        => 'Nama treatment wajib diisi.',
            'description.required' => 'Deskripsi treatment wajib diisi.',
            'price.required'       => 'Harga wajib diisi.',
            'price.numeric'        => 'Harga harus berupa angka.',
            'duration.required'    => 'Durasi wajib diisi.',
            'image.image'          => 'File harus berupa gambar.',
            'image.max'            => 'Ukuran gambar maksimal 2MB.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('treatments', 'public');
        }

        Treatment::create([
            'name'        => $validated['name'],
            'description' => $validated['description'],
            'price'       => $validated['price'],
            'duration'    => $validated['duration'],
            'image'       => $imagePath,
        ]);

        return redirect('/admin/treatments')
            ->with('success', 'Treatment berhasil ditambahkan.');
    }

    public function edit(Treatment $treatment)
    {
        return view('admin.treatments.edit', compact('treatment'));
    }

    public function update(Request $request, Treatment $treatment)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'duration'    => 'required|string|max:50',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = $treatment->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('treatments', 'public');
        }

        $treatment->update([
            'name'        => $validated['name'],
            'description' => $validated['description'],
            'price'       => $validated['price'],
            'duration'    => $validated['duration'],
            'image'       => $imagePath,
        ]);

        return redirect('/admin/treatments')
            ->with('success', 'Treatment berhasil diperbarui.');
    }

    public function destroy(Treatment $treatment)
    {
        $treatment->delete();
        return redirect('/admin/treatments')
            ->with('success', 'Treatment berhasil dihapus.');
    }
}
