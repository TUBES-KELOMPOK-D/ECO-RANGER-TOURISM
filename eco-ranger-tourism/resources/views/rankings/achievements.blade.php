@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 900px; margin: 0 auto; padding: 20px;">
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 30px; padding: 25px 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 15px; color: white;">
        <div style="font-size: 1.8em; font-weight: 700; margin-bottom: 10px;">Pencapaian Saya</div>
        <div style="font-size: 0.95em; opacity: 0.95;">Lacak progress menuju badge-badge eksklusif</div>
    </div>

    <!-- User Stats -->
    <div style="background: white; border-radius: 15px; padding: 20px; margin-bottom: 30px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); border-left: 4px solid #10b981;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
            <div style="text-align: center;">
                <div style="font-size: 2.5em; font-weight: 700; color: #10b981;">{{ $user->eco_points ?? 0 }}</div>
                <div style="color: #6b7280; font-size: 0.9em;">Total Poin</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 2.5em; font-weight: 700; color: #10b981;">{{ $rank ?? 'N/A' }}</div>
                <div style="color: #6b7280; font-size: 0.9em;">Global Rank</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 2.5em; font-weight: 700; color: #10b981;">{{ $user->eco_level ?? 'ECO-MEMBER' }}</div>
                <div style="color: #6b7280; font-size: 0.9em;">Level</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 2.5em; font-weight: 700; color: #10b981;">{{ $achievements->where('is_completed', true)->count() }}</div>
                <div style="color: #6b7280; font-size: 0.9em;">Badges</div>
            </div>
        </div>
    </div>

    <!-- Achievements -->
    <h2 style="font-size: 1.3em; font-weight: 700; margin-bottom: 20px; color: #1f2937;">Lencana & Pencapaian</h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
        @forelse($achievements as $achievement)
            <div style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); border: 1px solid #e5e7eb; transition: all 0.3s ease; {{ $achievement['is_completed'] ? 'border: 2px solid #10b981;' : '' }} ">
                <div style="display: flex; align-items: flex-start; gap: 15px; margin-bottom: 15px;">
                    <div style="font-size: 2.5em; display: flex; align-items: center; justify-content: center; width: 60px; height: 60px; background: rgba(16, 185, 129, 0.15); border-radius: 12px; flex-shrink: 0;">{{ $achievement['icon'] }}</div>
                    <div style="flex: 1;">
                        <div style="font-weight: 700; font-size: 1.1em; color: #1f2937;">{{ $achievement['name'] }}</div>
                        <div style="font-size: 0.85em; color: #6b7280;">{{ $achievement['description'] }}</div>
                    </div>
                    @if($achievement['is_completed'])
                        <div style="background: #10b981; color: white; padding: 5px 10px; border-radius: 8px; font-size: 0.75em; font-weight: 700;">✓ SELESAI</div>
                    @endif
                </div>

                <!-- Progress Bar -->
                <div style="margin-top: 15px;">
                    <div style="background: #e5e7eb; border-radius: 10px; height: 10px; overflow: hidden; border: 1px solid #d1d5db;">
                        <div style="height: 100%; background: linear-gradient(90deg, #10b981 0%, #34d399 100%); width: {{ $achievement['progress'] }}%; border-radius: 10px; transition: width 0.6s ease; box-shadow: 0 0 10px rgba(16, 185, 129, 0.4);"></div>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px; font-size: 0.8em;">
                        <span style="color: #6b7280;">Progress</span>
                        <span style="font-weight: 700; color: #10b981;">{{ $achievement['progress'] }}%</span>
                    </div>
                    <div style="font-size: 0.8em; color: #6b7280; margin-top: 5px;">{{ $achievement['current'] }}/{{ $achievement['target'] }} selesai</div>
                </div>

                @if($achievement['is_completed'] && $achievement['completed_at'])
                    <div style="margin-top: 10px; padding: 10px; background: #d1fae5; border-radius: 8px; font-size: 0.8em; color: #059669;">
                        ✓ Diselesaikan pada {{ $achievement['completed_at']->format('d M Y') }}
                    </div>
                @endif
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #6b7280;">
                <p>Belum ada achievement tersedia</p>
            </div>
        @endforelse
    </div>

    <!-- Motivational Message -->
    <div style="margin-top: 40px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 15px; padding: 20px; border-left: 4px solid #f59e0b; color: #92400e;">
        <div style="font-weight: 700; margin-bottom: 8px;">Tips Mengumpulkan Poin</div>
        <ul style="font-size: 0.9em; line-height: 1.6; margin-left: 20px;">
            <li>Laporkan masalah lingkungan yang Anda temui</li>
            <li>Bergabung dengan aksi komunitas yang sedang berlangsung</li>
            <li>Verifikasi laporan dari eco-warriors lain</li>
            <li>Berbagi konten dan pengalaman di forum</li>
            <li>Bagikan inisiatif hijau Anda melalui foto dan video</li>
        </ul>
    </div>

    <!-- Back to Leaderboard -->
    <div style="margin-top: 30px; text-align: center;">
        <a href="{{ route('rankings.index') }}" style="display: inline-block; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
            ← Lihat Leaderboard
        </a>
    </div>
</div>

<style>
    a:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3) !important;
    }
    
    @media (max-width: 768px) {
        .container {
            padding: 15px !important;
        }
    }
</style>
@endsection
