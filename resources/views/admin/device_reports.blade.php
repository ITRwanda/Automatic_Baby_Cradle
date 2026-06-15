@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h2 class="mb-0 fw-bold">Device Reports (Admin)</h2>
        <span class="text-muted small">Filter by family, dates, and search. Manage devices from here.</span>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-info text-white fw-semibold">Report Filters</div>
        <div class="card-body">
            <form class="row g-3" method="GET" action="{{ route('admin.deviceReports') }}">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Family</label>
                    <select name="family_id" class="form-select">
                        <option value="">All (assigned + unassigned)</option>
                        @foreach(($families ?? collect()) as $family)
                            <option value="{{ $family->id }}" {{ request('family_id') == $family->id ? 'selected' : '' }}>
                                {{ $family->family_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Search</label>
                    <input type="text" name="q" class="form-control" placeholder="Device name or token" value="{{ request('q') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">From</label>
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">To</label>
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>

                <div class="col-md-12 d-flex gap-2 justify-content-end mt-2">
                    <button class="btn btn-primary" type="submit">Apply</button>
                    <a href="{{ route('admin.deviceReports') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-secondary text-white fw-semibold">Devices</div>
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
                                <th>Created</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($devices as $device)
                                <tr>
                                    <td class="fw-semibold">{{ $device->device_name }}</td>
                                    <td class="text-muted">{{ $device->device_token }}</td>
                                    <td>{{ $device->family ? $device->family->family_name : 'Unassigned' }}</td>
                                    <td class="text-muted">{{ $device->created_at?->format('Y-m-d') }}</td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2 flex-wrap">
                                            {{-- Modify device --}}
                                            <button
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#drEditDevice-{{ $device->id }}"
                                            >
                                                Modify
                                            </button>

                                            {{-- Delete device --}}
                                            <form
                                                method="POST"
                                                action="{{ route('admin.deleteDevice', $device->id) }}"
                                                onsubmit="return confirm('Delete device "{{ addslashes($device->device_name) }}"?')"
                                            >
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </div>

                                        {{-- Modify device modal --}}
                                        <div class="modal fade" id="drEditDevice-{{ $device->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('admin.updateDevice', $device->id) }}">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Modify Device</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Device name</label>
                                                                <input
                                                                    type="text"
                                                                    name="device_name"
                                                                    class="form-control"
                                                                    required
                                                                    maxlength="255"
                                                                    value="{{ $device->device_name }}"
                                                                >
                                                            </div>
                                                            <div class="alert alert-info mb-0">
                                                                Token is fixed: <b>{{ $device->device_token }}</b>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary fw-bold">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
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

