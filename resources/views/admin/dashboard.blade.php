@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="mb-1 fw-bold" style="color:#0f172a;">Admin Console</h2>
            <p class="text-muted mb-0">Brilliant overview of families, devices, and incidents.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.reports') }}" class="btn btn-outline-dark fw-semibold shadow-sm">Assign devices</a>
            <a href="{{ route('admin.megaReports') }}" class="btn btn-dark fw-semibold shadow-sm">Mega report</a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-3">
            <div class="card shadow-sm border-0" style="border-radius:16px; overflow:hidden;">
                <div class="card-body text-center text-white" style="background: linear-gradient(135deg, #0b5ed7 0%, #4aa3ff 100%);">
                    <div class="small text-white-50 text-uppercase fw-semibold">Total Families</div>
                    <div class="display-6 fw-bold">{{ $families_total }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card shadow-sm border-0" style="border-radius:16px; overflow:hidden;">
                <div class="card-body text-center text-white" style="background: linear-gradient(135deg, #198754 0%, #34d399 100%);">
                    <div class="small text-white-50 text-uppercase fw-semibold">Total Devices</div>
                    <div class="display-6 fw-bold">{{ $devices_total }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card shadow-sm border-0" style="border-radius:16px; overflow:hidden;">
                <div class="card-body text-center text-white" style="background: linear-gradient(135deg, #0ea5e9 0%, #22d3ee 100%);">
                    <div class="small text-white-50 text-uppercase fw-semibold">Total Users</div>
                    <div class="display-6 fw-bold">{{ $users_total }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card shadow-sm border-0" style="border-radius:16px; overflow:hidden;">
                <div class="card-body text-center text-white" style="background: linear-gradient(135deg, #d97706 0%, #fbbf24 100%);">
                    <div class="small text-white-50 text-uppercase fw-semibold">Reports</div>
                    <div class="display-6 fw-bold">{{ $reports_total }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0" style="border-radius:16px; overflow:hidden;">
                <div class="card-header" style="background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%); color:white; font-weight:700;">
                    Families Overview
                </div>
                <div class="card-body" style="height:320px;">
                    <canvas id="familiesChart" height="320"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0" style="border-radius:16px; overflow:hidden;">
                <div class="card-header" style="background: linear-gradient(135deg, #16a34a 0%, #22d3ee 100%); color:white; font-weight:700;">
                    Devices Overview
                </div>
                <div class="card-body" style="height:320px;">
                    <canvas id="devicesChart" height="320"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="card shadow-sm border-0" style="border-radius:16px; overflow:hidden;">
        <div class="card-header" style="background: linear-gradient(135deg, #0ea5e9 0%, #111827 100%); color:white; font-weight:700;">
            Device Assignments
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Device</th>
                            <th>Token</th>
                            <th>Assigned Family</th>
                            <th>Members</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($devices as $device)
                            <tr>
                                <td class="fw-semibold">{{ $device->device_name }}</td>
                                <td><span class="badge" style="background: rgba(148,163,184,.25); color:#0f172a; border: 1px solid rgba(148,163,184,.35);">{{ $device->device_token }}</span></td>
                                <td>{{ $device->family ? $device->family->family_name : 'Unassigned' }}</td>
                                <td>
                                    @if($device->family)
                                        <span class="badge bg-primary" style="border-radius:999px;">{{ $device->family->members->count() }} members</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    // Render charts only if Chart.js + canvases exist
    (function () {
        const familiesCanvas = document.getElementById('familiesChart');
        const devicesCanvas = document.getElementById('devicesChart');

        if (!window.Chart) {
            console.warn('Chart.js not found.');
            return;
        }
        if (!familiesCanvas || !devicesCanvas) {
            console.warn('Chart canvases not found.');
            return;
        }

        // Families Chart
        new Chart(familiesCanvas, {
            type: 'bar',
            data: {
                labels: @json($families->pluck('family_name')),
                datasets: [{
                    label: 'Members per Family',
                    data: @json($families->map(fn($f) => $f->members->count())),
                    backgroundColor: '#3498db'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        // Devices Chart
        new Chart(devicesCanvas, {
            type: 'doughnut',
            data: {
                labels: @json($devices->pluck('device_name')),
                datasets: [{
                    data: @json($devices->map(fn($d) => $d->family_id ? 1 : 0)),
                    backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    })();
</script>

@endsection

