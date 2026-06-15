@extends('layouts.app')

@section('content')
    {{-- New Admin Reports UI with filters + export --}}
    @include('admin.reports_filtered')
@endsection

