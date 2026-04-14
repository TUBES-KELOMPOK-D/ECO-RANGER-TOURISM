<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
            'category'    => 'required|string',
            'description' => 'nullable|string',
            'photo'       => 'nullable|image|max:5120',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('eco-reports', 'public');
        }

        Report::create([
            'user_id'    => auth()->id() ?? 1, // fallback untuk testing tanpa login
            'title'      => $request->title,
            'description'=> $request->description,
            'photo_path' => $photoPath,
            'location'   => $request->latitude . ',' . $request->longitude,
            'category'   => $request->category,
            'status'     => 'menunggu',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim! +10 Poin Dasar.',
        ], 201);
    }
}
