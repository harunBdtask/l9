<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recap Report</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style type="text/css">
        /* table style */
        body {
            margin: 2px;
            font-family: sans-serif;
        }

        .reportTable {
            margin-bottom: 1rem;
            width: 100%;
            max-width: 95%;
            border-collapse: collapse;
            margin: 0 auto;
        }

        .reportTable thead,
        .reportTable tbody,
        .reportTable thead th {
            /*padding: 3px;*/
            font-size: 12px;
            text-align: center;
            width: 50px;
        }

        .reportTable th,
        .reportTable td {
            border: 1px solid black;

        }

        .table td, .table th {
            padding: 0.1rem;
            vertical-align: middle;
        }

        .inner-table tr td {
            border: none;
        }

        .inner-table tr td {
            border-left: none;
            border-right: none;
        }

        .inner-table tr td {
            border-top: none;
        }

        .inner-table tr:last-child td {
            border-bottom: none;
        }

        header {
            text-align: center;
        }

        header h4 {
            margin: 2px 0 2px 0;
        }

        header h2 {
            margin-bottom: 2px;
        }

        p {
            font-size: 12px;

        }

        footer {
            margin-top: 20px;
            text-align: center;
        }

        caption {
            caption-side: top;
            margin-top: -15px;
        }
    </style>

</head>
<body>
<header>
    <h4><b>{{groupName()}}</b></h4>
    <h5><b>Unit: {{factoryName()}}</b></h5>
    <h6><b>{{factoryAddress()}}</b></h6>
</header>

