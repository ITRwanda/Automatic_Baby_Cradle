@php
    // Shared helper for search attribute
    $familiesCollection = $families ?? collect();
    $parentsCollection = $parents ?? collect();
    $globalUnassigned = 0;
    try {
        $globalUnassigned = ($allDevices ?? collect())->whereNull('family_id')->count();
    } catch (\Throwable $e) {
        $globalUnassigned = 0;
    }
@endphp

<div class="card shadow-sm border-0">
    <div class="card-header bg-info text-white fw-semibold d-flex align-items-center justify-content-between gap-2">
        <span>Existing Families</span>
        <span class="badge bg-dark">Unassigned devices: {{ $globalUnassigned }}</span>
    </div>

    <div class="card-body">
        @if($familiesCollection->count() === 0)
            <div class="alert alert-warning mb-0">No families found.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle table-sm">
                    <thead class="table-light">
                        <tr>
                            <th colspan="4" class="pt-0 pb-2">
                                <input
                                    id="familiesSearch"
                                    type="text"
                                    class="form-control form-control-sm"
                                    placeholder="Search families by name or parent..."
                                />
                            </th>
                        </tr>
                        <tr>
                            <th style="min-width: 220px;">Family</th>
                            <th style="width: 110px;">Members</th>
                            <th style="width: 220px;">Devices</th>
                            <th class="text-end" style="width: 320px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($familiesCollection as $family)
                            @php
                                // Ensure nested modals do not break table HTML
                            @endphp

                            @php
                                $searchParent = optional($family->parent)->name ?? '';
                                $searchParentEmail = optional($family->parent)->email ?? '';
                                $assignedCount = ($family->devices ?? collect())->count();
                                $parentName = optional($family->parent)->name ?? '—';
                            @endphp

                            <tr data-family-search="{{ strtolower($family->family_name.' '.$searchParent.' '.$searchParentEmail) }}">
                                
                                @php
                                    $editModalId = 'editFamilyModal-' . $family->id;
                                    $assignModalId = 'assignDevicesModal-' . $family->id;
                                @endphp

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
                                    </div>
                                </td>

                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                                        <button
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editFamilyModal-{{ $family->id }}"
                                        >
                                            Modify
                                        </button>

                                        <button
                                            class="btn btn-sm btn-outline-success"
                                            data-bs-toggle="modal"
                                            data-bs-target="#assignDevicesModal-{{ $family->id }}"
                                        >
                                            Assign devices
                                        </button>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.deleteFamily', $family->id) }}"
                                            onsubmit="return confirm('Delete family "{{ addslashes($family->family_name) }}"? This will also delete its devices.')"
                                        >
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>


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
                                                            @foreach($parentsCollection as $parent)
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
                                                                        <button
                                                                            class="btn btn-sm btn-outline-primary"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#editDeviceModal-{{ $device->id }}"
                                                                        >
                                                                            Modify
                                                                        </button>

                                                                        <form
                                                                            method="POST"
                                                                            action="{{ route('admin.unassignDevice') }}"
                                                                            onsubmit="return confirm('Unassign device "{{ addslashes($device->device_name) }}" from this family?')"
                                                                        >
                                                                            @csrf
                                                                            <input type="hidden" name="device_id" value="{{ $device->id }}">
                                                                            <button type="submit" class="btn btn-sm btn-outline-secondary">Unassign (family)</button>
                                                                        </form>

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
                                                                                <div class="alert alert-info mb-0">Token is fixed: <b>{{ $device->device_token }}</b></div>
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

            <script>
                (function () {
                    const input = document.getElementById('familiesSearch');
                    if (!input) return;

                    const rows = Array.from(document.querySelectorAll('tbody tr[data-family-search]'));
                    const handler = function () {
                        const q = (input.value || '').trim().toLowerCase();
                        rows.forEach(row => {
                            const text = (row.getAttribute('data-family-search') || '').toLowerCase();
                            row.style.display = (!q || text.includes(q)) ? '' : 'none';
                        });
                    };

                    input.addEventListener('input', handler);
                    handler();
                })();
            </script>
        @endif
    </div>
</div>

