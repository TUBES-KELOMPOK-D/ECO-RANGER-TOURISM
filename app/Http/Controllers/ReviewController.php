<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Simpan review baru untuk marker.
     * Hanya user yang sudah login yang bisa submit.
     */
    public function store(Request $request, Marker $marker)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review_text' => ['required', 'string', 'min:10', 'max:500'],
        ], [
            'rating.required' => 'Silakan pilih rating bintang.',
            'rating.min' => 'Rating minimal 1 bintang.',
            'rating.max' => 'Rating maksimal 5 bintang.',
            'review_text.required' => 'Komentar tidak boleh kosong.',
            'review_text.min' => 'Komentar minimal 10 karakter.',
            'review_text.max' => 'Komentar maksimal 500 karakter.',
        ]);

        // Cek apakah user sudah pernah review marker ini
        $existing = Review::where('marker_id', $marker->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            return back()->with('review_error', 'Kamu sudah pernah memberikan review untuk lokasi ini.')->withInput();
        }

        Review::create([
            'marker_id' => $marker->id,
            'user_id' => auth()->id(),
            'rating' => $request->input('rating'),
            'review_text' => $request->input('review_text'),
        ]);

        return back()->with('review_success', 'Review berhasil ditambahkan! Terima kasih atas ulasanmu.');
    }
}
