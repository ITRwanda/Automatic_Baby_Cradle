@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Family Member Dashboard</h2>
    <div class="card shadow">
        <div class="card-body">
            <h5>Notifications</h5>
            @foreach($notifications as $note)
                <p>{{ $note->message }}</p>
            @endforeach
        </div>
    </div>
</div>
@endsection
