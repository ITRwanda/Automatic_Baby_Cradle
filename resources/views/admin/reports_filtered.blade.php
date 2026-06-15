@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h2 class="mb-0 fw-bold">Admin Reports</h2>
        <div class="text-muted small">Filter devices by family and export</div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-info text-white fw-semibold">Report Filters</div>
        <div class="card-body">

            <form class="row g-3" method="GET" action="{{ route('admin.reports') }}">

                <div class="col-md-5">
                    <label class="form-label fw-semibold">Family</label>
                    <select name="family_id" class="form-select">
                        <option value="">All families</option>
                        @foreach(($families ?? collect()) as $family)
                            <option value="{{ $family->id }}" {{ request('family_id') == $family->id ? 'selected' : '' }}>
                                {{ $family->family_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Search device</label>
                    <input type="text" name="q" class="form-control" placeholder="Device name or token" value="{{ request('q') }}">
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-primary w-100" type="submit">Apply</button>
                </div>
            </form>

            <div class="mt-3 d-flex flex-wrap gap-2">

            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-secondary text-white fw-semibold">Device Report</div>
        <div class="card-body">
            @if(($devices ?? collect())->count() === 0)
                <div class="alert alert-warning mb-0">No devices found.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Device</th>
                                <th>Token</th>
                                <th>Family</th>
                                <th>Assigned?</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($devices as $device)
                                <tr>
                                    <td class="fw-semibold">{{ $device->device_name }}</td>
                                    <td class="text-muted">{{ $device->device_token }}</td>
                                    <td>{{ $device->family ? $device->family->family_name : 'Unassigned' }}</td>
                                    <td>
                                        @if($device->family)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
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

