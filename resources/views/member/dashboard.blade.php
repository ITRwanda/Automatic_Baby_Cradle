@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-warning fw-bold">Family Member Dashboard</h2>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow text-center">
                <div class="card-body bg-info text-white">
                    <h5>Assigned Devices</h5>
                    <h3>{{ $devices->count() ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow text-center">
                <div class="card-body bg-warning text-dark">
                    <h5>Notifications</h5>
                    <h3>{{ $notifications->count() ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    <div class="card shadow mb-4">
        <div class="card-header bg-warning text-dark">Recent Notifications</div>
        <div class="card-body">
            @forelse($notifications as $note)
                <div class="alert alert-info mb-2">
                    <strong>{{ $note->created_at->format('d M Y H:i') }}</strong> - {{ $note->message }}
                </div>
            @empty
                <p class="text-muted">No notifications yet.</p>
            @endforelse
        </div>
    </div>

    <!-- Assigned Devices -->
    <div class="card shadow mb-4">
        <div class="card-header bg-info text-white">My Devices</div>
        <div class="card-body">
            @forelse($devices as $device)
                <p><strong>{{ $device->device_name }}</strong> 
                   <span class="badge bg-secondary">Token: {{ $device->device_token }}</span></p>
            @empty
                <p class="text-muted">No devices assigned to you yet.</p>
            @endforelse
        </div>
    </div>

    <!-- Profile Settings -->
    <div class="card shadow">
        <div class="card-header bg-secondary text-white">Profile</div>
        <div class="card-body">
            <a href="{{ route('profile.settings') }}" class="btn btn-outline-primary">Update Profile</a>
        </div>
    </div>
</div>
@endsection
