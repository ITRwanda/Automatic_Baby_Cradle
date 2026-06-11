@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-info fw-bold">Family Parent Dashboard</h2>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow text-center">
                <div class="card-body bg-info text-white">
                    <h5>Assigned Devices</h5>
                    <h3>{{ $devices->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow text-center">
                <div class="card-body bg-secondary text-white">
                    <h5>Family Members</h5>
                    <h3>{{ $members->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow text-center">
                <div class="card-body bg-warning text-dark">
                    <h5>Alerts</h5>
                    <h3>{{ $alerts->count() ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Devices -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">Assigned Devices</div>
                <div class="card-body">
                    @forelse($devices as $device)
                        <p><strong>{{ $device->device_name }}</strong> 
                           <span class="badge bg-secondary">Token: {{ $device->device_token }}</span></p>
                    @empty
                        <p>No devices assigned yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Family Members -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-secondary text-white">Family Members</div>
                <div class="card-body">
                    <a href="{{ route('family.members') }}" class="btn btn-outline-primary mb-2">Add Members</a>
                    <a href="{{ route('family.roles') }}" class="btn btn-outline-dark mb-2">Assign Roles</a>
                    <hr>
                    @forelse($members as $member)
                        <p>{{ $member->name }} - <span class="badge bg-info">{{ $member->role }}</span></p>
                    @empty
                        <p>No members added yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Reports -->
    <div class="card shadow mt-4">
        <div class="card-header bg-warning text-dark">Device Reports</div>
        <div class="card-body">
            <canvas id="reportsChart"></canvas>
        </div>
    </div>
</div>

<script>
    // Reports Chart (example: alerts per device)
    new Chart(document.getElementById('reportsChart'), {
        type: 'line',
        data: {
            labels: @json($devices->pluck('device_name')),
            datasets: [{
                label: 'Alerts per Device',
                data: @json($devices->map(fn($d)=>$d->alerts->count() ?? 0)),
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255,193,7,0.3)',
                fill: true,
                tension: 0.3
            }]
        }
    });
</script>
@endsection
