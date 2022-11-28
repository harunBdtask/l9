@extends('skeleton::layout')
@section('title','Container Planning Entry')

@section('content')
    <div class="padding">
        <div id="container-profile-create"></div>
    </div>
@endsection

@push('script-head')
    <script src="{{ mix('/js/planning/container-profile.js') }}"></script>
@endpush
