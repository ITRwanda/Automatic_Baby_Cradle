@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="mb-0 fw-bold">Family</h2>
                <span class="badge bg-success">Parent Area</span>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white fw-semibold">
                    Family Summary
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        You can manage your members and view assigned devices here.
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 border bg-light">
                                <div class="text-muted small">Status</div>
                                <div class="fw-bold">Active</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 border bg-light">
                                <div class="text-muted small">Access</div>
                                <div class="fw-bold">Family Parent</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 border bg-light">
                                <div class="text-muted small">Next</div>
                                <div class="fw-bold">Go to Reports</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2 flex-wrap">
                        <a href="{{ route('family.dashboard') }}" class="btn btn-primary">Dashboard</a>
                        <a href="{{ route('family.devices') }}" class="btn btn-outline-primary">Devices</a>
                        <a href="{{ route('family.reports') }}" class="btn btn-outline-warning">Reports</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


