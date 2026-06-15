<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marker;


class MapController extends Controller
{
    public function index()
    {
        return view('map');
    }

    public function apiMarkers()
    {
        $markers = Marker::all();
        return response()->json($markers);
    }

    public function create()
    {
        return view('markers.create');
    }

    public function adminIndex()
    {
        $markers = Marker::orderBy('created_at', 'desc')->paginate(15);
        return view('markers.index', compact('markers'));
    }

    public function store(Request $request)
    {
        // Decode coordinates if it's a JSON string (from manual form)
        if (is_string($request->input('coordinates'))) {
            $decoded = json_decode($request->input('coordinates'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request->merge(['coordinates' => $decoded]);
            }
        }

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
            'eco_rules' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $coordinates = $request->input('coordinates', []);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('markers', 'public');
        }

        $marker = new Marker();
        $shapeType = $request->input('shape_type', 'Marker');
        $marker->shape_type = $shapeType;
        $marker->status = $request->input('status', 'green');
        $marker->title = $request->input('title');
        $marker->location_name = $request->input('location_name');
        $marker->description = $request->input('description');
        $marker->coordinates = $coordinates;
        $marker->radius = $request->input('radius');
        $marker->user_id = auth()->id();
        $marker->map_layer_id = null;

        // Auto-set category berdasarkan shape_type jika belum diisi manual
        if ($request->filled('category')) {
            $marker->category = $request->input('category');
        } else {
            $marker->category = ($shapeType === 'Marker') ? 'Destinasi Wisata' : 'Kondisi Lingkungan';
        }

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
        // Parse eco_rules JSON string from hidden input
        $ecoRulesRaw = $request->input('eco_rules');
        if ($ecoRulesRaw && $ecoRulesRaw !== 'null') {
            $decoded = json_decode($ecoRulesRaw, true);
            if (is_array($decoded)) {
                $marker->eco_rules = $decoded;
            }
        }
        if ($request->filled('category')) {
            $marker->category = $request->input('category');
        }

        $marker->save();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Berhasil menyimpan lokasi']);
        }

        return redirect()->route('markers.edit', $marker->id)->with('success', 'Data marker berhasil ditambahkan!');
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
        } else {
            // Auto-derive category dari shape_type jika belum punya nilai
            if (empty($marker->category)) {
                $marker->category = ($marker->shape_type === 'Marker') ? 'Destinasi Wisata' : 'Kondisi Lingkungan';
            }
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
