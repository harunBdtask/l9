
@extends('commercial::layout')
@section('title','Primary\Master Contract Entry')

@push('style')
    <style>
        .form-control .form-control-sm {
            border: 1px solid #909ac8 !important;
            border-radius: 10px 0 0 0;
            width: 266px !important;
        }

        input, select {
            height: 35px !important;
        }

        .form-control .form-control-sm:focus {
            border: 2px solid #909ac8 !important;
        }

        .req {
            font-size: 1rem;
        }

        .mainForm td, .mainForm th {
            border: none !important;
            padding: .3rem !important;
            text-align: left;
        }

        li.parsley-required {
            color: red;
            list-style: none;
            text-align: left;
        }

        input.parsley-error,
        select.parsley-error,
        textarea.parsley-error {
            border-color: #843534;
            box-shadow: none;
        }


        input.parsley-error:focus,
        select.parsley-error:focus,
        textarea.parsley-error:focus {
            border-color: #843534;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 6px #ce8483
        }

        .remove-po {
            border: none;
            display: block;
            width: 100%;
            background-color: #843534;
            color: whitesmoke;
        }

        .close-po {
            border: none;
            display: block;
            width: 100%;
            background-color: #6cc788;
            color: whitesmoke;
        }

        .error + .select2-container .select2-selection--single {
            border: 1px solid red;
        }

        /*.active {*/
        /*    color: white !important;*/
        /*    background-color: #0275d8 !important;*/
        /*    border-color: #0275d8 !important;*/
        /*}*/

    </style>
@endpush

@section('content')
    <div class="padding" >
        <div id="primary-master-contract-entry">
        </div>
    </div>
@endsection

@push('script-head')
    <script src="{{ asset('/js/commercial/primary-master-contract/primary-master-contract.js') }}"></script>
@endpush



