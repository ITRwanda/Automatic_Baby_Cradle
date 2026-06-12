@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="mb-1 fw-bold text-dark">Device Registration</h2>
            <div class="text-muted small">Create devices (token generated automatically) and optionally assign them to families.</div>
        </div>
        <span class="badge bg-primary fs-6">Admin</span>
    </div>

    <div class="row g-3">
        {{-- Registration card --}}
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white fw-semibold">
                    + Register Device
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('admin.registerDevice') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Device name</label>
                            <input type="text" name="device_name" class="form-control" required maxlength="255" placeholder="e.g. Cradle Sensor" />
                        </div>

                        <div class="alert alert-info mb-3">
                            Token will be generated automatically.
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            Register Device
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Token/Model list card --}}
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-info text-white fw-semibold">
                    Registered Devices
                </div>
                <div class="card-body">
                    @if($devices->count() === 0)
                        <div class="alert alert-warning mb-0">No devices registered yet.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Device</th>
                                        <th>Token</th>
                                        <th class="text-end">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($devices as $device)
                                        <tr>
                                            <td class="fw-semibold">{{ $device->device_name }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $device->device_token }}</span>
                                            </td>
                                            <td class="text-end">
                                                @if($device->family_id)
                                                    <span class="badge bg-success">Assigned</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Unassigned</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="mt-3 text-muted small">
                        Tip: Use Admin → Reports to assign devices to families.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

