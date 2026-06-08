@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Profile Settings</h2>
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ auth()->user()->name }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ auth()->user()->email }}" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
@endsection
