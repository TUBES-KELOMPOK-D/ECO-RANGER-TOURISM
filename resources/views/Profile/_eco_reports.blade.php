{{-- Komponen daftar laporan Eco Reporter di halaman profil --}}
@if($ecoReports->isEmpty())
    <p>Belum ada laporan.</p>
@else
    <h3 class="text-2xl font-extrabold text-slate-900 mb-6">Laporan Saya</h3>
    <div class="flex flex-col gap-6">
    @foreach($ecoReports as $report)
        <div class="report-card border-2 border-slate-200 rounded-2xl bg-white p-6 shadow-sm flex flex-col gap-2">
            <div class="report-header flex items-center justify-between mb-2">
                <strong class="text-lg">{{ $report->title }}</strong>
                <span class="badge badge-{{ strtolower($report->status) }}">
                    {{ strtoupper($report->status) }}
                </span>
            </div>
            <div class="report-body">
                <p class="text-slate-700">{{ $report->description }}</p>
                <small class="text-slate-500 flex items-center gap-4 mt-2">
                    <span>📅 {{ $report->report_date ? $report->report_date->format('Y-m-d') : $report->created_at->format('Y-m-d') }}</span>
                    @if($report->location)
                        <span>📍 {{ $report->location }}</span>
                    @endif
                </small>
            </div>
        </div>
    @endforeach
    </div>
@endif

<style>
.badge-menunggu { background: #ffe9b0; color: #b38b00; padding: 2px 8px; border-radius: 8px; font-size: 0.8em; }
.badge-diverifikasi { background: #e0eaff; color: #0057b3; padding: 2px 8px; border-radius: 8px; font-size: 0.8em; }
.report-card { transition: box-shadow 0.2s; }
.report-card:hover { box-shadow: 0 4px 16px #e0e7ef; }
.report-header { display: flex; justify-content: space-between; align-items: center; }
</style>
