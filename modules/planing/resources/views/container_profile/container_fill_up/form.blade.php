@extends('skeleton::layout')
@section('title','Container Summaries')

@section('content')
    <div class="padding">
        <div id="container-summary-create"></div>
    </div>
@endsection

@push('script-head')
    <script src="{{ mix('/js/planning/container-fill-up.js') }}"></script>
@endpush
