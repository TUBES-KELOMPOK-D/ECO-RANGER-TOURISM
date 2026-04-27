<?php

namespace App\Http\Controllers;

use App\Models\EcoReportSubmission;
use Illuminate\Http\Request;
use App\Services\RankingService;

class EcoReporterController extends Controller
{
    public function create(Request $request)
    {
        $latitude = $request->query('latitude');
        $longitude = $request->query('longitude');
        $selectedLocation = null;

        if ($latitude && $longitude) {
            $selectedLocation = trim($latitude) . ', ' . trim($longitude);
        }

        return view('reports.create', compact('selectedLocation', 'latitude', 'longitude'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:Masalah Laut,Masalah Darat,Masalah Lingkungan',
            'description' => 'nullable|string|max:2000',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'nullable|image|max:5120',
        ]);

        $report = new EcoReportSubmission();
        $report->user_id = auth()->id();
        $report->title = $data['title'];
        $report->category = $data['category'];
        $report->description = $data['description'];
        $report->latitude = $data['latitude'];
        $report->longitude = $data['longitude'];
        $report->location = $data['latitude'] . ', ' . $data['longitude'];
        $report->status = 'menunggu';
        $report->report_date = now()->toDateString();

        if ($request->hasFile('photo')) {
            $report->photo_path = $request->file('photo')->store('report_photos', 'public');
        }

        $report->save();

        if (auth()->check()) {
            RankingService::addPoints(auth()->user(), 'report_issue', null, 'Laporan: ' . $report->title);
        }

        return redirect()->route('profile.index')->with('success', 'Laporan berhasil dikirim. Lihat status laporan di Profil Anda.');
    }

    public function success()
    {
        return view('reports.success');
    }
}
