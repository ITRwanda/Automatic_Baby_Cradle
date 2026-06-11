@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Member Reports</h2>
    <div class="card shadow">
        <div class="card-body">
            <h5>Reports for Your Devices</h5>
            <ul>
                @foreach($devices as $device)
                    <li>
                        {{ $device->device_name }} (Token: {{ $device->device_token }})
                        {{-- Later: attach IoT activity logs here --}}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
