@extends('subcontract::layout')
@section("title","Sub Grey Store Material Fabric Receive")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Material Fabric Issue Details</h2>
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
                                <a id="order_pdf" data-value="po_details" class="btn" href="{{ url('subcontract/material-fabric-issue/pdf/'.$fabricIssue->id) }}"><i class="fa fa-file-pdf-o"></i></a>
                            </div>
                        </div>

                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span style="font-size: 12pt; font-weight: bold;">Fabric Issue</span>
                                        <br>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </center>

                        <div class="row p-x-1">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5" style="float: left; position:relative; margin-top:30px">
                                    <table class="borderless">

                                    <tbody>
                                        <tr>
                                            <td style="padding-left: 0;">
                                                <b>Factory :</b>
                                            </td>
                                            <td style="padding-left: 30px;"> {{ $fabricIssue->factory->group_name }} </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 0;">
                                                <b>Party :</b>
                                            </td>
                                            <td style="padding-left: 30px;"> {{ $fabricIssue->supplier->name }} </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 0;">
                                                <b>Issue Challan No :</b>
                                            </td>
                                            <td style="padding-left: 30px;"> {{ $fabricIssue->challan_no }} </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 0;">
                                                <b>Issue Purpose :</b>
                                            </td>
                                            <td style="padding-left: 30px;"> {{ $fabricIssue->issue_purpose_value }} </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 0;">
                                                <b>Today Date :</b>
                                            </td>
                                            <td style="padding-left: 30px;"> {{ $currentDate }} </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-5" style="float: right; position:relative;margin-top:30px">
                                        <table class="borderless">
                                            <tbody>
                                                <tr>
                                                    <td style="padding-left: 0;">
                                                        <b>Sub Grey Store :</b>
                                                    </td>
                                                    <td style="padding-left: 30px;"> {{ $fabricIssue->subGreyStore->name }} </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 0;">
                                                        <b>Order No :</b>
                                                    </td>
                                                    <td style="padding-left: 30px;"> {{ $fabricIssue->textileOrder->order_no }} </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 0;">
                                                        <b>Issue Challan Date :</b>
                                                    </td>
                                                    <td style="padding-left: 30px;"> {{ $fabricIssue->challan_date }} </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 0;">
                                                        <b>Dyeing Unit :</b>
                                                    </td>
                                                    <td style="padding-left: 30px;"> {{ $fabricIssue->subDyeingUnit->name }} </td>
                                                </tr>
                                              </tbody>
                                       </table>
                                    </div>
                                </div>

                                <table class="reportTable" style="width: 100%;margin-top: 30px;">
                                    <thead>
                                    <tr>
                                        <th>Req Operation</th>
                                        <th>Process</th>
                                        <th>Fab Composition</th>
                                        <th>Fab Type</th>
                                        <th>Color</th>
                                        <th>Color Type</th>
                                        <th>Fin.Dia</th>
                                        {{-- <th>Yarn Details</th> --}}
                                        <th>Grey Avl Stock Qty</th>
                                        <th>UOM</th>
                                        <th>Total Roll</th>
                                        <th>Total Qty</th>
                                        <th>Return Qty</th>
                                        <th>Remarks</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($fabricIssue->issueDetails as $details)
                                        <tr>
                                            <td>{{ $details->subTextileOperation->name }}</td>
                                            <td>{{ $details->subTextileProcess->name }}</td>
                                            <td>{{ $details->fabric_description }}</td>
                                            <td>{{ $details->fabricType->construction_name }}</td>
                                            <td>{{ $details->color->name }}</td>
                                            <td>{{ $details->colorType->color_types }}</td>
                                            <td>{{ $details->finish_dia }}</td>
                                            {{-- <td></td> --}}
                                            <td>{{ $details->grey_required_qty}}</td>
                                            <td>{{ $details->unitOfMeasurement->unit_of_measurement }}</td>
                                            <td>{{ $details->total_roll }}</td>
                                            <td>{{ $details->issue_qty }}</td>
                                            <td>{{ $details->issue_return_qty }}</td>
                                            <td><{{ $details->remarks }}/td>

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
    </div>
    <style>
        /*.custom-field {*/
        /*    */
        /*    */
        /*}*/
    </style>
@endsection
@section('scripts')
    <script>

    </script>
@endsection
