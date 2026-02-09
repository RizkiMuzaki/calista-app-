@extends('layouts.app')

@section('title', 'Grafik Progres Anak')

@section('content')
<div style="max-width: 1400px; margin: 0 auto; padding: 20px;">
    <h1 style="font-family: 'Fredoka One', cursive; font-size: 2.5rem; margin-bottom: 30px; color: #5c7cfa;">
        <i class="fas fa-chart-bar"></i> Grafik Progres {{ $activeChild->nama_anak }}
    </h1>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <!-- Reading Progress -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px; padding: 25px; color: white; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="margin: 0; font-family: 'Fredoka One', cursive; font-size: 1.3rem;">Membaca</h3>
                <i class="fas fa-book" style="font-size: 2rem; opacity: 0.8;"></i>
            </div>
            <p style="font-size: 2.5rem; font-weight: bold; margin: 0 0 10px 0;">{{ $readingProgress['completed'] }}/{{ $readingProgress['total'] }}</p>
            <div style="background: rgba(255,255,255,0.2); height: 8px; border-radius: 4px; overflow: hidden; margin-bottom: 10px;">
                <div style="height: 100%; background: #51cf66; width: {{ $readingProgress['percentage'] }}%;"></div>
            </div>
            <p style="margin: 0; opacity: 0.9; font-size: 0.9rem;">{{ round($readingProgress['percentage']) }}% Selesai</p>
        </div>

        <!-- Counting Progress -->
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 15px; padding: 25px; color: white; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="margin: 0; font-family: 'Fredoka One', cursive; font-size: 1.3rem;">Menghitung</h3>
                <i class="fas fa-calculator" style="font-size: 2rem; opacity: 0.8;"></i>
            </div>
            <p style="font-size: 2.5rem; font-weight: bold; margin: 0 0 10px 0;">{{ $countingProgress['completed'] }}/{{ $countingProgress['total'] }}</p>
            <div style="background: rgba(255,255,255,0.2); height: 8px; border-radius: 4px; overflow: hidden; margin-bottom: 10px;">
                <div style="height: 100%; background: #51cf66; width: {{ $countingProgress['percentage'] }}%;"></div>
            </div>
            <p style="margin: 0; opacity: 0.9; font-size: 0.9rem;">{{ round($countingProgress['percentage']) }}% Selesai</p>
        </div>

        <!-- Writing Progress -->
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 15px; padding: 25px; color: white; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="margin: 0; font-family: 'Fredoka One', cursive; font-size: 1.3rem;">Menulis</h3>
                <i class="fas fa-pen" style="font-size: 2rem; opacity: 0.8;"></i>
            </div>
            <p style="font-size: 2.5rem; font-weight: bold; margin: 0 0 10px 0;">{{ $writingProgress['completed'] }}/{{ $writingProgress['total'] }}</p>
            <div style="background: rgba(255,255,255,0.2); height: 8px; border-radius: 4px; overflow: hidden; margin-bottom: 10px;">
                <div style="height: 100%; background: #51cf66; width: {{ $writingProgress['percentage'] }}%;"></div>
            </div>
            <p style="margin: 0; opacity: 0.9; font-size: 0.9rem;">{{ round($writingProgress['percentage']) }}% Selesai</p>
        </div>
    </div>

    <!-- Charts Container -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(500px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <!-- Progress by Module -->
        <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <h3 style="font-family: 'Fredoka One', cursive; font-size: 1.5rem; margin-bottom: 20px; color: #5c7cfa;">
                <i class="fas fa-tasks"></i> Progres per Modul
            </h3>
            <canvas id="moduleProgressChart" height="300"></canvas>
        </div>

        <!-- Activity Type Distribution -->
        <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <h3 style="font-family: 'Fredoka One', cursive; font-size: 1.5rem; margin-bottom: 20px; color: #5c7cfa;">
                <i class="fas fa-pie-chart"></i> Distribusi Aktivitas
            </h3>
            <canvas id="activityDistributionChart" height="300"></canvas>
        </div>
    </div>

    <!-- Detailed Progress Table -->
    <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
        <h3 style="font-family: 'Fredoka One', cursive; font-size: 1.5rem; margin-bottom: 20px; color: #5c7cfa;">
            <i class="fas fa-list"></i> Detail Progres
        </h3>
        
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #5c7cfa; background: #f0f4ff;">
                    <th style="padding: 15px; text-align: left; color: #5c7cfa; font-weight: bold;">Modul</th>
                    <th style="padding: 15px; text-align: left; color: #5c7cfa; font-weight: bold;">Level</th>
                    <th style="padding: 15px; text-align: left; color: #5c7cfa; font-weight: bold;">Tipe</th>
                    <th style="padding: 15px; text-align: center; color: #5c7cfa; font-weight: bold;">Status</th>
                    <th style="padding: 15px; text-align: center; color: #5c7cfa; font-weight: bold;">Tanggal Selesai</th>
                </tr>
            </thead>
            <tbody>
                @forelse($detailProgress as $progress)
                <tr style="border-bottom: 1px solid #e0e0e0; hover: { background: #f9f9f9; }">
                    <td style="padding: 15px;">{{ $progress->module->name ?? '-' }}</td>
                    <td style="padding: 15px;">{{ $progress->level->title ?? '-' }}</td>
                    <td style="padding: 15px;">
                        <span style="display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold;
                            @if($progress->type === 'reading')
                                background: #e7f5ff; color: #1971c2;
                            @elseif($progress->type === 'counting')
                                background: #ffe7f1; color: #c2255c;
                            @else
                                background: #e0f2f1; color: #00796b;
                            @endif
                        ">
                            @if($progress->type === 'reading')
                                <i class="fas fa-book"></i> Membaca
                            @elseif($progress->type === 'counting')
                                <i class="fas fa-calculator"></i> Menghitung
                            @else
                                <i class="fas fa-pen"></i> Menulis
                            @endif
                        </span>
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        @if($progress->is_completed)
                            <span style="color: #51cf66; font-weight: bold;"><i class="fas fa-check-circle"></i> Selesai</span>
                        @else
                            <span style="color: #ffa94d; font-weight: bold;"><i class="fas fa-clock"></i> Dalam Proses</span>
                        @endif
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        {{ $progress->completed_at ? $progress->completed_at->format('d M Y') : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 20px; text-align: center; color: #999;">
                        <i class="fas fa-inbox"></i> Belum ada data progres
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Back Button -->
    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ route('anak.profile') }}" style="display: inline-flex; align-items: center; gap: 10px; padding: 12px 30px; background: #5c7cfa; color: white; text-decoration: none; border-radius: 10px; font-weight: bold;">
            <i class="fas fa-arrow-left"></i> Kembali ke Profil
        </a>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
    // Data dari server
    const moduleProgressData = {!! json_encode($moduleProgressChart) !!};
    const activityData = {
        reading: {{ $readingProgress['completed'] }},
        counting: {{ $countingProgress['completed'] }},
        writing: {{ $writingProgress['completed'] }}
    };

    // Module Progress Chart
    const moduleCtx = document.getElementById('moduleProgressChart').getContext('2d');
    new Chart(moduleCtx, {
        type: 'bar',
        data: {
            labels: moduleProgressData.labels,
            datasets: [{
                label: 'Selesai',
                data: moduleProgressData.completed,
                backgroundColor: '#51cf66',
                borderRadius: 10
            }, {
                label: 'Total',
                data: moduleProgressData.total,
                backgroundColor: '#e0e0e0',
                borderRadius: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: { font: { size: 12, weight: 'bold' } }
                }
            }
        }
    });

    // Activity Distribution Pie Chart
    const pieCtx = document.getElementById('activityDistributionChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Membaca', 'Menghitung', 'Menulis'],
            datasets: [{
                data: [activityData.reading, activityData.counting, activityData.writing],
                backgroundColor: ['#667eea', '#f5576c', '#00f2fe'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: { font: { size: 12, weight: 'bold' } }
                }
            }
        }
    });
</script>
@endsection
