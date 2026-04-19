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
        ]);

        $coordinates = $request->input('coordinates', []);
        if (is_string($coordinates)) {
            $coordinates = json_decode($coordinates, true) ?: [];
        }

        $marker = new Marker();
        $marker->shape_type = $request->input('shape_type', 'Marker');
        $marker->status = $request->input('status', 'green');
        $marker->title = $request->input('title');
        $marker->description = $request->input('description');
        $marker->coordinates = $coordinates;
        $marker->radius = $request->input('radius');
        $marker->user_id = auth()->id();
        $marker->map_layer_id = null;
        $marker->save();

        return response()->json(['message' => 'Berhasil menyimpan lokasi']);
    }
}
