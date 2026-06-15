@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0 text-primary">Assign Roles</h2>
        <a href="{{ route('family.dashboard') }}" class="btn btn-sm btn-outline-secondary">Back</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold">Role Assignment</div>
        <div class="card-body">
            @forelse($members as $member)
                <div class="border rounded p-3 mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div>
                            <div class="fw-bold">{{ $member->name }}</div>
                            <div class="text-muted">Current: {{ $member->role }}</div>
                        </div>
                        <span class="badge bg-info">User ID: {{ $member->id }}</span>
                    </div>

                    <form method="POST" action="{{ route('family.assignRole') }}" class="row g-2 align-items-end">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $member->id }}">

                        <div class="col-md-8">
                            <label class="form-label">Role</label>
                            <select name="role_id" class="form-select" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" @selected($member->role_id == $role->id)>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <button class="btn btn-primary w-100" type="submit">Assign</button>
                        </div>
                    </form>
                </div>
            @empty
                <p class="text-muted mb-0">No members found.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

