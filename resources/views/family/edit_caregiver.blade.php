@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0 text-primary">Edit Caregiver</h2>
        <a href="{{ route('family.caregivers') }}" class="btn btn-sm btn-outline-secondary">Back</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('family.updateCaregiver', ['user_id' => $member->id]) }}">

                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required value="{{ old('name', $member->name) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required value="{{ old('email', $member->email) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password <span class="text-muted">(optional)</span></label>
                    <input type="password" name="password" class="form-control" minlength="6" placeholder="Leave blank to keep current password">
                </div>

                <button class="btn btn-primary" type="submit">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection

