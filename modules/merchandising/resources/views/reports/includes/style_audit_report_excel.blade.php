<div class="col-md-10 col-md-offset-1">
    @if(isset($reportData))
        <table class="reportTable">
            <tbody>
            @php
                $fab_req = isset($reportData->fabric_req_qty) ? collect($reportData->fabric_req_qty)
                            ->map(function($item){
                                return number_format($item['qty'], 0)." ".$item['uom_value'];
                            })->implode(', ') : '';
                $yarn_issue = isset($reportData->yarn_issue_qty) ? collect($reportData->yarn_issue_qty)
                            ->map(function($item){
                                return number_format($item['qty'], 0)." ".$item['uom_value'];
                            })->implode(', ') : '';
                $fab_booked = isset($reportData->fabric_booked_qty) ? collect($reportData->fabric_booked_qty)
                            ->map(function($item){
                                return number_format($item['qty'], 0)." ".$item['uom_value'];
                            })->implode(', ') : '';
                $finish_fab_store = isset($reportData->finish_fab_qty) ? collect($reportData->finish_fab_qty)
                            ->map(function($item){
                                return number_format($item['qty'], 0)." ".$item['uom_value'];
                            })->implode(', ') : '';
                $cutToShipRatio = ($reportData['shipment_qty'] / $reportData['order_qty']) * 100;
            @endphp
            <tr>
                
                <td colspan="2" style="background: aliceblue;"><b>BUYER</b></td>
                <td>{{ $reportData->order->buyer->name ?? null }}</td>
            </tr>
            <tr>
                <td colspan="2" style="background: aliceblue"><b>STYLE</b></td>
                <td>{{ $reportData->order->style_name ?? null }}</td>
            </tr>
            <tr>
                <td colspan="2" style="background: aliceblue"><b>SEASON</b></td>
                <td>{{ $reportData->order->season->season_name ?? null }}</td>
            </tr>
            <tr>
                <td colspan="3" style="background: aliceblue" class="text-center"><b>MERCHANDISING</b></td>
               
            </tr>
            <tr>
                <td colspan="2" style="text-align: left; padding-left: 5px"><b>ORDER QTY</b></td>
                <td>{{ $reportData->order_qty }} PCS</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: left; padding-left: 5px"><b>FAB REQ</b></td>
                <td>
                    {{ $fab_req }}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: left; padding-left: 5px"><b>YARN ISSUED</b></td>
                <td>
                    {{ $yarn_issue }}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: left; padding-left: 5px"><b>FAB BOOKED</b></td>
                <td>
                    {{ $fab_booked }}
                </td>
            </tr>
            <tr>
                <td colspan="3" style="background: aliceblue" class="text-center"><b>INVENTORY</b></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: left; padding-left: 5px"><b>KNITTING QTY</b></td>
                <td>{{ $reportData->knitting_qty }}</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: left; padding-left: 5px"><b>DYEING</b></td>
                <td>{{ $reportData->dyeing_qty }}</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: left; padding-left: 5px"><b>FINISH FAB STORE</b></td>
                <td>
                    {{ $finish_fab_store }}
                </td>
            </tr>
            <tr>
                <td colspan="3" style="background: aliceblue" class="text-center"><b>PRODUCTION</b></td>
                
            </tr>
            <tr>
                <td colspan="2" style="text-align: left; padding-left: 5px"><b>CUTTING QTY</b></td>
                <td>{{ $reportData->cutting_qty }} PCS</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: left; padding-left: 5px"><b>PRINT SENT QTY</b></td>
                <td>{{ $reportData->print_sent_qty }} PCS</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: left; padding-left: 5px"><b>PRINT RECEIVE QTY</b></td>
                <td>{{ $reportData->print_receive_qty }} PCS</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: left; padding-left: 5px"><b>EMBR SENT QTY</b></td>
                <td>{{ $reportData->embr_sent_qty }} PCS</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: left; padding-left: 5px"><b>EMBR RECEIVE QTY</b></td>
                <td>{{ $reportData->embr_receive_qty }} PCS</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: left; padding-left: 5px"><b>INPUT QTY</b></td>
                <td>{{ $reportData->input_qty }} PCS</td>
            </tr>

            <tr>
                <td colspan="2" style="text-align: left; padding-left: 5px"><b>SEWING QTY</b></td>
                <td>{{ $reportData->sewing_qty }} PCS</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: left; padding-left: 5px"><b>IRON QTY</b></td>
                <td>{{ $reportData->iron_qty }} PCS</td>
            </tr>

            <tr>
                <td colspan="2" style="text-align: left; padding-left: 5px"><b>POLY QTY</b></td>
                <td>{{ $reportData->poly_qty }} PCS</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: left; padding-left: 5px"><b>PACKING QTY</b></td>
                <td>{{ $reportData->packing_qty }} PCS</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: left; padding-left: 5px"><b>SHIPMENT QTY</b></td>
                <td>{{ $reportData->shipment_qty }} PCS</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: left; padding-left: 5px"><b>CUT TO SHIP RATIO</b></td>
                <td>{{ $cutToShipRatio }}%</td>
            </tr>
            </tbody>
        </table>
    @else
        <p class="text-danger text-center"><b>No Data Found!</b></p>
    @endif
