@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0 fw-bold">Family Reports</h2>
        <span class="badge bg-info">Assigned Devices</span>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-warning text-dark fw-semibold">Devices</div>
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
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($devices as $device)
                                <tr>
                                    <td class="fw-semibold">{{ $device->device_name }}</td>
                                    <td><span class="badge bg-secondary">{{ $device->device_token }}</span></td>
                                    <td class="text-end">
                                        {{-- Placeholder actions; real backend not implemented yet --}}
                                        <div class="d-inline-flex gap-2">
                                            <a href="#" class="btn btn-sm btn-outline-primary" aria-disabled="true">Assign</a>
                                            <a href="#" class="btn btn-sm btn-outline-danger" aria-disabled="true">Drop</a>
                                        </div>
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

