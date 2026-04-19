@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 1400px; margin: 0 auto; padding: 20px;">
    <!-- Header -->
    <div class="header" style="text-align: center; margin-bottom: 40px; padding: 30px 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 20px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12); color: white;">
        <div style="font-size: 2.5em; font-weight: 700; margin-bottom: 10px;">Eco-Rankings</div>
        <div style="font-size: 1em; opacity: 0.95; font-weight: 300;">Bergabunglah dengan eco-warriors dan lindungi planet kita</div>
    </div>

    <!-- Main Grid Layout -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 25px; margin-bottom: 30px;">
        <!-- Leaderboard -->
        <div style="background: white; border-radius: 20px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); overflow: hidden; display: flex; flex-direction: column; transition: all 0.3s ease; border: 1px solid rgba(16, 185, 129, 0.1);">
            <div style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%); color: white; padding: 20px; font-size: 1.3em; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                Papan Peringkat
            </div>
            <div style="padding: 20px; flex: 1; overflow-y: auto; min-height: 300px;">
                @forelse($topRankers as $ranker)
                    <div style="display: flex; align-items: center; gap: 15px; padding: 15px; margin-bottom: 12px; background: linear-gradient(135deg, {{ $ranker['rank'] == 1 ? '#fef3c7' : ($ranker['rank'] == 2 ? '#f3f4f6' : '#fed7aa') }} 0%, {{ $ranker['rank'] == 1 ? '#fde68a' : ($ranker['rank'] == 2 ? '#e5e7eb' : '#fdba74') }} 100%); border-radius: 12px; border: 2px solid {{ $ranker['rank'] == 1 ? '#fbbf24' : ($ranker['rank'] == 2 ? '#c0cfd9' : '#d97706') }};">
                        <div style="width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.3em; color: white; flex-shrink: 0; background: linear-gradient(135deg, {{ $ranker['rank'] == 1 ? '#fbbf24 0%, #f59e0b' : ($ranker['rank'] == 2 ? '#c0cfd9 0%, #9ca3af' : '#d97706 0%, #ea580c') }} 100%); box-shadow: 0 4px 15px rgba({{ $ranker['rank'] == 1 ? '251, 191, 36' : ($ranker['rank'] == 2 ? '192, 207, 217' : '217, 119, 6') }}, 0.3);">
                            #{{ $ranker['rank'] }}
                        </div>
                        <div style="width: 45px; height: 45px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.95em; color: white; flex-shrink: 0; background: linear-gradient(135deg, {{ $ranker['rank'] == 1 ? '#fbbf24 0%, #f59e0b' : ($ranker['rank'] == 2 ? '#c0cfd9 0%, #9ca3af' : '#d97706 0%, #dc2626') }} 100%);">
                            {{ $ranker['initials'] }}
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-weight: 600; font-size: 0.95em; color: #1f2937; margin-bottom: 3px;">{{ $ranker['name'] }}</div>
                            <div style="font-size: 0.8em; color: #10b981; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">{{ $ranker['level'] }}</div>
                        </div>
                        <div style="text-align: right; white-space: nowrap;">
                            <div style="font-weight: 700; font-size: 1.1em; color: #10b981;">{{ $ranker['points'] }}</div>
                            <div style="font-size: 0.7em; color: #6b7280; text-transform: uppercase; letter-spacing: 0.3px;">PTS</div>
                        </div>
                    </div>
                @empty
                    <p style="text-align: center; color: #6b7280;">Belum ada data ranking</p>
                @endforelse
            </div>
        </div>

        <!-- Point Rules -->
        <div style="background: white; border-radius: 20px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); overflow: hidden; display: flex; flex-direction: column; transition: all 0.3s ease; border: 1px solid rgba(16, 185, 129, 0.1);">
            <div style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%); color: white; padding: 20px; font-size: 1.3em; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                Aturan Poin
            </div>
            <div style="padding: 20px; flex: 1; overflow-y: auto; min-height: 300px;">
                @foreach($pointRules as $rule)
                    <div style="display: flex; align-items: center; gap: 15px; padding: 14px; margin-bottom: 12px; background: linear-gradient(135deg, #f0fdf4 0%, #f9fafb 100%); border-radius: 12px; border-left: 4px solid #10b981; transition: all 0.3s ease; cursor: pointer;">
                        <div style="font-size: 1.8em; flex-shrink: 0; display: flex; align-items: center; justify-content: center; width: 45px; height: 45px; background: rgba(16, 185, 129, 0.1); border-radius: 10px;">{{ $rule['icon'] }}</div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-weight: 600; font-size: 0.9em; color: #1f2937; margin-bottom: 2px;">{{ $rule['title'] }}</div>
                            <div style="color: #6b7280; font-weight: 400; font-size: 0.8em;">{{ $rule['description'] }}</div>
                        </div>
                        <div style="font-weight: 700; font-size: 1em; color: #10b981; text-align: right;">+{{ $rule['points'] }} PTS</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div style="text-align: center; padding: 30px 20px; color: #6b7280; font-size: 0.9em; margin-top: 40px;">
        <div style="width: 60px; height: 3px; background: linear-gradient(90deg, transparent 0%, #10b981 50%, transparent 100%); margin: 15px auto; border-radius: 2px;"></div>
        <p>Bersama kita jaga kelestarian lingkungan untuk generasi mendatang</p>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .container {
            padding: 15px !important;
        }
    }
</style>
@endsection
