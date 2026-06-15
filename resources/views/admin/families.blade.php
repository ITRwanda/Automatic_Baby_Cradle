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

        {{-- Debug: show how many families are loaded (temporary) --}}
        {{-- <div class="alert alert-primary mb-3">Loaded families: {{ ($families ?? collect())->count() }}</div> --}}
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

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

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
                            <label class="form-label fw-semibold">Family parent name</label>
                            <input
                                type="text"
                                name="parent_name"
                                class="form-control"
                                required
                                maxlength="255"
                                placeholder="e.g. John Parent"
                                value="{{ old('parent_name') }}"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Family parent email</label>
                            <input
                                type="email"
                                name="parent_email"
                                class="form-control"
                                required
                                placeholder="parent@example.com"
                                value="{{ old('parent_email') }}"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Family parent password</label>
                            <input
                                type="password"
                                name="parent_password"
                                class="form-control"
                                required
                                minlength="6"
                                placeholder="Enter password"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Confirm password</label>
                            <input
                                type="password"
                                name="parent_password_confirmation"
                                class="form-control"
                                required
                                minlength="6"
                                placeholder="Confirm password"
                            >
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
            @include('admin._families_table_fixed')
        </div>

    </div>
</div>
@endsection

