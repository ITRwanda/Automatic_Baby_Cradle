@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0 fw-bold">Family Reports</h2>
        <span class="badge bg-info">Assigned Devices</span>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-warning text-dark fw-semibold">Devices assigned to this family</div>
        <div class="card-body">
            @if(($devices ?? collect())->count() === 0)
                <div class="alert alert-warning mb-0">No devices assigned to this family.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Device</th>
                                <th>Token</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($devices as $device)
                                <tr>
                                    <td class="fw-semibold">{{ $device->device_name }}</td>
                                    <td><span class="badge bg-secondary">{{ $device->device_token }}</span></td>
                                    <td>
                                        @if($device->user_id)
                                            @php($assignedMember = \App\Models\User::find($device->user_id))
                                            <span class="badge bg-success">Assigned: {{ $assignedMember?->name ?? 'Unknown' }}</span>
                                        @else
                                            <span class="text-muted">Unassigned</span>
                                        @endif
                                    </td>
            <td class="text-end">
                                        <form method="POST" action="{{ route('family.unassignDeviceFromMember') }}" onsubmit="return confirm('Unassign this device from the member?');">
                                            @csrf
                                            <input type="hidden" name="device_id" value="{{ $device->id }}">
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Unassign</button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection

