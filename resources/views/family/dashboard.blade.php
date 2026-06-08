@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Family Parent Dashboard</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h5>Assigned Devices</h5>
                    @foreach($devices as $device)
                        <p>{{ $device->device_name }} (Token: {{ $device->device_token }})</p>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h5>Family Members</h5>
                    <a href="{{ route('family.members') }}" class="btn btn-outline-primary">Add Members</a>
                    <a href="{{ route('family.roles') }}" class="btn btn-outline-secondary">Assign Roles</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
