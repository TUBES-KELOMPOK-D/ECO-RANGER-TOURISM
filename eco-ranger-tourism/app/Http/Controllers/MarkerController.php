<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;

class MarkerController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'description' => ['required', 'string', 'max:1000'],
        ]);

        Marker::create([
            'user_id' => auth()->id(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'description' => $request->description,
            'coordinates' => [$request->latitude, $request->longitude],
        ]);

        return back()->with('success', 'Marker baru berhasil ditambahkan.');
    }
}
