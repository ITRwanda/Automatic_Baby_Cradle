@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0 text-primary">Family Members</h2>
        <a href="{{ route('family.dashboard') }}" class="btn btn-sm btn-outline-secondary">Back</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold">Members</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                            <tr>
                                <td class="fw-semibold">{{ $member->name }}</td>
                                <td class="text-muted">{{ $member->email ?? '' }}</td>
                                <td>
                                    {{-- Show only role name (no raw object/json) --}}
                                    <span class="badge bg-info">
                                        {{ is_string($member->role) ? $member->role : ($member->role->name ?? 'member') }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('family.assignDeviceToMember') }}" class="d-inline-block" style="min-width: 280px;">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $member->id }}">
                                        <div class="row g-2 justify-content-end align-items-center">
                                            <div class="col-auto">
                                                <select name="device_id" class="form-select form-select-sm" required>
                                                    <option value="" selected disabled>Select device</option>
                                                    @foreach($devices as $device)
                                                        <option value="{{ $device->id }}">{{ $device->device_name }}{{ $device->user_id ? ' (Assigned)' : '' }}</option>
                                                    @endforeach


                                                </select>
                                            </div>
                                            <div class="col-auto">
                                                <button class="btn btn-sm btn-primary" type="submit">Assign</button>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="mt-2 d-inline-flex gap-2">
                                        <a href="#" class="btn btn-sm btn-outline-secondary" aria-disabled="true" title="Edit member">Edit</a>
                                        <a href="#" class="btn btn-sm btn-outline-danger" aria-disabled="true" title="Delete member">Delete</a>
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="4" class="text-muted p-4 text-center">No family members found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<div class="card shadow-sm mt-4">
        <div class="card-header bg-white fw-bold">Add Member</div>
        <div class="card-body">
            <form method="POST" action="{{ route('family.addMember') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password <span class="text-muted">(optional)</span></label>
                    <input type="password" name="password" class="form-control" minlength="6">
                </div>

                <button class="btn btn-primary" type="submit">Add</button>
            </form>
        </div>
    </div>
</div>
@endsection

