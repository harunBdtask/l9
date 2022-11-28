@extends('skeleton::layout')
@section('title', 'Hide Fields Variable')
@push('style')
    <style>
        .select-option {
            min-height: 2.375rem !important;
        }
    </style>
@endpush
@section('content')
<div class="padding">
    <div class="col-lg-12 col-md-12">
        <div class="box">
            <div class="box-header">
                <h2>Hide Fields Variable</h2>
            </div>
            <div class="box-body b-t">
                <div id="setting">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ url('/js/sys/hide-fields.js') }}"></script>
@endsection
