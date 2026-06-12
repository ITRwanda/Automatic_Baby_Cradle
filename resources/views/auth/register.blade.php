@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center fw-bold">
                    <i class="bi bi-person-plus"></i> Register
                </div>

                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Full name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter your full name" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email address</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                        </div>


                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter a password" required minlength="6">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Confirm password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm your password" required minlength="6">
                        </div>

                        <input type="hidden" name="register_as" value="admin">

                        <div class="alert alert-info mb-3">
                            <strong>Note:</strong> Family Parents and Family Members are created by the Admin.
                        </div>


                        <button type="submit" class="btn btn-primary w-100 fw-bold mt-2">Create account</button>
                    </form>
                </div>

                <div class="card-footer text-center bg-light">
                    <small class="text-muted">Already have an account? <a href="{{ route('login') }}" class="text-decoration-none">Login</a></small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

