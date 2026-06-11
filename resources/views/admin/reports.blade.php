@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-info">System Reports</h2>
    <div class="card shadow">
        <div class="card-header bg-info text-white">Device Assignments</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Device</th>
                        <th>Token</th>
                        <th>Assigned Family</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($devices as $device)
                        <tr>
                            <td>{{ $device->device_name }}</td>
                            <td>{{ $device->device_token }}</td>
                            <td>{{ $device->family ? $device->family->family_name : 'Unassigned' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
