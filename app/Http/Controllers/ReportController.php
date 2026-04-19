<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $reports = Report::with('user')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->orderByDesc('report_date')
            ->get();

        return view('reports.index', compact('reports', 'status'));
    }
}
