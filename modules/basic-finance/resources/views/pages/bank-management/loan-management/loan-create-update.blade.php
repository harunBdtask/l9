@extends('skeleton::layout')
@section('title','Loan Account Create')

@section('content')
    <div class="padding">
        <div id="loan-account"></div>
    </div>
@endsection

@push('script-head')
    <script src="{{ mix('/js/basic-finance/loan-account.js') }}"></script>
@endpush

