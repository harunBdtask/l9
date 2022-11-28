
<div class="padding">
    <div class="box">
        <div class="box-body table-responsive b-t">
                <div class="">
                    <center>
                        <table style="border: 1px solid black;width: 20%;">
                            <thead>
                            <tr>
                                <td class="text-center">
                                    <span style="font-size: 7pt; font-weight: bold;">Party And Order Wise Report</span>
                                    <br>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </center>
                    <br>
                    <div class="row p-x-1">
                        <div class="col-md-12" id="dateWiseStockSummeryTable">
                            <table class="reportTable">
                                <thead>
                                <tr>
                                    <th><b>SL</b></th>
                                    <th><b>Buyer Name</b></th>
                                    <th><b>Sales Order No</b></th>
                                    <th><b>Order Receive Date</b></th>
                                    <th><b>Fab Description</b></th>
                                    <th><b>Fab Color</b></th>
                                    <th><b>Order Qty</b></th>
                                    <th><b>Order Value (BDT)</b></th>
                                    <th><b>T Batch Qty</b></th>
                                    <th><b>T Dye Production Qty</b></th>
                                    <th><b>T Dye Finish Qty</b></th>
                                    <th><b>T Delivery QTY</b></th>
                                    <th><b>T Bill Value</b></th>
                                </tr>
                                </thead>
                                <tbody>
                                    
                                    @foreach ($orderDetails as $key=>$order)
                            
                                        @foreach ($order->textileOrderDetails as $details)
                                            <tr>
                                                @if ($loop->first)
                                                @php
                                                    $rowSpan = $order->textileOrderDetails->count();
                                                @endphp
                                                <td class="text-center" rowspan="{{ $rowSpan }}">{{ $key+1 }}</td>
                                                <td class="text-center" rowspan="{{ $rowSpan }}">{{ $order->buyer->name }}</td>
                                                <td class="text-center" rowspan="{{ $rowSpan }}">{{ $order->fabric_sales_order_no }}</td>
                                                <td class="text-center" rowspan="{{ $rowSpan }}">{{ $order->receive_date }}</td>
                                                @endif
                                
                                                <td class="text-center">{{ $details->fabric_composition_value }}</td>
                                                <td class="text-center">{{ $details->color->name }}</td>
                                                <td class="text-center">{{ $details->order_qty }}</td>
                                                <td class="text-center">{{ $details->total_value }}</td>
                                                <td class="text-center">{{ $details->dyeingBatchDetail->order_qty }}</td>
                                                <td class="text-center">{{ $details->dyeingProductionDetails->dyeing_production_qty }}</td>
                                                <td class="text-center">{{ $details->dyeingFinishingProductionDetail->total_finish_qty }}</td>
                                                <td class="text-center">{{ $details->dyeingGoodsDeliveryDetail->delivery_qty }}</td>
                                                <td class="text-center"></td>
                                            </tr>
                                            @endforeach
                                        
                                        @endforeach
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                    

                </div>
        </div>
    </div>
</div>

