@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Family Devices</h2>
    <div class="row">
        @foreach($devices as $device)
            <div class="col-md-6 mb-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title">{{ $device->device_name }}</h5>
                        <p><strong>Token:</strong> {{ $device->device_token }}</p>
                        <p><strong>Status:</strong> {{ $device->family_id ? 'Assigned' : 'Unassigned' }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
