<style>
    .reportTable th, .reportTable td, .borderless th, .borderless td {
            font-size: 14px;
            
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
                <th>Fabric Type</th>
                <th>No Off Roll</th>
                <th>Finished Weight</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">Finished Fabric</td>
                    <td class="text-center">{{ $dyeingGoodsDelivery->total_roll_sum }}</td>
                    <td class="text-center"><b>{{ $dyeingGoodsDelivery->delivery_qty_sum }}</b></td>
                </tr>
            </tbody>
        </table>

        <br>
        <br>

    </div>
</div>
