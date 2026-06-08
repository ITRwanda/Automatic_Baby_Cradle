@extends('layouts.app')

@section('content')
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

<script>
    const familiesChart = new Chart(document.getElementById('familiesChart'), {
        type: 'bar',
        data: {
            labels: @json($familyNames),
            datasets: [{
                label: 'Members per Family',
                data: @json($memberCounts),
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            }]
        }
    });

    const devicesChart = new Chart(document.getElementById('devicesChart'), {
        type: 'pie',
        data: {
            labels: @json($deviceNames),
            datasets: [{
                label: 'Devices Assigned',
                data: @json($deviceCounts),
                backgroundColor: ['#007bff','#28a745','#ffc107','#dc3545']
            }]
        }
    });
</script>
@endsection
