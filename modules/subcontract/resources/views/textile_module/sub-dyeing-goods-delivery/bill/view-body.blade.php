<style>
    .reportTable th, .reportTable td, .borderless th, .borderless td {
        font-size: 14px;

    }

    .reportTable th, .reportTable td {
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
                            <strong>No</strong>
                        </td>
                        <td style="padding-left: 30px;">
                            <strong>:</strong> {{ $dyeingGoodsDelivery->goods_delivery_uid }} </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;">
                            <b>Company Name</b>
                        </td>
                        <td style="padding-left: 30px;"><strong>:</strong> {{ $dyeingGoodsDelivery->buyer->name }} </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;">
                            <b>Address</b>
                        </td>
                        <td style="padding-left: 30px;"><strong>:</strong> {{ $dyeingGoodsDelivery->buyer->address_1 }}
                        </td>
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
                            <b>Date</b>
                        </td>
                        <td style="padding-left: 30px;"><strong>:</strong> {{ $dyeingGoodsDelivery->delivery_date }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @if(isset($isPdf))
            <div style="display: block; height: 100px"></div>
        @endif
        <div class="table-responsive">
            <table class="reportTable" style="width: 100%;margin-top: 10px">
                <thead>
                <tr>
                    <th>Fabric Type</th>
                    <th>Dia & Dia Type</th>
                    <th>Colour</th>
                    <th>Batch No</th>
                    <th>Grey Weight</th>
                    <th>Rate
                        @if($dyeingGoodsDelivery->currency == 1)
                            <span>USD</span>
                        @elseif($dyeingGoodsDelivery->currency == 2)
                            <span>Taka</span>
                        @endif
                    </th>
                    <th>Total Value</th>
                    <th>Order No</th>
                    <th>Shade%</th>
                    <th>Remarks</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $sumGreyWeight = 0;
                    $sumRateTaka = 0;
                    $sumValueTaka = 0;
                    $sumRequiredOrderQty = 0;
                @endphp
                @foreach($dyeingGoodsDelivery->subDyeingGoodsDeliveryDetails as $details)
                    @if($details->total_roll >0 || $details->delivery_qty>0)
                        <tr>
                            <td class="text-center">{{ $details->fabricType->construction_name }}</td>
                            <td class="text-right">{{ $details->dia_type_value }}</td>
                            <td class="text-center">{{ $details->color->name }}</td>
                            <td class="text-center">{{ $details->batch_no }}</td>
                            <td class="text-center">{{ $details->grey_weight_fabric }}</td>
                            <td class="text-center">{{ $details->rate }}</td>
                            <td class="text-center">{{ $details->total_value }}</td>
                            <td class="text-center">{{ $details->order_no }}</td>
                            <td class="text-center">{{ $details->shade }}</td>
                            <td class="text-center"><b>{{ $details->remarks }}</b></td>
                        </tr>
                        @php
                            $sumRateTaka += $details->rate;
                            $sumValueTaka += $details->total_value;
                            $sumGreyWeight += $details->grey_weight_fabric;
                            $sumRequiredOrderQty += $details->subDyeingBatchDetail->batch_weight;
                        @endphp
                    @endif
                @endforeach
                <tr>
                    <td colspan="10">&nbsp;</td>
                </tr>

                <tr>
                    <td colspan="4" style="text-align: center"><b>Total</b></td>
                    <td class="text-center"><b>{{ $sumGreyWeight }}</b></td>
                    <td class="text-center"></td>
                    <td class="text-center"><b>{{ $sumValueTaka }}</b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
        @if(isset($isPdf))
            <div style="height: 20px"></div>
        @endif
        @php
            $formatter = new \NumberFormatter( locale_get_default(), \NumberFormatter::SPELLOUT );
        @endphp
        <strong>In words
            (Total): {{strtoupper($formatter->format($sumValueTaka))}}
            TK</strong>
        <br>
        <br>

    </div>
</div>
