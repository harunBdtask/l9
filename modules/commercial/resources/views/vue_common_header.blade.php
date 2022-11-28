<meta charset="utf-8"/>
<title>goRMG | An Ultimate ERP Solutions For Garments</title>
<meta name="description" content="RMG, ERP, Production Tracking"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">

{{--<link rel="shortcut icon" sizes="196x196" href="{{ asset('flatkit/assets/images/gormg_fav_ico.png') }}">--}}

<!-- style -->
<link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/animate.css/animate.min.css') }}"
      type="text/css"/>
<link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/glyphicons/glyphicons.css') }}"
      type="text/css"/>
<link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/font-awesome/css/font-awesome.min.css') }}"
      type="text/css"/>
<link rel="stylesheet"
      href="{{ asset('modules/skeleton/flatkit/assets/material-design-icons/material-design-icons.css') }}"
      type="text/css"/>
<link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/bootstrap/dist/css/bootstrap.min.css') }}"
      type="text/css"/>
<link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/styles/app.css') }}" type="text/css"/>
<link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/styles/font.css') }}" type="text/css"/>

<!-- libs -->
<link rel="stylesheet" href="{{ asset('modules/skeleton/lib/datepicker/datepicker3.css')}}" type="text/css"/>
<link rel="stylesheet" href="{{ asset('modules/skeleton/lib/select2/select2.min.css') }}" type="text/css"/>
<link rel="stylesheet" href="{{ asset('modules/skeleton/lib/morris-charts/morris.css')}}">
<link rel="stylesheet" href="{{ asset('modules/skeleton/lib/toaster/toaster.css')}}">
<link rel="stylesheet" href="{{ asset('modules/skeleton/css/custom.css') }}" type="text/css"/>
<style>
    .reportEntryTable {
        margin-bottom: 1rem;
        width: 100%;
        max-width: 100%;
    }

    .rounded-div {
        box-shadow: -1px 7px 9px grey;
        border-radius: 5px;
        margin-bottom: 35px;
        min-height: 35px;
    }

    .rounded-table {
        margin-top: 2% !important;
        font-size: 11px !important;
        table-layout: fixed !important;
        width: 100% !important;
        overflow-x: auto;
    }

    .rounded-font {
        font-size: 11px !important;
        font-weight: 500 !important;
    }

    .reportEntryTable thead,
    .reportEntryTable tbody,
    .reportEntryTable th {
        padding: 3px;
        font-size: 12px;
        text-align: center;
    }

    .reportEntryTable th,
    .reportEntryTable td {
        border: 1px solid transparent;
    }

    .reportTable th,
    .reportTable td {
        border: 1px solid #0a1029 !important;
    }

    input {
        font-size: 12px !important;
    }

    #loader {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(226, 226, 226, 0.75) no-repeat center center;
        width: 100%;
        z-index: 1000;
    }

    .spin-loader {
        position: relative;
        top: 46%;
        left: 0;
    }

    .form-buttons {
        position: fixed;
        z-index: 2000;
        top: 10px;
        right: 10px;
    }

    .text-align-right {
        text-align: right !important;
    }

    .text-align-left {
        text-align: left !important;
    }

    .custom-input-field {
        color: #000208;
        text-align: center !important;
    }

    .custom-color {
        background-color: #5bc0de;
        color: #fbfdff;
    }

    .mx-input {
        display: inline-block;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        width: 100%;
        height: 34px;
        padding: 6px 30px 6px 10px;
        font-size: 14px;
        line-height: 1.4;
        /* color: #555; */
        background-color: white;
        border: none !important;
        /* border-radius: 4px; */
        -webkit-box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
        /* box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%); */
    }

    .mx_input_custom {
        border: 1px solid #909ac8 !important;
        border-radius: 10px 0 0 0;
    }
</style>
