@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Families</h2>
    <div class="row">
        @foreach($families as $family)
            <div class="col-md-6 mb-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title">{{ $family->family_name }}</h5>
                        <p><strong>ID:</strong> {{ $family->id }}</p>
                        <h6>Members:</h6>
                        <ul>
                            @foreach($family->members as $member)
                                <li>{{ $member->name }} ({{ $member->email }})</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
