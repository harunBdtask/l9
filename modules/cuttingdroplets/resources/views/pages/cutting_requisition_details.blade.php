@push('style')

    <style>

        .reportTable {
            margin-bottom: 1rem;
            width: 100%;
            max-width: 100%;
            font-size: 12px;
            border-collapse: collapse;
        }
        .reportTable thead,
        .reportTable tbody,
        .reportTable th {
            padding: 3px;
            font-size: 12px;
            text-align: center;
        }
        .reportTable th,
        .reportTable td {
            border: 1px solid #000;
        }
        .table td, .table th {
            padding: 0.1rem;
            vertical-align: middle;
        }

        .web-hide {
            display: none;
        }

        @media print {
            @page  {
                size: a4 portrait !important;
                margin-top: -7mm;
            }

            .web-hide {
                display: block;
            }
        }
    </style>

@endpush

@extends('cuttingdroplets::layout')
@section('title', 'Cutting Requisition')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-body">

                <div class="text-center">
                    <h2 style="font-size: 16px;"> {{factoryName()}}</h2>
                   {{--  <h4 style="font-size: 14px;">Unit: {{factoryName()}}</h4> --}}
                    <h4 style="font-size: 14px;">{{factoryAddress()}}</h4>
                </div>

                <div class="text-center">
                    <h4 style="font-size: 16px;">Cutting Requisition Details</h4>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <tr>
                                <th style="background: ghostwhite;">Cutting Requisition No</th>
                                <td>{{ $requisition->cutting_requisition_no }}</td>
                                <th style="background: ghostwhite;">Date</th>
                                <td>{{ format_date($requisition->created_at) }}</td>
                            </tr>
                            <tr>
                                <th style="background: ghostwhite;">Style</th>
                                <td>
                                    @foreach($bookings as $key => $val)
                                        {{ $val }}
                                        @if(!$loop->last)
                                            {{ ',' }}<br>
                                        @endif
                                    @endforeach
                                </td>
                                <th style="background: ghostwhite;">Buyer</th>
                                <td>
                                    @foreach($buyers as $key => $val)
                                        {{ $val }}
                                        @if(!$loop->last)
                                            {{ ',' }}<br>
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th style="background: ghostwhite;">Order/Style No</th>
                                <td>
                                    @foreach($orders as $key => $val)
                                        {{ $val }}
                                        @if(!$loop->last)
                                            {{ ',' }}<br>
                                        @endif
                                    @endforeach
                                </td>
                                <th style="background: ghostwhite;">Created By</th>
                                <td>
                                    {{ $created_by->first_name  . ' ' . $created_by->last_name }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead>
                                <tr style="background: ghostwhite;">
                                    <th>Fab Composition</th>
                                    <th>Fab Type</th>
                                    <th>Color</th>
                                    <th>Garments Part</th>
                                    <th>Batch No</th>
                                    <th>Req Amount</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($details as $detail)
                                    <tr>
                                        <td>{{ optional($detail->fabric_composition)->yarn_composition }}</td>
                                        <td>{{ optional($detail->fab_type)->fabric_type_name }}</td>
                                        <td>{{ optional($detail->color)->name }}</td>
                                        <td>{{ $detail->garments_part->name }}</td>
                                        <td>{{ $detail->batch_no }}</td>
                                        <td>{{ $detail->requisition_amount }} {{ BOOKING_UNIT_CONSUMPTION[$detail->unit_of_measurement_id ?? 2] }}</td>
                                        <td>{{ $detail->remark }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="web-hide row pull-right"  style="border-top: 1px solid black; padding-top: 5px; position: fixed; bottom: 100px; right: 20px;">
                    Authorized By
                </div>

                <div class="web-hide" style="position: fixed; bottom: 10px;">
                    © Copyright <strong>goRMG-ERP</strong>. Developed by Skylark Soft Limited.
                </div>

            </div>

        </div>
    </div>
@endsection
