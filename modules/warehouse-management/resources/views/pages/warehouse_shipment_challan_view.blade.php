@extends('warehouse-management::layout')
@section('title', 'Gate Pass')

@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Gate Pass || {{ date("jS F, Y", strtotime($warehouse_shipment_challan->created_at)) }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="factory-area text-center" style="font-size: 1.1em;">
                            @if($userFactoryInfo)
                                <strong>{{ $userFactoryInfo->group_name ?? ''}}</strong><br>
                                <strong>{{ $userFactoryInfo->factory_name ?? ''}}</strong><br>
                                {{ $userFactoryInfo->factory_address ?? '' }}<br>
                                <strong>Gate Pass / Challan</strong><br>
                            @endif
                        </div>
                        <hr>
                        <div class="col-sm-12 text-center" style="margin-bottom: 20px;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <strong>Challan No: </strong> {{ $warehouse_shipment_challan->challan_no }}
                                </div>
                            </div>
                            <br/>
                        </div>
                        <br>
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>Buyer</th>
                                <th>Style</th>
                                <th>PO</th>
                                <th>Total Carton</th>
                                <th>Total Qty</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($reportData && $reportData->count())
                                @php
                                    $g_total_carton = 0;
                                    $g_total_garments_qty = 0;
                                @endphp
                                @foreach($reportData->groupBy('purchase_order_id') as $reportByPo)
                                    @php
                                        $buyer = $reportByPo->first()->buyer->name;
                                        $order = $reportByPo->first()->order->style_name;
                                        $purchaseOrder = $reportByPo->first()->purchaseOrder->po_no;
                                        $total_carton = $reportByPo->count();
                                        $total_garments_qty = $reportByPo->sum('garments_qty');

                                        $g_total_carton += $total_carton;
                                        $g_total_garments_qty += $total_garments_qty;
                                    @endphp
                                    <tr>
                                        <td>{{ $buyer }}</td>
                                        <td>{{ $order }}</td>
                                        <td>{{ $purchaseOrder }}</td>
                                        <td>{{ $total_carton }}</td>
                                        <td>{{ $total_garments_qty }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th colspan="3">Total</th>
                                    <th>{{ $g_total_carton }}</th>
                                    <th>{{ $g_total_garments_qty }}</th>
                                </tr>
                            @else
                                <tr>
                                    <th colspan="5">No Data</th>
                                </tr>
                            @endif
                            </tbody>
                        </table>

                        <table class="text-center" width="100%" style="margin-top: 100px; margin-bottom: 35px"
                               class="autorized_table">
                            <tr style="font-weight: bold">
                                <td>&nbsp;</td>
                                <td>Prepared By</td>
                                <td>Incharge/Manager</td>
                                <td>&nbsp;</td>
                                <td>Authorised Signature</td>
                                <td>Driver</td>
                                <td><input type="text" size="15"></td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection