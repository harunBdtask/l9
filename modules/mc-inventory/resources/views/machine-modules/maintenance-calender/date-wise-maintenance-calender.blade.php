@extends('skeleton::layout')
@section('title','Date Wise Maintenance Calender')
@section('content')
<style type="text/css">
    #pdfGenerateInfo {
        display: none;
    }

    .v-align-top td, .v-algin-top th {
        vertical-align: top;
    }

    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }

    .page {
        width: 210mm;
        min-height: 297mm;
        margin: 10mm auto;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .header-section {
        padding: 10px;
    }

    .body-section {
        padding: 10px;
        padding-top: 0px;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .text-left {
        text-align: left;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table, th, td {
        border: 1px solid black;
    }

    th, td {
        padding-left: 5px;
        padding-right: 5px;
        padding-top: 3px;
        padding-bottom: 3px;
    }

    table.borderless {
        border: none;
    }

    .borderless td, .borderless th {
        border: none;
    }

    @page {
        size: landscape;
        /*margin: 5mm;*/
        /*margin-left: 15mm;*/
        /*margin-right: 15mm;*/
    }

    .select2-container .select2-selection--single {
        height: 32px !important;
    }

    .select2-container--default .select2-selection--multiple {
        min-height: 35px !important;
    }

    @media print {
        html, body {
            width: 210mm;
            /*height: 293mm;*/
        }

        .page {
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }
    }
</style>
<div class="padding">
    <div class="box">
        <div class="box-body table-responsive b-t">
            <div class="">
                <center>
                    <table style="border: 1px solid black;width: 20%;">
                        <thead>
                        <tr>
                            <td class="text-center">
                                <span style="font-size: 12pt; font-weight: bold;">Maintenance Calender</span>
                                <br>
                            </td>
                        </tr>
                        </thead>
                    </table>
                </center>
                <br>
                <div class="row">
                    <div class="col-md-12" >
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th><b>SL</b></th>
                                <th><b>M/C Name</b></th>
                                <th><b>M/C No</b></th>
                                <th><b>M/C Model No</b></th>
                                <th><b>Last Service Date</b></th>
                                <th><b>Last Service Description</b></th>
                                <th><b>Next Service Date</b></th>
                                <th><b>Unit</b></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($maintenances as $maintenance)
                                <tr>
                                    <td class="text-center">{{$loop->iteration}}</td>
                                    <td class="text-center">{{$maintenance->machine->name}}</td>
                                    <td class="text-center">{{$maintenance->machine->barcode}}</td>
                                    <td class="text-center">{{$maintenance->machine->model_no}}</td>
                                    <td class="text-center">{{$maintenance->last_maintenance}}</td>
                                    <td class="text-center">{{$maintenance->description}}</td>
                                    <td class="text-center">{{$maintenance->next_maintenance}}</td>
                                    <td class="text-center">{{$maintenance->machineUnit->name ?? ''}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

