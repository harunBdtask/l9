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
                $cutToShipRatio = $reportData['order_qty'] > 0 ?
                    ($reportData['shipment_qty'] / $reportData['order_qty']) * 100 : 0;
            @endphp
            <tr>
                <td rowspan="3" style="width: 30%;">
                    @if($reportData->order['images'] && File::exists('storage/'.$reportData->order['images']))
                        <img
                            src="{{asset('storage/'. $reportData->order['images'])}}"
                            alt="style image" width="200">
                    @else
                        <img src="{{ asset('images/no_image.jpg') }}" width="150"
                             alt="no image">
                    @endif
                </td>
                <td style="background: aliceblue;"><b>BUYER</b></td>
                <td>{{ $reportData->order->buyer->name ?? null }}</td>
            </tr>
            <tr>
                <td style="background: aliceblue"><b>STYLE</b></td>
                <td>{{ $reportData->order->style_name ?? null }}</td>
            </tr>
            <tr>
                <td style="background: aliceblue"><b>SEASON</b></td>
                <td>{{ $reportData->order->season->season_name ?? null }}</td>
            </tr>
            <tr>
                <td colspan="2" style="background: aliceblue"><b>MERCHANDISING</b></td>
                <td rowspan="10">
                    <div class="arrow-chart" style="margin-left: 25%;margin-top: 1%;margin-bottom: 1%;">
                        <section class="medium" style="background: #34495e;">
                            <label>Style Value <br> {{ $reportData->order_value }} USD </label>
                        </section>
                        <section class="low" style="background: #16a085">
                            <label>Budget Value <br> {{ $reportData->budget_value }} USD </label>
                        </section>

                        <section class="medium">
                            <label>Trims Cost <br> {{ $reportData->trims_cost_Value }} USD </label>
                        </section>
                        <section class="high" style="background-color: #3498db;">
                            <label>Other Cost <br> {{ $reportData->others_cost }} USD </label>
                        </section>
                        <section class="high" style="background: #FEA47F;">
                            <label>Fab Booked <br> {{ number_format($reportData->fabric_booked_value,0) }} USD </label>
                        </section>
                        <section class="high" style="background: #fbc531;">
                            <label>Finish Fab <br> {{ $reportData->finish_fab_value }} USD </label>
                        </section>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 5px"><b>STYLE VALUE </b></td>
                <td>{{ $reportData->order_value }} USD</td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 5px"><b>BUDGET VALUE</b></td>
                <td>
                    {{ $reportData->budget_value }} USD
                </td>
            </tr>

            <tr>
                <td style="text-align: left; padding-left: 5px"><b>FAB BOOKED</b></td>
                <td>
                    {{ number_format($reportData->fabric_booked_value,0) }} USD
                </td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 5px"><b>TRIMS COST</b></td>
                <td>
                    {{ $reportData->trims_cost_Value }} USD
                </td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 5px"><b>OTHER COST</b></td>
                <td>
                    {{ $reportData->others_cost }} USD
                </td>
            </tr>
            <tr>
                <td colspan="2" style="background: aliceblue"><b>INVENTORY</b></td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 5px"><b>KNITTING VALUE</b></td>
                <td>{{ $reportData->knitting_value }} USD</td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 5px"><b>DYEING</b></td>
                <td>{{ $reportData->dyeing_qty }} USD</td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 5px"><b>FINISH FAB BALANCE VALUE</b></td>
                <td>
                    {{ $reportData->finish_fab_value }} USD
                </td>
            </tr>
            <tr>
                <td colspan="2" style="background: aliceblue"><b>PRODUCTION</b></td>
                <td rowspan="13" class="text-center">
                    <ul style="margin-left: 20%;" class="tree">
                        <li class="tree-node-width"><span style="background: #eb4d4b;color: white;"><b>Order</b><br>{{ $reportData->order_qty }} USD</span>
                            <ul>
                                <li><span
                                        style="background: #dff9fb;"><b>Cutting</b> <br>{{ $reportData->cutting_value }} USD</span>
                                    <ul>
                                        <li><span style="background: #7ed6df;"><b>Print Sent</b> <br>{{ $reportData->print_sent_value }} USD</span>
                                            <ul>
                                                <li>
                                                    <span style="background: #FEA47F;"><b>Print Receive</b> <br>{{$reportData->print_receive_value}} USD</span>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><span style="background: #CAD3C8"><b>Embr Sent</b> <br>{{ $reportData->embr_sent_value }} USD</span>
                                            <ul>
                                                <li><span style="background: #9AECDB"><b>Embr Receive</b> <br>{{ $reportData->embr_receive_value }} USD</span>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                    <ul>
                                        <li><span
                                                style="background: #d1d8e0"><b>Input</b> <br>{{ $reportData->input_value }} USD</span>
                                            <ul>
                                                <li><span style="background: #66d1ff"><b>Sewing</b> <br>{{ $reportData->sewing_value }} USD</span>
                                                    <ul>
                                                        <li><span style="background: #9AECDB"><b>Finishing</b></span>
                                                            <ul>
                                                                <li><span style="background: #dff9fb"><b>Iron</b> <br>{{ $reportData->iron_value }} USD</span>
                                                                </li>
                                                                <li><span style="background: #dff9fb"><b>Poly</b> <br>{{ $reportData->poly_value }} USD</span>
                                                                </li>
                                                                <li><span
                                                                        style="background: #dff9fb"><b>Packing</b> <br>{{ $reportData->packing_value }} USD</span>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                            <ul>
                                                <li><span style="background: #afd7ff"><b>Shipment</b> <br>{{ $reportData->shipment_value }} USD</span>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 5px"><b>CUTTING VALUE</b></td>
                <td>{{ $reportData->cutting_qty }} USD</td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 5px"><b>PRINT SENT VALUE</b></td>
                <td>{{ $reportData->print_sent_value }} USD</td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 5px"><b>PRINT RECEIVE VALUE</b></td>
                <td>{{ $reportData->print_receive_value }} USD</td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 5px"><b>EMBR SENT VALUE</b></td>
                <td>{{ $reportData->embr_sent_value }} USD</td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 5px"><b>EMBR RECEIVE VALUE</b></td>
                <td>{{ $reportData->embr_receive_value }} USD</td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 5px"><b>INPUT VALUE</b></td>
                <td>{{ $reportData->input_value }} USD</td>
            </tr>

            <tr>
                <td style="text-align: left; padding-left: 5px"><b>SEWING VALUE</b></td>
                <td>{{ $reportData->sewing_value }} USD</td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 5px"><b>PACKING VALUE</b></td>
                <td>{{ $reportData->packing_value }} USD</td>
            </tr>
            <tr>
                <td style="text-align: left; padding-left: 5px"><b>SHIPMENT VALUE</b></td>
                <td>{{ $reportData->shipment_value }} USD</td>
            </tr>
            {{-- <tr>
                <td style="text-align: left; padding-left: 5px"><b>CUT TO SHIP RATIO</b></td>
                <td>{{ $cutToShipRatio }}%</td>
            </tr> --}}
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
