@extends('dyes-store::layout')
@section('title','Dyes Chemical Issue')
@section('content')
    <style type="text/css">
        .party-info-section {
            float: left;
            width: 40%;
        }

        .challan-info-section {
            float: right;
            width: 40%;
        }

        .v-align-top td,
        .v-algin-top th {
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

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 3px;
            padding-bottom: 3px;
        }

        table.borderless {
            border: none;
        }

        .borderless td,
        .borderless th {
            border: none;
        }

        @page {
            size: A4;
            margin: 5mm;
            margin-left: 15mm;
            margin-right: 15mm;
        }

        @media print {

            html,
            body {
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
                    <div class="header-section" style="padding-bottom: 0px;">
                        <div class="pull-right" style="margin-bottom: -5%;">
                            {{-- <a class="btn btn-xs btn-primary print" href="{{ url('/vouchers/' . $voucher->id . '/print') }}"><i
                                    class="fa fa-print"></i></a>
                            <a class="btn btn btn-xs btn-danger" href="{{ url('/vouchers/' . $voucher->id . '/download') }}"><i
                                    class="fa fa-file-pdf-o"></i></a> --}}
                        </div>
                        <table class="borderless">
                            <thead>
                            <tr>
                                <td class="text-center">
                                    <span
                                        style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span><br>
                                    {{ factoryAddress() }}<br>
                                    Dhaka,Bangladesh
                                </td>
                            </tr>
                            </thead>
                        </table>
                        <hr>
                    </div>
                    <center>
                        <table style="border: 1px solid black;width: 20%;background: #02aeef">
                            <thead>
                            <tr>
                                <td class="text-center">
                                    <span style="font-size: 12pt; font-weight: bold;">Dyes & Chemicals Issues</span>
                                    <br>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </center>
                    <br>

                    <div class="body-section">
                        <table>
                            <thead class="thead-light">
                            <tr>
                                <th class="text-left">Sl No</th>
                                <th class="text-left">Batch No</th>
                                <th class="text-left">Item</th>
                                <th class="text-left">Category</th>
                                <th class="text-left">Brand</th>
                                <th class="text-left">Delivery Date</th>
                                <th class="text-left">Delivery Qty.</th>
                                <th class="text-left">Rate</th>
                                <th class="text-left">Total Value</th>
                                <th class="text-left">UOM</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($dyesChemicalsIssue->details as $detail)
                                <tr>
                                    <td class="text-left">{{ $loop->iteration }}</td>
                                    <td class="text-left">{{ collect($dyesChemicalsIssue->details)->pluck('batch_no')->unique()->join(' , ') ?? '' }}</td>
                                    <td class="text-left">{{ $detail['item_name'] ?? '' }}</td>
                                    <td class="text-left">{{ $detail['category_name'] ?? '' }}</td>
                                    <td class="text-left">{{ $detail['brand_name'] ?? '' }}</td>
                                    <td class="text-left">{{ \Carbon\Carbon::parse($dyesChemicalsIssue->delivery_date)->toFormattedDateString() }}</td>
                                    <td class="text-left">{{ $detail['delivery_qty'] ?? '' }}</td>
                                    <td class="text-left">{{ $detail['rate'] ?? '' }}</td>
                                    <td class="text-left">{{ number_format($detail['delivery_qty'] * $detail['rate'], 3) ?? '' }}</td>
                                    <td class="text-left">{{ $detail['uom_name'] ?? '' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <br>

                        <div style="margin-top: 16mm">
                            <table class="borderless">
                                <tbody>
                                <tr>
                                    <td class="text-center"><u>Prepared By</u></td>
                                    <td class="text-center"><u>Authorized By</u></td>
                                    <td class='text-center'><u>Issued By</u></td>
                                    <td class='text-center'><u>Received By</u></td>
                                    <td class='text-center'><u>Kardex Posted By</u></td>
                                    <td class='text-center'><u>Ledger Posted </u></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-head')

@endpush
