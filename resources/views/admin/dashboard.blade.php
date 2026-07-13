@extends('layouts.admin')

@section('title', 'Dashboard — Admin')

@section('page-heading')
    {{-- intentionally blank; dashboard has no sub-heading --}}
@endsection

@section('content')

{{-- ── Stats Cards ──────────────────────────────────────── --}}
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-icon stat-icon--blue">
            <i class="fas fa-layer-group"></i>
        </div>
        <div class="stat-body">
            <span class="stat-label">Total Libraries</span>
            <span class="stat-value">{{ $totalLibraries }}</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon--indigo">
            <i class="fas fa-code"></i>
        </div>
        <div class="stat-body">
            <span class="stat-label">Language</span>
            <span class="stat-value">{{ $languages }}</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon--teal">
            <i class="fas fa-tags"></i>
        </div>
        <div class="stat-body">
            <span class="stat-label">Categories</span>
            <span class="stat-value">{{ $categories }}</span>
        </div>
    </div>

</div>

{{-- ── Charts Row ────────────────────────────────────────── --}}
<div class="charts-row">

    <div class="chart-card chart-card--bar">
        <div class="chart-canvas-wrap">
            <canvas id="langChart"></canvas>
        </div>
        <p class="chart-label">Libraries by Programming Language</p>
    </div>

    <div class="chart-card chart-card--pie">
        <div class="chart-canvas-wrap">
            <canvas id="pqcChart"></canvas>
        </div>
        <p class="chart-label">Library Categories</p>
    </div>

</div>

{{-- ── Recent Libraries Table ──────────────────────────── --}}
<div class="data-card">
    <h2 class="data-card-title">RECENTLY ADDED LIBRARIES</h2>
    <div class="table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Language</th>
                    <th>Category</th>
                    <th>Version</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentLibraries as $lib)
                <tr>
                    <td>{{ $lib->name }}</td>
                    <td>{{ $lib->language ?? '—' }}</td>
                    <td>{{ $lib->category ?? '—' }}</td>
                    <td>{{ $lib->latest_version ?? '—' }}</td>
                    <td>{{ $lib->created_at ? $lib->created_at->format('Y-m-d') : '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="table-empty">No libraries found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    'use strict';

    // ── Bar chart ──────────────────────────────────────────
    const langLabels = @json($langCounts->keys()->values());
    const langValues = @json($langCounts->values()->values());

    new Chart(document.getElementById('langChart'), {
        type: 'bar',
        data: {
            labels: langLabels,
            datasets: [{
                label: 'Programming Language',
                data: langValues,
                backgroundColor: 'rgba(58, 157, 225, 0.85)',
                borderColor: '#3a9de1',
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { color: '#374151', font: { size: 12 } } },
                tooltip: { callbacks: { label: ctx => ' ' + ctx.raw } }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { color: '#6b7280', precision: 0 }
                },
                y: {
                    grid: { display: false },
                    ticks: { color: '#374151', font: { weight: '600' } }
                }
            }
        }
    });

    // ── Pie chart ──────────────────────────────────────────
    const pqcTotal = {{ $pqcCount + $nonPqcCount }};
    new Chart(document.getElementById('pqcChart'), {
        type: 'doughnut',
        data: {
            labels: ['PQC Algo', 'Non-PQC Algo'],
            datasets: [{
                data: [{{ $pqcCount }}, {{ $nonPqcCount }}],
                backgroundColor: ['#3a9de1', '#a8d4f5'],
                borderColor: ['#2e7cb5', '#7ab8e8'],
                borderWidth: 2,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#374151',
                        font: { size: 12 },
                        padding: 16,
                    }
                },
                tooltip: {
                    callbacks: {
                        label: ctx => {
                            const pct = pqcTotal > 0
                                ? ((ctx.raw / pqcTotal) * 100).toFixed(1)
                                : 0;
                            return ` ${ctx.label}: ${pct}%`;
                        }
                    }
                }
            },
            cutout: '55%',
        }
    });
})();
</script>
@endsection
