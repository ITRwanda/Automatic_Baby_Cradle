@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Family Member Dashboard</h2>
    <div class="card shadow">
        <div class="card-header bg-warning text-dark">Notifications</div>
        <div class="card-body">
            @forelse($notifications as $note)
                <p>{{ $note->message }}</p>
            @empty
                <p>No notifications yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
