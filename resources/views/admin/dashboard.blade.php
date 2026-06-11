@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-primary fw-bold">Admin Dashboard</h2>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow text-center">
                <div class="card-body bg-primary text-white">
                    <h5>Total Families</h5>
                    <h3>{{ $families->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow text-center">
                <div class="card-body bg-success text-white">
                    <h5>Total Devices</h5>
                    <h3>{{ $devices->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow text-center">
                <div class="card-body bg-info text-white">
                    <h5>Total Users</h5>
                    <h3>{{ $users->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow text-center">
                <div class="card-body bg-warning text-dark">
                    <h5>Reports</h5>
                    <h3>{{ $reports->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">Families Overview</div>
                <div class="card-body">
                    <canvas id="familiesChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">Devices Overview</div>
                <div class="card-body">
                    <canvas id="devicesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="card shadow mt-4">
        <div class="card-header bg-info text-white">Device Assignments</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
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
                            <td>{{ $device->device_name }}</td>
                            <td>{{ $device->device_token }}</td>
                            <td>{{ $device->family ? $device->family->family_name : 'Unassigned' }}</td>
                            <td>
                                @if($device->family)
                                    {{ $device->family->members->count() }} members
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Families Chart
    new Chart(document.getElementById('familiesChart'), {
        type: 'bar',
        data: {
            labels: @json($families->pluck('family_name')),
            datasets: [{
                label: 'Members per Family',
                data: @json($families->map(fn($f)=>$f->members->count())),
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            }]
        }
    });

    // Devices Chart
    new Chart(document.getElementById('devicesChart'), {
        type: 'pie',
        data: {
            labels: @json($devices->pluck('device_name')),
            datasets: [{
                data: @json($devices->map(fn($d)=>$d->family_id ? 1 : 0)),
                backgroundColor: ['#007bff','#28a745','#ffc107','#dc3545']
            }]
        }
    });
</script>
@endsection
