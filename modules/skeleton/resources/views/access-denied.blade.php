@extends('skeleton::layout')
@section('content')
    <div>
        <div class="text-center pos-rlt p-y-md">
            <h2 class="text-shadow m-a-0 text-black text-2x">
                <span class="text-2x font-bold block m-t-lg">Access Denied</span>
            </h2>
            <p class="h5 m-y-lg text-u-c font-bold text-black">Sorry! You don’t have permission to access this Page</p>
            <p class="m-y-lg text-u-c text-black">Please purchase full version of 'goRMG-ERP' to avail this service</p>
            <a class="md-btn indigo-A400 md-raised p-x-md" href="{{ URL::previous() }}">
                <span class="text-white">Back</span>
            </a>
        </div>
    </div>
@endsection
