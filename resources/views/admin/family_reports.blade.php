@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h2 class="mb-0 fw-bold">Family Reports (Admin)</h2>
        <span class="text-muted small">Filter by dates and search. Manage families from here.</span>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-info text-white fw-semibold">Report Filters</div>
        <div class="card-body">
            <form class="row g-3" method="GET" action="{{ route('admin.familyReports') }}">
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Search</label>
                    <input type="text" name="q" class="form-control" placeholder="Family name or parent name/email" value="{{ request('q') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">From</label>
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">To</label>
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>

                <div class="col-md-12 d-flex gap-2 justify-content-end mt-2">
                    <button class="btn btn-primary" type="submit">Apply</button>
                    <a href="{{ route('admin.familyReports') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-secondary text-white fw-semibold">Families</div>
        <div class="card-body">
            @if(($families ?? collect())->count() === 0)
                <div class="alert alert-warning mb-0">No families found.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Family</th>
                                <th>Parent</th>
                                <th>Members</th>
                                <th>Devices</th>
                                <th>Created</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($families as $family)
                                @php
                                    $membersCount = $family->members->count();
                                    $devicesCount = $family->devices->count();
                                    $parentName = optional($family->parent)->name;
                                    $parentEmail = optional($family->parent)->email;
                                @endphp
                                <tr>
                                    <td class="fw-semibold">{{ $family->family_name }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $parentName ?? '—' }}</div>
                                        <div class="text-muted small">{{ $parentEmail ?? '' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $membersCount }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $devicesCount }}</span>
                                    </td>
                                    <td class="text-muted">{{ $family->created_at?->format('Y-m-d') }}</td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2 flex-wrap">
                                            {{-- Modify family --}}
                                            <button
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#frEditFamily-{{ $family->id }}"
                                            >
                                                Modify
                                            </button>

                                            {{-- Delete family --}}
                                            <form
                                                method="POST"
                                                action="{{ route('admin.deleteFamily', $family->id) }}"
                                                onsubmit="return confirm('Delete family "{{ addslashes($family->family_name) }}"? This will also delete its devices.')"
                                            >
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </div>

                                        {{-- Modify family modal --}}
                                        <div class="modal fade" id="frEditFamily-{{ $family->id }}" tabindex="-1" aria-hidden="true">
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

