<?php

namespace App\Http\Controllers;

use App\Models\EcoReportSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $user = $request->user();

        $reports = EcoReportSubmission::with('user')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($user->role !== 'admin', fn ($query) => $query->where('user_id', $user->id))
            ->orderByDesc('report_date')
            ->get();

        return view('reports.index', compact('reports', 'status'));
    }

    public function adminIndex(Request $request)
    {
        $status = $request->query('status');

        $reports = EcoReportSubmission::with('user')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->orderByDesc('report_date')
            ->get();

        return view('reports.index', compact('reports', 'status'));
    }

    public function edit(EcoReportSubmission $report)
    {
        // Only admin can edit reports
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('reports.edit', compact('report'));
    }

    public function update(Request $request, EcoReportSubmission $report)
    {
        // Only admin can update reports
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $data = $request->validate([
            'status' => 'required|in:menunggu,diverifikasi,diterima,ditolak',
        ]);

        $report->update($data);

        return redirect()->route('admin.reports.index')->with('success', 'Status laporan berhasil diperbarui.');
    }

    public function destroy(EcoReportSubmission $report)
    {
        // Only admin can delete reports
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Delete associated photo if exists
        if ($report->photo_path && Storage::disk('public')->exists($report->photo_path)) {
            Storage::disk('public')->delete($report->photo_path);
        }

        $report->delete();

        return redirect()->route('admin.reports.index')->with('success', 'Laporan berhasil dihapus.');
    }
}
