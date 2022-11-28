@extends('skeleton::layout')
@section('title','Bank')

@section('content')
    <div class="padding">
        <div id="bank-create"></div>
    </div>
@endsection

@push('script-head')
    <script src="{{ mix('/js/basic-finance/bank.js') }}"></script>
@endpush