<main>
    <div style="text-align: center">
        <p>
            <b>Order No:</b> {{$orderDetails->order_style_no}} <br>
            <b>Buyer:</b> {{$orderDetails->buyer->name}} <br>
            <b>Buying Agent:</b> {{ $orderDetails->agent->buying_agent_name }} <br>
            <b>Total No PO: </b> {{ $orderDetails->total_po }} <br>
            <b>Total PO quantity:</b>{{$orderDetails->total_quantity}}
        </p>
    </div>
    <hr>
    <div class="">
        @if(isset($items))
            <table class="reportTable">
                <caption><h6>Budget Details</h6></caption>
                <thead>
                <tr style="background: #cccccc">
                    <th>SL</th>
                    <th>Description</th>
                    <th>Size</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                </tr>
                </thead>
                <tbody>
                @php $total_pcs = 0; $total = 0 ;@endphp
                @foreach($items->groupBy('item_id') as $items)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$items->first()->item->item_name ?? '--'}}</td>
                        <td>{{$items->implode('size.name', ', ')}}</td>
                        <td>{{$items->sum('quantity')}}</td>
                        <td>{{$items->first()->order_item_details->unit_price ?? 0}}</td>
                        <td>
                            @php
                                $unit_price = $items->first()->order_item_details->unit_price ?? 0;
                                $quantity = $items->sum('quantity');
                                $sub_total = ($unit_price * $quantity);
                            @endphp
                            {{$sub_total}}
                        </td>
                    </tr>
                    @php
                        $total_pcs += $items->sum('quantity');
                        $total += $sub_total;
                    @endphp
                @endforeach
                <tr>
                    <td colspan="3" style="font-weight: bold">Total PCS :</td>
                    <td><span style="text-align: right;font-weight: bold">{{$total_pcs}}</span></td>
                    <td></td>
                    <td colspan="2" style="font-weight: bold">{{$total}}</td>
                </tr>
                </tbody>
                <tfoot>

                </tfoot>
            </table>
        @endif
    </div>
    @if(isset($finish_fabrics) && $finish_fabrics->count() > 0)
        <div class="">
            <table class="reportTable">
                <caption><h6 style="line-height: 30px">Finish Fabric Costing</h6></caption>
                <thead>
                <tr style="background: #cccccc">
                    <th>SL</th>
                    <th>Fabric</th>
                    <th>Consumption / KG</th>
                    <th>Req. Fab. Qty</th>
                    <th>Budget Fab. Qty</th>
                    <th>Yarn Count</th>
                    <th>Yarn Qty</th>
                    <th>Lycra</th>
                </tr>
                </thead>
                <tbody>
                @foreach($finish_fabrics as $fabric)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$fabric->fabric_description}}</td>
                        <td></td>
                        <td>{{$fabric->fabric_required_qty}}</td>
                        <td>{{$fabric->fabric_required_qty}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>

                </tfoot>
            </table>
        </div>
    @endif
    @if(isset($gray_fabric) && $gray_fabric->count() > 0)
        <div class="">
            <table class="reportTable">
                <caption><h6>Gray Fabric</h6></caption>
                <thead>
                <tr style="background: #cccccc">
                    <th>SL</th>
                    <th>Source</th>
                    <th>Fab. Desc</th>
                    <th>Composition</th>
                    <th>Fabric Type</th>
                    <th>GSM</th>
                    <th>Required Dia</th>
                    <th>Required Qty</th>
                    <th>Supplier</th>
                    <th>Unit Price</th>
                    <th>Total Amount</th>
                </tr>
                </thead>
                <tbody>
                @foreach($gray_fabric as $value)
                    @php
                        $source = '';
                    if($value->gray_fabric_source == 1){
                        $source = 'Purchase';
                    }elseif($value->gray_fabric_source == 2){
                        $source = 'Inhouse';
                    }else{
                         $source = 'Subcontract';
                    }
                    @endphp
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$source}}</td>
                        <td>{{$value->gray_fabric_description}}</td>
                        <td>{{$value->composition->yarn_composition}}</td>
                        <td>{{$value->gray_fabric_type == 1 ? 'Knit' : 'Woven'}}</td>
                        <td>{{$value->gray_gsm}}</td>
                        <td>{{$value->gray_required_dia}}</td>
                        <td>{{$value->gray_fabric_required_qty}}</td>
                        <td>{{$value->supplier->supplier_name ?? 'N/A'}}</td>
                        <td>{{$value->gray_unit_price}}</td>
                        <td>{{$value->gray_total_amount}}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot></tfoot>
            </table>
        </div>
    @endif
    @if(isset($yarn) && $yarn->count() > 0)
        <div class="table-responsive">
            <table class="reportTable">
                <caption><h6 style="padding-top: 20px">Yarn Details</h6></caption>
                <thead>
                <tr style="background: #cccccc">
                    <th>SL</th>
                    <th>Source</th>
                    <th>Supplier</th>
                    <th>Fab. Desc</th>
                    <th>Fab. Comp</th>
                    <th>Yarn Type</th>
                    <th>Yarn Composition</th>
                    <th>Color Type</th>
                    <th>Yarn color</th>
                    <th>Yarn Count</th>
                    <th>Consumption</th>
                    <th>Fab. Req. Qty</th>
                    <th>Process Loss</th>
                    <th>Yarn Req Qty</th>
                    <th>Price / Kg</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach($yarn as $value)
                    @php
                        $source = '';
                    if($value->yarn_source == 1){
                        $source = 'Purchase';
                    }elseif($value->yarn_source == 2){
                        $source = 'Inhouse';
                    }else{
                         $source = 'Subcontract';
                    }
                    @endphp
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$source}}</td>
                        <td>{{$value->supplier->supplier_name ?? 'N/A'}}</td>
                        <td>{{$value->yarn_description}}</td>
                        <td>{{$value->yarn_fabric_composition}}</td>
                        <td>{{$value->yarntype->yarn_type ?? 'N/A'}}</td>
                        <td>{{$value->yarncomposition->yarn_composition ?? 'N/A'}}</td>
                        <td>{{$value->yarn_color == 1 ? 'Solid' : 'Dyed'}}</td>
                        <td>{{$value->color->name ?? 'N/A'}}</td>
                        <td>{{$value->yarncount->yarn_count ?? 'N/A'}}</td>
                        <td>{{$value->yarn_consumption}}</td>
                        <td>{{$value->fabric_req_qty}}</td>
                        <td>{{$value->process_loss}}</td>
                        <td>{{$value->yarn_req_qty}}</td>
                        <td>{{$value->price_per_kg}}</td>
                        <td>{{$value->total}}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot></tfoot>
            </table>
        </div>
    @endif
    @if(isset($knitting) && $knitting->count() > 0)
        <div class="">
            <table class="reportTable">
                <caption><h6 style="padding-top: 20px">Knitting Details</h6></caption>
                <thead>
                <tr style="background: #cccccc">
                    <th>SL</th>
                    <th>Source</th>
                    <th>Supplier</th>
                    <th>Fab. Desc</th>
                    <th>Fab. Comp</th>
                    <th>Yarn Count</th>
                    <th>Fab. Req Qty</th>
                    <th>Price/Kg</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach($knitting as $value)
                    @php
                        $source = '';
                    if($value->knitting_source == 1){
                        $source = 'Purchase';
                    }elseif($value->knitting_source == 2){
                        $source = 'Inhouse';
                    }else{
                         $source = 'Subcontract';
                    }
                    @endphp
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$source}}</td>
                        <td>{{$value->supplier->supplier_name ?? 'N/A'}}</td>
                        <td>{{$value->knitting_fabric_description}}</td>
                        <td>{{$value->composition->yarn_composition}}</td>
                        <td>{{$value->yarn_count->yarn_count }}</td>
                        <td>{{$value->knitting_fabric_req_qty}}</td>
                        <td>{{$value->knitting_fabric_price_per_kg}}</td>
                        <td>{{$value->knitting_total}}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot></tfoot>
            </table>
        </div>
    @endif
    @if(isset($dyeing) && $dyeing->count() > 0)
        <div class="">
            <table class="reportTable">
                <caption><h6 style="padding-top: 20px">Dyeing Details</h6></caption>
                <thead>
                <tr style="background: #cccccc">
                    <th>SL</th>
                    <th>Source</th>
                    <th>Supplier</th>
                    <th>Color</th>
                    <th>Fabric Required Qty</th>
                    <th>Price/Kg</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach($dyeing as $value)
                    @php
                        $source = '';
                    if($value->dyeing_source == 1){
                        $source = 'Purchase';
                    }elseif($value->gray_fabric_source == 2){
                        $source = 'Inhouse';
                    }else{
                         $source = 'Subcontract';
                    }
                    @endphp
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$source}}</td>
                        <td>{{$value->supplier->supplier_name ?? 'N/A'}}</td>
                        <td>{{$value->color->name ?? 'N/A'}}</td>
                        <td>{{$value->dyeing_fab_req_qty}}</td>
                        <td>{{$value->price_per_kg}}</td>
                        <td>{{$value->dyeing_total}}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot></tfoot>
            </table>
        </div>
    @endif
    @if(isset($accessories) && $accessories->count() > 0)
        <div class="">
            <table class="reportTable">
                <caption><h6 style="padding-top: 20px">Accessories</h6></caption>
                <thead>
                <tr style="background: #cccccc">
                    <th>SL</th>
                    <th>Item</th>
                    <th>Unit</th>
                    <th>Price</th>
                    <th>Req. Qty</th>
                    <th>Total Amount</th>
                </tr>
                </thead>
                <tbody>
                @foreach($accessories as $accessory)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$accessory->item->item_name ?? '--'}}</td>
                        <td>{{$accessory->consumption_uom->unit_of_measurements ?? '--'}}</td>
                        <td>{{$accessory->cost_per_unit}}</td>
                        <td>
                            @php
                                $total_qty = 0;
                                $qty = json_decode($accessory->quantity_details);
                                if($accessory->color_type == 'separate_color' && $accessory->size_type == 'separate_size'){
                                    foreach ($qty as $key=>$details){
                                        foreach ($details as $key2=>$detail){
                                            $total_qty +=  $detail;
                                        }
                                    }
                                }elseif ($accessory->color_type == 'all_color' && $accessory->size_type == 'separate_size'){
                                   foreach ($qty as $all_color_separate_size){
                                        $total_qty +=  $all_color_separate_size;
                                   }
                                }elseif($accessory->color_type == 'separate_color' && $accessory->size_type == 'all_size'){
                                    foreach ($qty as $all_color_separate_size){
                                        $total_qty +=  $all_color_separate_size;
                                    }
                                }
                            @endphp
                            {{$total_qty}}
                        </td>
                        <td>{{($accessory->cost_per_unit * $total_qty)}}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot></tfoot>
            </table>
        </div>
        <div style="margin-top: 20px">
            <table class="reportTable">
                <tr style="background: #cccccc">
                    <td colspan="2"><h6><b>Summary Of Cost Breakdown</b></h6></td>
                </tr>
                <tr>
                    <td>Fabric Cost</td>
                    <td>{{$fabric_total}}</td>
                </tr>
                <tr>
                    <td>Trims Cost</td>
                    <td>{{$trims_total}}</td>
                </tr>
                @php $all_total = 0 @endphp
                @foreach($other_costing as $key=>$value)
                    <tr>
                        <td>{{$value->others_item->component ?? 'N/A'}}</td>
                        <td>{{$value->total_cost}}</td>
                    </tr>
                    @php $all_total += $value->total_cost @endphp
                @endforeach
                @php $sum_total = $all_total + $fabric_total + $trims_total @endphp
                <tfoot>
                <tr>
                    <td class="text-right">Total Cost</td>
                    <td class="text-center">{{$sum_total}}</td>
                </tr>
                <tr>
                    <td class="text-right">Cost / Dozon</td>
                    <td class="text-center">{{$sum_total/12}}</td>
                </tr>
                <tr>
                    <td class="text-right">Net Profit / Balance</td>
                    <td class="text-center">{{($sum_total) - ($cm_cost->total_cost ?? 0)}}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    @endif
</main>
<footer>
    © Copyright - PROTRACKER. Produced by Skylark Soft Limited.
</footer>
<script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script>
    $(function () {
        window.print();

    });
</script>
</body>
</html>