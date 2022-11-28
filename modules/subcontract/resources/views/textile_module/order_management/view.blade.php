@extends('subcontract::layout')
@section("title","Order Details")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Order Details</h2>
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
                                <a id="order_pdf" data-value="po_details" class="btn" href="{{ url('subcontract/pdf/'.$orderDetails->id) }}"><i class="fa fa-file-pdf-o"></i></a>
                            </div>
                        </div>

                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span style="font-size: 12pt; font-weight: bold;">Order Details</span>
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
                                            <td style="padding-left: 30px;"> {{ $orderDetails->factory->group_name }} </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 0;">
                                                <b>Party :</b>
                                            </td>
                                            <td style="padding-left: 30px;"> {{ $orderDetails->supplier->name }} </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 0;">
                                                <b>Order No :</b>
                                            </td>
                                            <td style="padding-left: 30px;"> {{ $orderDetails->order_no }} </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 0;">
                                                <b>Ref No :</b>
                                            </td>
                                            <td style="padding-left: 30px;"> {{ $orderDetails->ref_no }} </td>
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
                                                        <b>Revised No :</b>
                                                    </td>
                                                    <td style="padding-left: 30px;"> {{ $orderDetails->revised_no }} </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 0;">
                                                        <b>Received Date :</b>
                                                    </td>
                                                    <td style="padding-left: 30px;"> {{ $orderDetails->receive_date }} </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 0;">
                                                        <b>Currency :</b>
                                                    </td>
                                                    <td style="padding-left: 30px;"> {{ $orderDetails->currency->currency_name }} </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 0;">
                                                        <b>Payment Basis :</b>
                                                    </td>
                                                    <td style="padding-left: 30px;"> {{ $orderDetails->payment_basis_value }} </td>
                                                </tr>
                                              </tbody>
                                       </table>
                                    </div>
                                </div>

                                <table class="reportTable" style="width: 100%;margin-top: 30px;">
                                    <thead>
                                    <tr>
                                        <th>Sub Textile Operation</th>
                                        <th>Sub Textile Process</th>
                                        <th>Operation Description</th>
                                        <th>Fabric Composition</th>
                                        <th>Fabric Type</th>
                                        <th>Yarn Type</th>
                                        <th>Color</th>
                                        <th>Color Type</th>
                                        <th>Finish Dia</th>
                                        <th>Dia Type</th>
                                        <th>GSM</th>
                                        <th>Customer Buyer</th>
                                        <th>Customer Style</th>
                                        <th>Order Qty</th>
                                        <th>UOM</th>
                                        <th>Price Rate</th>
                                        <th>Conv Rate</th>
                                        <th>Total Amount BDT</th>
                                        <th>Delivery Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orderDetails->subTextileOrderDetails as $details)
                                        <tr>
                                            <td>{{ $details->subTextileOperation->name }}</td>
                                            <td>{{ $details->subTextileProcess->name }}</td>
                                            <td>{{ $details->operation_description }}</td>
                                            <td>{{ $details->fabric_composition_value }}</td>
                                            <td>{{ $details->fabricComposition->construction }}</td>
                                            <td>{{ collect($details->yarn_details)->pluck('yarn_type_value')->join(', ') }}</td>
                                            <td>{{ $details->color->name }}</td>
                                            <td>{{ $details->colorType->color_types }}</td>
                                            <td>{{ $details->finish_dia }}</td>
                                            <td>{{ $details->dia_type_value }}</td>
                                            <td>{{ $details->gsm }}</td>
                                            <td>{{ $details->customer_buyer }}</td>
                                            <td>{{ $details->customer_style }}</td>
                                            <td>{{ $details->order_qty }}</td>
                                            <td>{{ $details->unitOfMeasurement->unit_of_measurement }}</td>
                                            <td>{{ $details->price_rate }}</td>
                                            <td>{{ $details->conv_rate }}</td>
                                            <td>{{ $details->total_amount_bdt }}</td>
                                            <td>{{ $details->delivery_date }}</td>
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
