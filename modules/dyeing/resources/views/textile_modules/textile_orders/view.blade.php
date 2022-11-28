@extends('subcontract::layout')
@section("title","Dyeing Textile Order")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Textile Orders</h2>
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
                                <a id="order_pdf" data-value="po_details" class="btn" href="{{url('/dyeing/textile-orders/pdf/'.$textileOrders->id)}}"><i class="fa fa-file-pdf-o"></i></a>
                            </div>
                        </div>

                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span style="font-size: 12pt; font-weight: bold;">Textile Order</span>
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
                                                <td style="padding-left: 30px;"> {{$textileOrders->factory->factory_name}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Buyer :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$textileOrders->buyer->name}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Sales Order No :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$textileOrders->fabricSalesOrder->sales_order_no}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Description :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$textileOrders->description}} </td>
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
                                                    <b>Receive Date :</b>
                                                </td>
                                                <td style="padding-left: 30px;">{{$textileOrders->receive_date}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Currency :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$textileOrders->currency->currency_name}}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Payment Basis :</b>
                                                </td>
                                                <td style="padding-left: 30px;">{{$textileOrders->payment_basis}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Remarks :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$textileOrders->remarks}}</td>
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
                                        <th>OP.DESC</th>
                                        <th>Body Part</th>
                                        <th>Fab Comp</th>
                                        <th>Fab Type</th>
                                        <th>Color</th>
                                        <th>L/D No</th>
                                        <th>Color Type</th>
                                        <th>Fin Dia</th>
                                        <th>Dia Type</th>
                                        <th>GSM</th>
                                        <th>Yarn Description</th>
                                        <th>Cus Buyer</th>
                                        <th>Cus Style</th>
                                        <th>Req Order Qty</th>
                                        <th>UOM</th>
                                        <th>Price Rate/USD</th>
                                        <th>Total Value</th>
                                        <th>Conv Rate</th>
                                        <th>Total Amount(BDT)</th>
                                        <th>Del Date</th>
                                        <th>Remarks</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($textileOrders->textileOrderDetails as $order)
                                        <tr>
                                            <td>{{$order->subTextileOperation->name}}</td>
                                            <td>{{$order->subTextileProcess->name}}</td>
                                            <td>{{$order->operation_description}}</td>
                                            <td>{{$order->bodyPart->name}}</td>
                                            <td>{{$order->fabricComposition->construction}}</td>
                                            <td>{{$order->fabricType->name}}</td>
                                            <td>{{$order->color->name}}</td>
                                            <td>{{$order->ld_no}}</td>
                                            <td>{{$order->colorType->color_types}}</td>
                                            <td>{{$order->finish_dia}}</td>
                                            <td>{{$order->dia_type_value}}</td>
                                            <td>{{$order->gsm}}</td>
                                            <td>{{$order->yarn_details}}</td>
                                            <td>{{$order->customerBuyer->name}}</td>
                                            <td>{{$order->customerStyle->style_name}}</td>
                                            <td>{{$order->order_qty}}</td>
                                            <td>{{$order->unitOfMeasurement->unit_of_measurement}}</td>
                                            <td>{{$order->price_rate}}USD</td>
                                            <td>{{$order->total_value}}</td>
                                            <td>{{$order->conv_rate}}</td>
                                            <td>{{$order->total_amount_bdt}}</td>
                                            <td>{{$order->delivery_date}}</td>
                                            <td>{{$order->remarks}}</td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="24">No Data</td>
                                            </tr>
                                        @endforelse
                                        

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
