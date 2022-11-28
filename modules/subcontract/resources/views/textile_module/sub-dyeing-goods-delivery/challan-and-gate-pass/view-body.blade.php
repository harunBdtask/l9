<style>
.reportTable th, .reportTable td, .borderless th, .borderless td {
        font-size: 14px;

    }
    .reportTable th, .reportTable td{
        text-align: right;
    }
</style>
<div class="row p-x-1">
    <div class="col-md-12">

        <div class="row">
            <div class="col-md-5" style="float: left; position:relative; margin-top:30px">
                <table class="borderless">

                    <tbody>
                    <tr>
                        <td style="padding-left: 0;">
                            <strong>No :</strong>
                        </td>
                        <td style="padding-left: 30px;"> {{ $dyeingGoodsDelivery->goods_delivery_uid }} </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;">
                            <b>Company Name :</b>
                        </td>
                        <td style="padding-left: 30px;"> {{ $dyeingGoodsDelivery->buyer->name }} </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;">
                            <b>Address :</b>
                        </td>
                        <td style="padding-left: 30px;"> {{ $dyeingGoodsDelivery->buyer->address_1 }} </td>
                    </tr>

                    </tbody>
                </table>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-5" style="float: right; position:relative;margin-top:30px">
                <table class="borderless" style="float: right;">
                    <tbody>
                    <tr>
                        <td style="padding-left: 0;">
                            <b>Date :</b>
                        </td>
                        <td style="padding-left: 30px;"> {{ $dyeingGoodsDelivery->delivery_date }} </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @if(isset($isPdf))
            <div style="display: block; height: 70px"></div>
        @endif
        <table class="reportTable" style="width: 100%;margin-top: 30px;">
            <thead>
            <tr>
                <th>Batch No</th>
                <th>Fabric Type</th>
                <th>Dia & Dia Type</th>
                <th>Finished Dia</th>
                <th>GSM</th>
                <th>No Of Roll</th>
                <th>Colour</th>
                <th>Grey Delivery(KG)</th>
                <th>Finished Weight(KG)</th>
                <th>Order No</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            @php
                $sumNoOfRoll = 0;
                $sumGreyDelivery = 0;
                $sumFinishedWeight = 0;
                $sumRequiredOrderQty = 0;
            @endphp
            @foreach($dyeingGoodsDelivery->subDyeingGoodsDeliveryDetails as $details)
                @if($details->total_roll >0 || $details->delivery_qty>0)
                    <tr>
                        <td class="text-right">{{ $details->batch_no }}</td>
                        <td class="text-right">{{ $details->fabricType->construction_name }}</td>
                        <td class="text-right">{{ $details->dia_type_value }}</td>
                        <td class="text-right">{{ $details->finish_dia }}</td>
                        <td class="text-right"><b>{{ $details->gsm }}</b></td>
                        <td class="text-right">{{ $details->total_roll }}</td>
                        <td class="text-right">{{ $details->color->name }}</td>
                        <td class="text-right">{{ $details->grey_weight_fabric }}</td>
                        <td class="text-right"><b>{{ $details->delivery_qty }}</b></td>
                        <td class="text-right"><b>{{ $details->order_no }}</b></td>
                        <td class="text-right"><b>{{ $details->remarks }}</b></td>
                    </tr>
                    @php
                        $sumNoOfRoll += $details->total_roll;
                        $sumFinishedWeight += $details->delivery_qty;
                        $sumGreyDelivery += $details->grey_weight_fabric;
                        $sumRequiredOrderQty += $details->subDyeingBatchDetail->batch_weight;
                    @endphp
                @endif
            @endforeach
            <tr>
                <th colspan="11">&nbsp;</th>
            </tr>
            <tr>
                <th colspan="5" style="text-align: right">Total</th>
                <th class="text-right">{{ $sumNoOfRoll }}</th>
                <th></th>
                <th class="text-right">{{ $sumGreyDelivery }}</th>
                <th class="text-right">{{ $sumFinishedWeight }}</th>
                <th></th>
                <th></th>
            </tr>
            </tbody>
        </table>

        <br>
        <br>

    </div>
</div>
