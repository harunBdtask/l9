<table class="reportTable">
    <thead>
    <tr>
        <td style="background-color: aliceblue"><strong>DATE</strong></td>
        <td style="background-color: aliceblue"><strong>M/C NO</strong></td>
        <td style="background-color: aliceblue"><strong>BUYER</strong></td>
        <td style="background-color: aliceblue"><strong>ORDER</strong></td>
        <td style="background-color: aliceblue"><strong>F/TYPE</strong></td>
        <td style="background-color: aliceblue"><strong>Dia TYPE</strong></td>
        <td style="background-color: aliceblue"><strong>BATCH</strong></td>
        <td style="background-color: aliceblue"><strong>COLOR</strong></td>
        <td style="background-color: aliceblue"><strong>PROD. QTY</strong></td>
        <td style="background-color: aliceblue"><strong>Total QTY</strong></td>
        <td style="background-color: aliceblue"><strong>TUBE</strong></td>
        <td style="background-color: aliceblue"><strong>LOAD. TIME</strong></td>
        <td style="background-color: aliceblue"><strong>UNLOAD. TIME</strong></td>
        <td style="background-color: aliceblue"><strong>DUR.</strong></td>
        <td style="background-color: aliceblue"><strong>REMARKS</strong></td>
        <td style="background-color: aliceblue"><strong>DYES &amp; CHEM. COST</strong></td>
        <td style="background-color: aliceblue"><strong>OVERHEAD COST</strong></td>
        <td style="background-color: aliceblue"><strong>T. COST</strong></td>
        <td style="background-color: aliceblue"><strong>PER KG COST</strong></td>
        <td style="background-color: aliceblue"><strong>RATE</strong></td>
        <td style="background-color: aliceblue"><strong>T. VALUE</strong></td>
    </tr>
    </thead>
    <tbody>
    @php
        $totalCostRowCount = 0;
        $rateRowCount = 0;
        $totalValueRowCount = 0;
        $sumOfTotalCost = 0;
        $sumOfRate = 0;
        $sumOfTotalValue = 0;
    @endphp
    @foreach($dyeingProductionDetails as $batchWiseDyeingProductionDetails)
        @php
            $totalQty = $batchWiseDyeingProductionDetails->sum('production_qty');
        @endphp
        @foreach($batchWiseDyeingProductionDetails as $dyeingProductionDetail)
            @php
                $batchRowCount = $batchWiseDyeingProductionDetails->count();
                $totalProductionQty = $batchWiseDyeingProductionDetails->sum('production_qty');
//                dump($totalProductionQty);
            @endphp
            <tr>
                @if($loop->first)
                    <td rowspan="{{ $batchRowCount }}">
                        {{$dyeingProductionDetail['date'] }}
                    </td>
                    <td rowspan="{{ $batchRowCount }}">{{ $dyeingProductionDetail['mc_no'] }}</td>
                    <td rowspan="{{ $batchRowCount }}">{{ $dyeingProductionDetail['buyer'] }}</td>
                @endif
                <td>{{ $dyeingProductionDetail['order'] }}</td>
                <td>{{ $dyeingProductionDetail['fabric_type'] }}</td>
                <td>{{ $dyeingProductionDetail['dia_type'] }}</td>
                @if($loop->first)
                    <td rowspan="{{ $batchRowCount }}">{{ $dyeingProductionDetail['batch_no'] }}</td>
                    <td rowspan="{{ $batchRowCount }}">{{ $dyeingProductionDetail['color'] }}</td>
                @endif

                <td style="text-align: right">{{ $dyeingProductionDetail['production_qty'] }}</td>
                @if($loop->first)
                    <td rowspan="{{ $batchRowCount }}" style="text-align: right">{{ $totalQty }}</td>
                @endif
                <td style="text-align: right">{{ $dyeingProductionDetail['tube'] }}</td>
                <td>{{ $dyeingProductionDetail['loading_time'] }}</td>
                <td>{{ $dyeingProductionDetail['unloading_time'] }}</td>
                <td>{{ $dyeingProductionDetail['duration'] }}</td>
                <td>{{ $dyeingProductionDetail['remarks'] }}</td>
                @if($loop->first)

                    @php
                        $sumOfTotalCost += $dyeingProductionDetail['total_cost'];
                        $sumOfRate += $dyeingProductionDetail['rate'];
                        $sumOfTotalValue += $dyeingProductionDetail['total_value'];
                        $totalCostRowCount += 1;
                        $rateRowCount += 1;
                        $totalValueRowCount += 1;
                    @endphp

                    <td rowspan="{{ $batchRowCount }}" style="text-align: right">
                        {{ round($dyeingProductionDetail['dyes_chemical_cost'], 3) }}
                    </td>
                    <td rowspan="{{ $batchRowCount }}" style="text-align: right">
                        {{ round($dyeingProductionDetail['overhead_cost'], 3) }}
                    </td>
                    <td rowspan="{{ $batchRowCount }}" style="text-align: right">
                        {{ round($dyeingProductionDetail['total_cost'], 3) }}
                    </td>
                    <td rowspan="{{ $batchRowCount }}" style="text-align: right">
                        {{ round($dyeingProductionDetail['per_keg_cost'], 3) }}
                    </td>
                    @endif
                    <td style="text-align: right">
                        {{ round($dyeingProductionDetail['rate'], 3) }}
                    </td>
                    @if($loop->first)
                    <td rowspan="{{ $batchRowCount }}" style="text-align: right">
                        {{ round($totalProductionQty * $dyeingProductionDetail['rate'], 3) }}
                    </td>
                @endif
            </tr>
        @endforeach
    @endforeach
    <tr>
        <td colspan="19" style="padding-top: 10px"></td>
    </tr>
    <tr>
        <td colspan="7" style="background-color:gainsboro;text-align: right">
            <strong>G. TOTAL</strong>
        </td>
        <td style="background-color:gainsboro;text-align: right">
            <strong>{{ $dyeingProductionDetails->flatten(1)->sum('production_qty') }}</strong>
        </td>
        <td style="background-color:gainsboro;text-align: right">
            <strong>{{ $dyeingProductionDetails->flatten(1)->sum('tube') }}</strong>
        </td>
        <td colspan="6" style="background-color:gainsboro;"></td>
        <td style="background-color:gainsboro;text-align: right">
            <strong>{{ round($sumOfTotalCost, 3) }}</strong>
        </td>
        <td style="background-color:gainsboro;text-align: right">
        </td>
        <td style="background-color:gainsboro;text-align: right">
            <strong>{{ round($sumOfRate, 3) }}</strong>
        </td>
        <td style="background-color:gainsboro;text-align: right">
            <strong>{{ round($sumOfTotalValue, 3) }}</strong>
        </td>
    </tr>
    <tr>
        <td colspan="15" style="background-color: lightgray;text-align: right">
            <strong>AVG.</strong>
        </td>
        <td style="background-color: lightgray;text-align: right">
            <strong>{{ round($sumOfTotalCost/$totalCostRowCount, 3) }}</strong>
        </td>
        <td style="background-color: lightgray;text-align: right">
            <strong></strong>
        </td>
        <td style="background-color: lightgray;text-align: right">
            <strong>{{ round($sumOfRate/$rateRowCount, 3) }}</strong>
        </td>
        <td style="background-color: lightgray;text-align: right">
            <strong>{{ round($sumOfTotalValue/$totalValueRowCount, 3) }}</strong>
        </td>
    </tr>
    </tbody>
</table>
