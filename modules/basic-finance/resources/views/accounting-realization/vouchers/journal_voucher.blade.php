@extends('skeleton::layout')
@section('title','Journal Voucher')

@section('content')
    <div class="padding">
        <div id="voucher-create"></div>
    </div>
@endsection

@push('script-head')
    <script src="{{ mix('/js/basic-finance/journal-voucher.js') }}"></script>
@endpush