</div>
<style>
    .tree-node-width {
        width: 45%;
    }

    .tree,
    .tree ul,
    .tree li {
        list-style: none;
        margin: 0;
        padding: 0;
        position: relative;
    }

    .tree {
        margin: 0 0 1em;
        text-align: center;
    }

    .tree,
    .tree ul {
        display: table;
    }

    .tree ul {
        width: 100%;
    }

    .tree li {
        display: table-cell;
        padding: .5em 0;
        vertical-align: top;
    }

    .tree li:before {
        outline: solid 1px #666;
        content: "";
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
    }

    .tree li:first-child:before {
        left: 50%;
    }

    .tree li:last-child:before {
        right: 50%;
    }

    .tree code,
    .tree span {

        border: solid .1em #666;
        border-radius: .2em;
        display: inline-block;
        margin: 0 .2em .5em;
        padding: .2em .5em;
        position: relative;
    }

    .tree ul:before,
    .tree code:before,
    .tree span:before {
        outline: solid 1px #666;
        content: "";
        height: .5em;
        left: 50%;
        position: absolute;
    }

    .tree ul:before {
        top: -.5em;
    }

    .tree code:before,
    .tree span:before {
        top: -.55em;
    }

    .tree > li {
        margin-top: 0;
    }

    .tree > li:before,
    .tree > li:after,
    .tree > li > code:before,
    .tree > li > span:before {
        outline: none;
    }

    * {
        box-sizing: border-box;
    }

    .arrow-chart {
        width: 300px;
        height: 300px;
        position: relative;
        text-align: center;
        border-radius: 100%;
        overflow: hidden;
        clip-path: circle(50% at 50% 50%);
        /*   -ms-transform: scale(0.35, 0.35);
          -webkit-transform: scale(0.35, 0.35);
          transform: scale(0.35, 0.35); */
    }

    .arrow-chart:after {
        content: "";
        display: block;
        background: #FFFFFF;
        width: 50%;
        height: 50%;
        z-index: 10;
        position: absolute;
        border-radius: 100%;
        top: 25%;
        left: 25%;
        /*   opacity: .8; */
        /*   display: none; */
    }

    .arrow-chart section {
        height: 48%;
        width: 55%;
        position: absolute;
        text-align: center;
        color: #CCCCCC;
        background-color: currentColor;
        display: flex;
        align-items: center;
        justify-content: center;
        -webkit-clip-path: polygon(75% 0%, 100% 50%, 75% 100%, 0% 100%, 25% 50%, 0% 0%);
        clip-path: polygon(75% 0%, 100% 50%, 75% 100%, 0% 100%, 25% 50%, 0% 0%);
    }

    .arrow-chart section label {
        font-size: .7rem;
        font-weight: bold;
        position: relative;
        width: 50%;
        padding: 0 5%;
        margin: 25% 0 0 0;
        display: block;
        color: #FFFFFF;
    }

    .arrow-chart section:nth-child(1) {
        transform: rotate(0);
        margin-left: 44%;
        margin-top: -12%
    }

    .arrow-chart section:nth-child(1) label {
        transform: rotate(0);
    }

    .arrow-chart section:nth-child(2) {
        /*   display: none; */
        transform: rotate(60deg);
        margin-left: 66%;
        margin-top: 26%;
    }

    .arrow-chart section:nth-child(2) label {
        transform: rotate(-60deg);
    }

    .arrow-chart section:nth-child(3) {
        /*   display: none; */
        transform: rotate(120deg);
        margin-left: 44%;
        margin-top: 64%;
    }

    .arrow-chart section:nth-child(3) label {
        transform: rotate(-120deg);
    }

    .arrow-chart section:nth-child(4) {
        /*   display:none; */
        transform: rotate(180deg);
        margin-left: .25%;
        margin-top: 64%;
    }

    .arrow-chart section:nth-child(4) label {
        transform: rotate(-180deg);
    }

    .arrow-chart section:nth-child(5) {
        /*   display:none; */
        transform: rotate(240deg);
        margin-left: -21.5%;
        margin-top: 26%;
    }

    .arrow-chart section:nth-child(5) label {
        transform: rotate(-240deg);
    }

    .arrow-chart section:nth-child(6) {
        /*   display:none; */
        transform: rotate(300deg);
        margin-left: 0%;
        margin-top: -12%;
    }

    .arrow-chart section:nth-child(6) label {
        transform: rotate(-300deg);
    }

    figure ul {
        list-style: none;
    }

    figure li:before {
        content: "\2022";
        display: inline-block;
        width: 2rem;
        margin-left: -1rem;
        font-size: 5rem;
        vertical-align: middle;
    }

    figure li {
        height: 2rem;
    }

    figure li span {
        color: #424242;
        display: inline-block;
    }

    /* Color Palette from https://www.materialui.co/colors */
    .high {
        color: #4CAF50 !important;
    }

    .medium {
        color: #FF9800 !important;
    }

    .low {
        color: #FF5722 !important;
    }
</style>
