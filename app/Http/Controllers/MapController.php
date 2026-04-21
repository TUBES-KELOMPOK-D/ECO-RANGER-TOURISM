<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marker;


class MapController extends Controller
{
    public function index()
    {
        // Menampilkan data markers
        $markers = Marker::all();

        return view('map', compact('markers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shape_type' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'coordinates' => ['required', 'array'],
            'radius' => ['nullable', 'numeric'],
            'image' => ['nullable', 'image', 'max:5120'],
            'location_name' => ['nullable', 'string', 'max:255'],
            'eco_health_score' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'kebersihan' => ['nullable', 'string', 'max:50'],
            'akses' => ['nullable', 'string', 'max:50'],
            'populasi' => ['nullable', 'string', 'max:50'],
            'eco_rules' => ['nullable', 'json'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $coordinates = $request->input('coordinates', []);
        if (is_string($coordinates)) {
            $coordinates = json_decode($coordinates, true) ?: [];
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('markers', 'public');
        }

        $marker = new Marker();
        $marker->shape_type = $request->input('shape_type', 'Marker');
        $marker->status = $request->input('status', 'green');
        $marker->title = $request->input('title');
        $marker->location_name = $request->input('location_name');
        $marker->description = $request->input('description');
        $marker->coordinates = $coordinates;
        $marker->radius = $request->input('radius');
        $marker->user_id = auth()->id();
        $marker->map_layer_id = null;

        if ($imagePath) {
            $marker->image_path = $imagePath;
        }

        // Detail fields
        if ($request->filled('eco_health_score')) {
            $marker->eco_health_score = $request->input('eco_health_score');
        }
        if ($request->filled('kebersihan')) {
            $marker->kebersihan = $request->input('kebersihan');
        }
        if ($request->filled('akses')) {
            $marker->akses = $request->input('akses');
        }
        if ($request->filled('populasi')) {
            $marker->populasi = $request->input('populasi');
        }
        if ($request->filled('eco_rules')) {
            $marker->eco_rules = json_decode($request->input('eco_rules'), true);
        }
        if ($request->filled('category')) {
            $marker->category = $request->input('category');
        }

        $marker->save();

        return response()->json(['message' => 'Berhasil menyimpan lokasi']);
    }

    public function edit(Marker $marker)
    {
        return view('markers.edit', compact('marker'));
    }

    public function update(Request $request, Marker $marker)
    {
        $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'location_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:5120'],
            'eco_health_score' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'kebersihan' => ['nullable', 'string', 'max:50'],
            'akses' => ['nullable', 'string', 'max:50'],
            'populasi' => ['nullable', 'string', 'max:50'],
            'eco_rules' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $marker->title = $request->input('title', $marker->title);
        $marker->location_name = $request->input('location_name', $marker->location_name);
        $marker->description = $request->input('description', $marker->description);
        $marker->status = $request->input('status', $marker->status);

        if ($request->hasFile('image')) {
            $marker->image_path = $request->file('image')->store('markers', 'public');
        }

        if ($request->filled('eco_health_score')) {
            $marker->eco_health_score = $request->input('eco_health_score');
        }
        if ($request->filled('kebersihan')) {
            $marker->kebersihan = $request->input('kebersihan');
        }
        if ($request->filled('akses')) {
            $marker->akses = $request->input('akses');
        }
        if ($request->filled('populasi')) {
            $marker->populasi = $request->input('populasi');
        }
        if ($request->filled('category')) {
            $marker->category = $request->input('category');
        }

        // Parse eco_rules from textarea (JSON string)
        $ecoRulesRaw = $request->input('eco_rules');
        if ($ecoRulesRaw) {
            $decoded = json_decode($ecoRulesRaw, true);
            if (is_array($decoded)) {
                $marker->eco_rules = $decoded;
            }
        }

        $marker->save();

        return redirect()->route('markers.edit', $marker->id)->with('success', 'Data marker berhasil diperbarui!');
    }

    public function destroy(Marker $marker)
    {
        $marker->delete();
        return redirect('/')->with('success', 'Marker berhasil dihapus.');
    }
}
