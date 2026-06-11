@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Member Dashboard</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-3">
                <div class="card-body">
                    <h5>Assigned Devices</h5>
                    <ul>
                        @foreach($devices as $device)
                            <li>{{ $device->device_name }} (Token: {{ $device->device_token }})</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow mb-3">
                <div class="card-body">
                    <h5>Notifications</h5>
                    <ul>
                        @foreach($notifications as $note)
                            <li>{{ $note->message }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
