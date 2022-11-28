@extends('skeleton::layout')
@section('title','Time & Action')
@section('content')
    <div id="time-and-action"></div>
@endsection
@push('script-head')
    <script src="{{ mix('/js/time-and-action/time-and-action.js') }}"></script>
@endpush

