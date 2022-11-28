<div class="row p-x-1">
    <div class="col-md-12">

        <div class="row">
            <div class="col-md-5" style="float: left; position:relative; margin-top:30px">
                <table class="borderless">

                    <tbody>
                    <tr>
                        <td style="padding-left: 0;">
                            <b>Buyer :</b>
                        </td>
                        <td style="padding-left: 30px;"> {{ $poDetails[0]['buyer'] }}  </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;">
                            <b>Style :</b>
                        </td>
                        <td style="padding-left: 30px;"> {{ $poDetails[0]['style'] }} </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0;">
                            <b>PO No : </b>
                        </td>
                        <td style="padding-left: 30px;"> {{ $poDetails[0]['po'] }} </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-2"></div>
        </div>

        <table class="reportTable table-responsive" style="width: 100%;margin-top: 30px;">
            <thead>

            <tr>
                <th rowspan="2" style="font-weight: bold;">COLOR</th>
                <th rowspan="2" style="font-weight: bold;">O. Qty</th>
                <th rowspan="2" style="font-weight: bold;">Destination</th>
                <th rowspan="2" style="font-weight: bold;">Tag Type</th>
                <th rowspan="2" style="font-weight: bold;">Assort/Solid</th>
                <th rowspan="2" style="font-weight: bold;">No of Poly/Carton</th>
                <th rowspan="2" style="font-weight: bold;">Qty/Carton</th>
                <th rowspan="2" style="font-weight: bold;">No of Boxes(Qty)</th>
                <th rowspan="2" style="font-weight: bold;">Blister/Kit Per Carton</th>
                <th rowspan="2" style="font-weight: bold;">Kit/Bc CTN</th>
                <th colspan="2" style="font-weight: bold;">Cartons No</th>
                <th colspan="3" style="font-weight: bold;">Measurement</th>
                <th rowspan="2" style="font-weight: bold;">BC Height</th>
                <th rowspan="2" style="font-weight: bold;">G.W.1 box weight KG</th>
                <th rowspan="2" style="font-weight: bold;">BC G.W</th>
                <th rowspan="2" style="font-weight: bold;">N.W.1 box weight KG</th>
                <th rowspan="2" style="font-weight: bold;">BC N.W</th>
                <th rowspan="2" style="font-weight: bold;">M3/CBU</th>
                <th rowspan="2" style="font-weight: bold;">TYPE OF SHIPMENT</th>
                <th style="font-weight: bold;" colspan="{{ count($sizes) }}">SIZE RATIO</th>
            </tr>
            <tr>
                <th style="font-weight: bold;">FROM</th>
                <th style="font-weight: bold;">TO</th>
                <th style="font-weight: bold;">L</th>
                <th style="font-weight: bold;">W</th>
                <th style="font-weight: bold;">H</th>
                @foreach($sizes as $size)
                    <th style="font-weight: bold;">{{ $size['name'] }}</th>
                @endforeach
            </tr>

            </thead>
            <tbody>
            @foreach($poDetails as $detail)
                <tr>
                    <td>{{ $detail['color'] }}</td>
                    <td>{{ $detail['order_qty'] }}</td>
                    <td>{{ $detail['destination'] }}</td>
                    <td>{{ $detail['tag_type_value'] }}</td>
                    <td>{{ $detail['assort_solid'] }}</td>
                    <td>{{ $detail['no_of_carton'] }}</td>
                    <td>{{ $detail['qty_per_carton'] }}</td>
                    <td>{{ $detail['no_of_boxes'] }}</td>
                    <td>{{ $detail['blister_kit_carton'] }}</td>
                    <td>{{ $detail['kit_bc_carton'] }}</td>
                    <td>{{ $detail['carton_no_from'] }}</td>
                    <td>{{ $detail['carton_no_to'] }}</td>
                    <td>{{ $detail['measurement_l'] }}</td>
                    <td>{{ $detail['measurement_w'] }}</td>
                    <td>{{ $detail['measurement_h'] }}</td>
                    <td>{{ $detail['bc_height'] }}</td>
                    <td>{{ $detail['gw_box_weight'] }}</td>
                    <td>{{ $detail['bc_gw'] }}</td>
                    <td>{{ $detail['nw_box_weight'] }}</td>
                    <td>{{ $detail['bc_nw'] }}</td>
                    <td>{{ $detail['m3_cbu'] }}</td>
                    <td>{{ $detail['type_of_shipment_value'] }}</td>
                    @foreach($sizes as $size)
                        <td>{{ $detail['sizes'][$size['name']]['qty'] }}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>


    </div>
</div>
