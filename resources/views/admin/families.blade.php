@extends('layouts.app')

@section('content')
<div class="container-fluid mt-3">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h2 class="mb-0 fw-bold text-dark">Families Management</h2>
        <span class="badge bg-primary fs-6">Admin</span>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Left: Create family --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white fw-semibold">
                    + Register New Family
                </div>

                <div class="card-body">
                    <p class="text-muted small mb-3">
                        Admin registers a family and assigns a family parent.
                    </p>

                    <form method="POST" action="{{ route('admin.createFamily') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Family name</label>
                            <input
                                type="text"
                                name="family_name"
                                class="form-control"
                                required
                                maxlength="255"
                                placeholder="e.g. Johnson Family"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Family parent</label>
                            <select name="parent_id" class="form-select" required>
                                <option value="">Select parent</option>
                                @foreach(($parents ?? collect()) as $parent)
                                    <option value="{{ $parent->id }}">
                                        {{ $parent->name }} ({{ $parent->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            Create Family
                        </button>
                    </form>

                    <hr class="my-4"/>

                    <div class="alert alert-info mb-0">
                        Tip: After creating a family, click <b>Assign devices</b> on the right.
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Families list --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white fw-semibold">
                    Existing Families
                </div>

                <div class="card-body">
                    @if(($families ?? collect())->count() === 0)
                        <div class="alert alert-warning mb-0">No families found.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width: 220px;">Family</th>
                                        <th style="width: 110px;">Members</th>
                                        <th style="width: 220px;">Devices</th>
                                        <th class="text-end" style="width: 320px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($families as $family)
                                    @php
                                        $assignedDevices = $family->devices ?? collect();
                                        $assignedCount = $assignedDevices->count();

                                        $unassignedCount = 0;
                                        try {
                                            $unassignedCount = ($allDevices ?? collect())->whereNull('family_id')->count();
                                        } catch (\Throwable $e) {
                                            $unassignedCount = 0;
                                        }

                                        $parentName = optional($family->parent)->name ?? '—';
                                    @endphp

                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $family->family_name }}</div>
                                            <div class="text-muted small">Parent: {{ $parentName }}</div>
                                        </td>

                                        <td>
                                            <span class="badge bg-primary">{{ ($family->members ?? collect())->count() }}</span>
                                        </td>

                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <span class="badge bg-success">Assigned: {{ $assignedCount }}</span>
                                                <span class="badge bg-secondary">Unassigned: {{ $unassignedCount }}</span>
                                            </div>
                                        </td>

                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-2 flex-wrap">
                                                {{-- Modify family --}}
                                                <button
                                                    class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editFamilyModal-{{ $family->id }}"
                                                >
                                                    Modify
                                                </button>

                                                {{-- Assign devices --}}
                                                <button
                                                    class="btn btn-sm btn-outline-success"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#assignDevicesModal-{{ $family->id }}"
                                                >
                                                    Assign devices
                                                </button>

                                                {{-- Delete family --}}
                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.deleteFamily', $family->id) }}"
                                                    onsubmit="return confirm('Delete family "{{ addslashes($family->family_name) }}"? This will also delete its devices.')"
                                                >
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Edit family modal --}}
                                    <div class="modal fade" id="editFamilyModal-{{ $family->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('admin.updateFamily', $family->id) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Modify Family</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Family name</label>
                                                                <input
                                                                    type="text"
                                                                    name="family_name"
                                                                    class="form-control"
                                                                    required
                                                                    maxlength="255"
                                                                    value="{{ $family->family_name }}"
                                                                >
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Family parent</label>
                                                                <select name="parent_id" class="form-select" required>
                                                                    @foreach(($parents ?? collect()) as $parent)
                                                                        <option
                                                                            value="{{ $parent->id }}"
                                                                            {{ $family->parent_id == $parent->id ? 'selected' : '' }}
                                                                        >
                                                                            {{ $parent->name }} ({{ $parent->email }})
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary fw-bold">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Assign devices modal --}}
                                    <div class="modal fade" id="assignDevicesModal-{{ $family->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <div>
                                                        <h5 class="modal-title">Assign devices to: {{ $family->family_name }}</h5>
                                                        <div class="text-muted small">Only unassigned devices are shown.</div>
                                                    </div>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    @php
                                                        $unassignedDevices = ($allDevices ?? collect())->whereNull('family_id');
                                                        $assignedDevicesNow = $family->devices ?? collect();
                                                    @endphp

                                                    {{-- Unassigned devices --}}
                                                    @if($unassignedDevices->count() === 0)
                                                        <div class="alert alert-warning mb-0">No unassigned devices available.</div>
                                                    @else
                                                        <div class="mb-4">
                                                            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                                                <h6 class="mb-0 fw-bold">Unassigned devices</h6>
                                                                <span class="text-muted small">{{ $unassignedDevices->count() }} available</span>
                                                            </div>

                                                            <div class="table-responsive">
                                                                <table class="table table-sm table-hover align-middle">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th>Device</th>
                                                                            <th>Token</th>
                                                                            <th class="text-end">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($unassignedDevices as $device)
                                                                        <tr>
                                                                            <td class="fw-semibold">{{ $device->device_name }}</td>
                                                                            <td><span class="badge bg-secondary">{{ $device->device_token }}</span></td>
                                                                            <td class="text-end">
                                                                                <form method="POST" action="{{ route('admin.assignDevice') }}">
                                                                                    @csrf
                                                                                    <input type="hidden" name="device_id" value="{{ $device->id }}">
                                                                                    <input type="hidden" name="family_id" value="{{ $family->id }}">
                                                                                    <button type="submit" class="btn btn-sm btn-success fw-bold">Assign</button>
                                                                                </form>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <hr class="my-4"/>

                                                    {{-- Assigned devices management --}}
                                                    <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                                        <h6 class="mb-0 fw-bold">Assigned devices (manage)</h6>
                                                        <span class="text-muted small">{{ $assignedDevicesNow->count() }} assigned</span>
                                                    </div>

                                                    @if($assignedDevicesNow->count() === 0)
                                                        <div class="alert alert-secondary mb-0">No devices assigned yet.</div>
                                                    @else
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-hover align-middle">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>Device</th>
                                                                        <th>Token</th>
                                                                        <th class="text-end">Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($assignedDevicesNow as $device)
                                                                    <tr>
                                                                        <td class="fw-semibold">{{ $device->device_name }}</td>
                                                                        <td><span class="badge bg-secondary">{{ $device->device_token }}</span></td>
                                                                        <td class="text-end">
                                                                            <div class="d-flex justify-content-end gap-2 flex-wrap">
                                                                                {{-- Modify device --}}
                                                                                <button
                                                                                    class="btn btn-sm btn-outline-primary"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#editDeviceModal-{{ $device->id }}"
                                                                                >
                                                                                    Modify
                                                                                </button>

                                                                                    {{-- Unassign device from family_id (existing) --}}
                                                                                    <form
                                                                                        method="POST"
                                                                                        action="{{ route('admin.unassignDevice') }}"
                                                                                        onsubmit="return confirm('Unassign device "{{ addslashes($device->device_name) }}" from this family?')"
                                                                                    >
                                                                                        @csrf
                                                                                        <input type="hidden" name="device_id" value="{{ $device->id }}">
                                                                                        <button type="submit" class="btn btn-sm btn-outline-secondary">Unassign (family)</button>
                                                                                    </form>

                                                                                    {{-- Unassign device from family_parent user_id (sync with family-parent side) --}}
                                                                                    <form
                                                                                        method="POST"
                                                                                        action="{{ route('admin.unassignDeviceFromFamilyParent') }}"
                                                                                        onsubmit="return confirm('Unassign device "{{ addslashes($device->device_name) }}" from family parent?')"
                                                                                    >
                                                                                        @csrf
                                                                                        <input type="hidden" name="device_id" value="{{ $device->id }}">
                                                                                        <input type="hidden" name="family_id" value="{{ $family->id }}">
                                                                                    <button type="submit" class="btn btn-sm btn-outline-warning">Unassign (parent)</button>

                                                                                    </form>


                                                                                {{-- Delete device --}}
                                                                                <form
                                                                                    method="POST"
                                                                                    action="{{ route('admin.deleteDevice', $device->id) }}"
                                                                                    onsubmit="return confirm('Delete device "{{ addslashes($device->device_name) }}"? This cannot be undone.')"
                                                                                >
                                                                                    @csrf
                                                                                    <button type="submit" class="btn btn-outline-danger">Delete</button>
                                                                                </form>
                                                                            </div>
                                                                        </td>
                                                                    </tr>

                                                                    {{-- Edit device modal --}}
                                                                    <div class="modal fade" id="editDeviceModal-{{ $device->id }}" tabindex="-1" aria-hidden="true">
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
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

