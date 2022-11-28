@extends('skeleton::layout')
@section('title','Bank Account')

@section('content')
    <div class="padding">
        <div id="bank-account-create"></div>
    </div>
@endsection

@push('script-head')
    <script src="{{ mix('/js/finance/bank-account.js') }}"></script>
@endpush
