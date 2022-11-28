@extends('skeleton::layout')
@section('title','Pre Costing')
@push('style')
    <style>
        .table > thead > tr > th,
        .table > tbody > tr > td,
        .table > tbody > tr > th,
        .table > tfoot > tr > td,
        .table > tfoot > tr > th {
            border: 1px solid #ddd;
            vertical-align: middle;
        }

        table thead tr th {
            white-space: nowrap;
        }

        .custom-select {
            width: 100%;
            height: 35px !important;
            margin: 0px 0px;
            border: 1px solid rgba(120, 130, 140, 0.2);
        }

        .datepicker {
            border-radius: 0px;
        }

        .input-group-addon {
            border-radius: 0px !important;
        }

        .select2-container--default .select2-selection--single {
            height: 35px !important;
            border-radius: 0px !important;
            border: 1px solid rgba(120, 130, 140, 0.2);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 35px !important;
            /*border: 1px solid rgba(120, 130, 140, 0.2);*/
        }

        .select2-container--default .select2-selection--multiple {
            border-radius: 0px !important;
            /*border: 1px solid rgba(120, 130, 140, 0.2);*/
        }

        .suggetion {
            z-index: 1000;
            position: absolute;
        }

        .append-suggetion {
            padding: 0px;
            margin: 0px;
            width: 340px;
            border: 1px solid #ccc;
            min-height: 50px;
            max-height: 200px;
            overflow-y: scroll;
            background: #fff;
            /*margin-top: 40px;*/
        }

        .append-suggetion li {
            list-style: none;
            display: block;
            cursor: pointer;
            height: 40px;
            padding-left: 15px;
            line-height: 40px;
        }

        .append-suggetion li:hover {
            background: lightgoldenrodyellow;
        }

        .add-btn {
            padding: 3px;
            border-radius: 2px;
        }
    </style>
@endpush
@section('content')
    <div class="padding">
        <div class="box" style="min-height: 610px">
            <div class="box-header btn-info">
                <h2>
                    Pre-Costing
                </h2>
            </div>

            <div class="box-body">
                <div class="box-body" id="pre-costing">

                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('/js/pre-costing.js') }}"></script>
@endsection