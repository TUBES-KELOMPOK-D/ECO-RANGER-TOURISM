<?php

namespace App\Http\Controllers;

use App\Models\EcoReportSubmission;
use Illuminate\Http\Request;

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
}
