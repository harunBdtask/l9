@extends('subcontract::layout')
@section("title","Batch View Page")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Batch Card</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">

                        <div class="header-section" style="padding-bottom: 0px;">
                            <div class="pull-right" style="margin-bottom: -5%;">
                                <a id="order_pdf" data-value="po_details" class="btn"
                                   href="{{ url('subcontract/dyeing-process/batch-entry/pdf/'.$dyeingBatch->id) }}"><i
                                        class="fa fa-file-pdf-o"></i></a>
                            </div>
                        </div>

                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span style="font-size: 10pt; font-weight: bold;">Batch Entry</span>
                                        <br>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </center>

                        <table class="reportTable" style="margin-top: 1%">
                            <thead>
                            <tr>
                                <td>Batch Date</td>
                                <td>Buyer/Party Name</td>
                                <td>Batch Type</td>
                                <td>Order No</td>
                                <td>Batch No</td>
                                <td>Color</td>
                                <td>Fabric Description</td>
                                <td>Fabric Type</td>
                                <td>Dia Type</td>
                                <td>GSM</td>
                                <td>Fab Dia</td>
                                <td>Batch Roll</td>
                                <td>Batch Weight</td>
                                <td>Remarks</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dyeingBatch->batchDetails as $key => $detail)
                                <tr>
                                    <td>{{ $dyeingBatch->batch_date }}</td>
                                    <td>{{ $dyeingBatch->supplier->name ?? '' }}</td>
                                    <td>
                                        @if($dyeingBatch->batch_entry == 1)
                                            <span>Sample</span>
                                        @else
                                            <span>Bulk</span>
                                        @endif
                                    </td>
                                    <td>{{ $detail->subTextileOrder->order_no ?? '' }}</td>
                                    <td>{{ $dyeingBatch->batch_no }}</td>
                                    <td>{{ $dyeingBatch->color->name }}</td>
                                    <td>{{ $detail->fabric_composition_value }}</td>
                                    <td>{{ $detail->fabricType->construction_name }}</td>
                                    <td>
                                        @if($detail->dia_type_id == 1)
                                            <span>Open</span>
                                        @elseif($detail->dia_type_id == 2)
                                            <span>Tabular</span>
                                        @elseif($detail->dia_type_id == 3)
                                            <span>Niddle Open</span>
                                        @else
                                            <span>Any Dia</span>
                                        @endif
                                    </td>
                                    <td>{{ $detail->gsm }}</td>
                                    <td>{{ $detail->finish_dia }}</td>
                                    <td>{{ $detail->batch_roll }}</td>
                                    <td>{{ $detail->batch_weight }}</td>
                                    <td>{{ $detail->remarks }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
