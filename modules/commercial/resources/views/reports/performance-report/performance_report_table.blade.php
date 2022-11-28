<div class="row">
    <div style="width: 50%">
        <table class="borderless">
            <tbody>
            <tr>
                <td><b>Company :</b></td>
                <td>{{ $company ?? '' }}</td>
            </tr>
            <tr>
                <td><b>Buyer :</b></td>
                <td>{{ $buyer ?? '' }}</td>
            </tr>
            <tr>
                <td><b>Style :</b></td>
                <td>{{ $style ?? '' }}</td>
            </tr>
            <tr>
                <td><b>Order Qty :</b></td>
                <td>{{ round(($total_qty ?? 0)) }}</td>
            </tr>
            <tr>
                <td><b>Order Value :</b></td>
                <td>{{ number_format(($total_fob_value ?? 0), 2) }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<table class="reportTable">
    <thead>
    <tr>
        <td style="background-color: aliceblue" rowspan="2" colspan="2"><b>FABRIC (PURCHASE) DESCRIPTION</b></td>
        <td style="background-color: aliceblue" colspan="2"><b>As Per Budget</b></td>
        <td style="background-color: aliceblue" colspan="2"><b>Ordered</b></td>
        <td style="background-color: aliceblue" colspan="2"><b>Balance</b></td>
    </tr>
    <tr>
        <td style="background-color: aliceblue"><b>Quantity</b></td>
        <td style="background-color: aliceblue; text-align: right"><b>Value</b></td>
        <td style="background-color: aliceblue"><b>Quantity</b></td>
        <td style="background-color: aliceblue;text-align: right"><b>Value</b></td>
        <td style="background-color: aliceblue"><b>Quantity</b></td>
        <td style="background-color: aliceblue;text-align: right"><b>Value</b></td>
    </tr>
    </thead>
    <tbody>
    @if(isset($fabricPurchaseDetails['details']))
        @foreach($fabricPurchaseDetails['details'] as $fabricDetail)
            <tr>
                <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                <td class="text-left">{{ $fabricDetail['item'] }}</td>
                <td style="color: {{$fabricDetail['budget_qty'] < 0 ? 'red' : 'black'}}">{{ $fabricDetail['budget_qty'] }}</td>
                <td style="text-align: right; color: {{$fabricDetail['budget_value'] < 0 ? 'red' : 'black'}}">
                    {{ $fabricDetail['budget_value'] }}</td>
                <td style="background-color: #f3f3f3; color: {{$fabricDetail['booking_qty'] < 0 ? 'red' : 'black'}}">
                    {{ $fabricDetail['booking_qty'] }}</td>
                <td style="background-color: #f3f3f3;text-align: right;
                color: {{$fabricDetail['booking_value'] < 0 ? 'red' : 'black'}}">
                    {{ $fabricDetail['booking_value'] }}
                </td>
                <td style="color: {{$fabricDetail['balance_qty'] < 0 ? 'red' : 'black'}}">
                    {{ $fabricDetail['balance_qty'] }}</td>
                <td style="text-align: right; color: {{$fabricDetail['balance_value'] < 0 ? 'red' : 'black'}}">
                    {{ $fabricDetail['balance_value'] }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="8"></td>
        </tr>
        <tr>
            <td style="background-color: gainsboro" colspan="2"><b>Total</b></td>
            <td style="background-color: gainsboro;"></td>
            <td style="background-color: gainsboro;text-align: right;
            color: {{ isset($fabricPurchaseDetails['total_budget_value']) && $fabricPurchaseDetails['total_budget_value'] < 0 ? 'red' : 'black' }}">
                <b>{{ number_format($fabricPurchaseDetails['total_budget_value'] ?? 0,2) }}</b>
            </td>
            <td style="background-color: gainsboro;"></td>
            <td style="background-color: gainsboro;text-align: right;
            color: {{ isset($fabricPurchaseDetails['total_booking_value']) && $fabricPurchaseDetails['total_booking_value'] < 0 ? 'red' : 'black' }}">
                <b>{{ number_format($fabricPurchaseDetails['total_booking_value'] ?? 0,2) }}</b>
            </td>
            <td style="background-color: gainsboro;"></td>
            <td style="background-color: gainsboro;text-align: right;
            color: {{ isset($fabricPurchaseDetails['total_balance_value']) && $fabricPurchaseDetails['total_balance_value'] < 0 ? 'red' : 'black' }}">
                <b>{{ number_format($fabricPurchaseDetails['total_balance_value'] ?? 0, 2) }}</b>
            </td>
        </tr>
    @else
        <tr>
            <td colspan="8">No data found</td>
        </tr>
    @endif
    </tbody>
</table>
<br>
<table class="reportTable">
    <thead>
    <tr>
        <td style="background-color: aliceblue" rowspan="2" colspan="2"><b>FABRIC (PRODUCTION) DESCRIPTION</b></td>
        <td style="background-color: aliceblue" colspan="2"><b>As Per Budget</b></td>
        <td style="background-color: aliceblue" colspan="2"><b>Ordered</b></td>
        <td style="background-color: aliceblue" colspan="2"><b>Balance</b></td>
    </tr>
    <tr>
        <td style="background-color: aliceblue"><b>Quantity</b></td>
        <td style="background-color: aliceblue; text-align: right"><b>Value</b></td>
        <td style="background-color: aliceblue"><b>Quantity</b></td>
        <td style="background-color: aliceblue;text-align: right"><b>Value</b></td>
        <td style="background-color: aliceblue"><b>Quantity</b></td>
        <td style="background-color: aliceblue;text-align: right"><b>Value</b></td>
    </tr>
    </thead>
    <tbody>
    @if(isset($fabricProductionDetails['details']))
        @foreach($fabricProductionDetails['details'] as $fabricDetail)
            <tr>
                <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                <td class="text-left">{{ $fabricDetail['item'] }}</td>
                <td style="color: {{$fabricDetail['budget_qty'] < 0 ? 'red' : 'black'}}">{{ $fabricDetail['budget_qty'] }}</td>
                <td style="text-align: right; color: {{$fabricDetail['budget_value'] < 0 ? 'red' : 'black'}}">
                    {{ $fabricDetail['budget_value'] }}</td>
                <td style="background-color: #f3f3f3; color: {{$fabricDetail['booking_qty'] < 0 ? 'red' : 'black'}}">
                    {{ $fabricDetail['booking_qty'] }}</td>
                <td style="background-color: #f3f3f3;text-align: right;
                color: {{$fabricDetail['booking_value'] < 0 ? 'red' : 'black'}}">
                    {{ $fabricDetail['booking_value'] }}
                </td>
                <td style="color: {{$fabricDetail['balance_qty'] < 0 ? 'red' : 'black'}}">
                    {{ $fabricDetail['balance_qty'] }}</td>
                <td style="text-align: right; color: {{$fabricDetail['balance_value'] < 0 ? 'red' : 'black'}}">
                    {{ $fabricDetail['balance_value'] }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="8"></td>
        </tr>
        <tr>
            <td style="background-color: gainsboro" colspan="2"><b>Total</b></td>
            <td style="background-color: gainsboro;"></td>
            <td style="background-color: gainsboro;text-align: right;
            color: {{ isset($fabricProductionDetails['total_budget_value']) && $fabricProductionDetails['total_budget_value'] < 0 ? 'red' : 'black' }}">
                <b>{{ number_format($fabricProductionDetails['total_budget_value'] ?? 0,2) }}</b>
            </td>
            <td style="background-color: gainsboro;"></td>
            <td style="background-color: gainsboro;text-align: right;
            color: {{ isset($fabricProductionDetails['total_booking_value']) && $fabricProductionDetails['total_booking_value'] < 0 ? 'red' : 'black' }}">
                <b>{{ number_format($fabricProductionDetails['total_booking_value'] ?? 0,2) }}</b>
            </td>
            <td style="background-color: gainsboro;"></td>
            <td style="background-color: gainsboro;text-align: right;
            color: {{ isset($fabricProductionDetails['total_balance_value']) && $fabricProductionDetails['total_balance_value'] < 0 ? 'red' : 'black' }}">
                <b>{{ number_format($fabricProductionDetails['total_balance_value'] ?? 0, 2) }}</b>
            </td>
        </tr>
    @else
        <tr>
            <td colspan="8">No data found</td>
        </tr>
    @endif
    </tbody>
</table>
<br>
<table class="reportTable">
    <thead>
    <tr>
        <td style="background-color: aliceblue" rowspan="2" colspan="2"><b>ACCESSORIES DESCRIPTION</b></td>
        <td style="background-color: aliceblue" colspan="2"><b>As Per Budget</b></td>
        <td style="background-color: aliceblue" colspan="2"><b>Ordered</b></td>
        <td style="background-color: aliceblue" colspan="2"><b>Balance</b></td>
    </tr>
    <tr>
        <th style="background-color: aliceblue"><b>Quantity</b></th>
        <th style="background-color: aliceblue; text-align: right"><b>Value</b></th>
        <th style="background-color: aliceblue"><b>Quantity</b></th>
        <th style="background-color: aliceblue; text-align: right"><b>Value</b></th>
        <th style="background-color: aliceblue"><b>Quantity</b></th>
        <th style="background-color: aliceblue; text-align: right"><b>Value</b></th>
    </tr>
    </thead>
    <tbody>
    @if(isset($trimsDetails['details']))
        @foreach($trimsDetails['details'] as $trimDetail)
            <tr>
                <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                <td class="text-left">{{$trimDetail['item']}}</td>
                <td style="color: {{$trimDetail['budget_qty'] < 0 ? 'red' : 'black'}}">
                    {{$trimDetail['budget_qty']}}</td>
                <td style="text-align: right;color: {{$trimDetail['budget_value'] < 0 ? 'red' : 'black'}}">
                    {{$trimDetail['budget_value']}}</td>
                <td style="background-color: #f3f3f3;color: {{$trimDetail['booking_qty'] < 0 ? 'red' : 'black'}}">
                    {{$trimDetail['booking_qty']}}</td>
                <td style="background-color: #f3f3f3; text-align: right;
                color: {{$trimDetail['booking_value'] < 0 ? 'red' : 'black'}}">
                    {{$trimDetail['booking_value']}}</td>
                <td style="color: {{$trimDetail['balance_qty'] < 0 ? 'red' : 'black'}}">
                    {{$trimDetail['balance_qty']}}</td>
                <td style="color: {{$trimDetail['balance_value'] < 0 ? 'red' : 'black'}};text-align: right">
                    {{$trimDetail['balance_value']}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="8"></td>
        </tr>
        <tr>
            <td style="background-color: gainsboro" colspan="2"><b>Total</b></td>
            <td style="background-color: gainsboro; color: {{$trimsDetails['total_budget_qty'] < 0 ? 'red' : 'black'}}"></td>
            <td style="text-align: right;background-color: gainsboro; color: {{$trimsDetails['total_budget_value'] < 0 ? 'red' : 'black'}}">
                <b>{{ number_format($trimsDetails['total_budget_value'],2) }}</b>
            </td>
            <td style="background-color: gainsboro; color: {{$trimsDetails['total_booking_qty'] < 0 ? 'red' : 'black'}}"></td>
            <td style="text-align: right;background-color: gainsboro; color: {{$trimsDetails['total_booking_value'] < 0 ? 'red' : 'black'}}">
                <b>{{ number_format($trimsDetails['total_booking_value'],2) }}</b>
            </td>
            <td style="background-color: gainsboro; color: {{$trimsDetails['total_balance_qty'] < 0 ? 'red' : 'black'}}"></td>
            <td style="text-align: right;background-color: gainsboro; color: {{$trimsDetails['total_balance_value'] < 0 ? 'red' : 'black'}}">
                <b>{{ number_format($trimsDetails['total_balance_value'], 2) }}</b>
            </td>
        </tr>
    @else
        <tr>
            <td colspan="8">
                No data found
            </td>
        </tr>
    @endif
    </tbody>
</table>
<br>
<table class="reportTable">
    <thead>
    <tr>
        <td style="background-color: aliceblue" rowspan="2" colspan="2"><b>OTHERS DESCRIPTION</b></td>
        <td style="background-color: aliceblue" colspan="2"><b>As Per Budget</b></td>
        <td style="background-color: aliceblue" colspan="2"><b>Ordered</b></td>
        <td style="background-color: aliceblue" colspan="2"><b>Balance</b></td>
    </tr>
    <tr>
        <td style="background-color: aliceblue"><b>Quantity</b></td>
        <td style="background-color: aliceblue; text-align: right"><b>Value</b></td>
        <td style="background-color: aliceblue"><b>Quantity</b></td>
        <td style="background-color: aliceblue; text-align: right"><b>Value</b></td>
        <td style="background-color: aliceblue"><b>Quantity</b></td>
        <td style="background-color: aliceblue; text-align: right"><b>Value</b></td>
    </tr>
    </thead>
    <tbody>
    @if(isset($otherDetails) && !empty($orderDetails))
        @foreach($otherDetails as $otherDetail)
            <tr>
                <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $otherDetail['name'] }}</td>
                <td style="color: {{ $otherDetail['budget_qty'] < 0 ? 'red' : 'black' }}">
                    {{ round($otherDetail['budget_qty']) }}
                </td>
                <td style="text-align: right;color: {{ $otherDetail['budget_value'] < 0 ? 'red' : 'black' }}">
                    {{ number_format($otherDetail['budget_value'], 2) }}
                </td>
                <td style="background-color: #f3f3f3; color: {{ $otherDetail['booking_qty'] < 0 ? 'red' : 'black' }}">
                    {{ round($otherDetail['booking_qty']) }}
                </td>
                <td style="background-color: #f3f3f3;text-align: right;
                color: {{ $otherDetail['booking_value'] < 0 ? 'red' : 'black' }}">
                    {{ number_format($otherDetail['booking_value'], 2) }}</td>
                <td style="color: {{ $otherDetail['balance_qty'] < 0 ? 'red' : 'black' }}">
                    {{ round($otherDetail['balance_qty']) }}
                </td>
                <td style="text-align: right; color: {{ $otherDetail['balance_value'] < 0 ? 'red' : 'black' }}">
                    {{ number_format($otherDetail['balance_value'], 2) }}
                </td>
            </tr>
            @if($loop->last)
                <tr>
                    <td colspan="8"></td>
                </tr>
                <tr>
                    <td style="background-color: gainsboro" colspan="2"><b>Total</b></td>
                    <td style="background-color: gainsboro; color: {{ collect($otherDetails)->sum('budget_qty') < 0 ? 'red' : 'black' }}"></td>
                    <td style="background-color: gainsboro; text-align: right; color: {{ collect($otherDetails)->sum('budget_value') < 0 ? 'red' : 'black' }}">
                        <b>{{ number_format(collect($otherDetails)->sum('budget_value'), 2) }}</b></td>
                    <td style="background-color: gainsboro;color: {{ collect($otherDetails)->sum('booking_qty') < 0 ? 'red' : 'black' }}"></td>
                    <td style="background-color: gainsboro; text-align: right;color: {{ collect($otherDetails)->sum('booking_value') < 0 ? 'red' : 'black' }}">
                        <b>{{ number_format(collect($otherDetails)->sum('booking_value'), 2) }}</b>
                    </td>
                    <td style="background-color: gainsboro;color: {{ collect($otherDetails)->sum('balance_qty') < 0 ? 'red' : 'black' }}"></td>
                    <td style="background-color: gainsboro; text-align: right;color: {{ collect($otherDetails)->sum('balance_value') < 0 ? 'red' : 'black' }}">
                        <b>{{ number_format(collect($otherDetails)->sum('balance_value'), 2) }}</b>
                    </td>
                </tr>
            @endif
        @endforeach
    @else
        <tr>
            <td colspan="8">No data found</td>
        </tr>
    @endif
    </tbody>
</table>
<br>
<div class="col-md-8 col-md-offset-2">
    <table class="reportTable">
        <thead>
        <tr>
            <td style="background-color: aliceblue" colspan="2"><b>SUMMARY</b></td>
        </tr>
        <tr>
            <td style="background-color: aliceblue"><b>Type</b></td>
            <td style="background-color: aliceblue; text-align: right"><b>Value</b></td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="text-left"><b>TOTAL FOB VALUE</b></td>
            <td style="text-align: right;"><b>{{ number_format(($total_fob_value ?? 0), 2) }}</b></td>
        </tr>
        <tr>
            <td class="text-left"><b>Fabric(Purchase) Cost</b></td>
            <td style="text-align: right;">
                <b>{{ number_format(($fabricPurchaseDetails['total_budget_value'] ?? 0), 2) }}</b>
            </td>
        </tr>
        <tr>
            <td class="text-left"><b>Yarn Cost</b></td>
            <td style="text-align: right;">
                <b>{{ number_format(($fabricProductionDetails['total_budget_value'] ?? 0), 2) }}</b>
            </td>
        </tr>
        <tr>
            <td class="text-left"><b>Trims Cost</b></td>
            <td style="text-align: right;"><b>{{ number_format(($trimsDetails['total_budget_value'] ?? 0), 2) }}</b>
            </td>
        </tr>
        <tr>
            <td class="text-left"><b>Embellishment Cost</b></td>
            <td style="text-align: right;">
                <b>{{ number_format(collect($otherDetails ?? [])->where('type', 'embellishment')->sum('budget_value'), 2) }}</b>
            </td>
        </tr>
        <tr>
            <td class="text-left"><b>Wash Cost</b></td>
            <td style="text-align: right;">
                <b>{{ number_format(collect($otherDetails ?? [])->where('type', 'wash')->sum('budget_value'), 2) }}</b>
            </td>
        </tr>
        <tr>
            <td class="text-left"><b>Commercial Cost</b></td>
            <td style="text-align: right;">
                <b>{{ number_format(($budgetCommercialCost ?? 0), 2) }}</b></td>
        </tr>

        <tr>
            <td class="text-left"><b>Other Cost</b></td>
            <td style="text-align: right;"><b>{{ number_format(($budgetOthersCosting ?? 0), 2) }}</b></td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            @php
                $totalBudgetedValue = ($fabricPurchaseDetails['total_budget_value']
                + $fabricProductionDetails['total_budget_value']
                + $trimsDetails['total_budget_value']
                + collect($otherDetails)->sum('budget_value')
                + $budgetCommercialCost
                + $budgetOthersCosting);
                $difference = $total_fob_value - $totalBudgetedValue;
            @endphp
            <td class="text-left"><b>Total Cost</b></td>
            <td style="text-align: right"><b>{{ number_format($totalBudgetedValue, 2) }}</b></td>
        </tr>
        <tr>
            <td class="text-left" style="background-color: gainsboro;"><b>PROFIT/LOSS</b></td>
            <td style="background-color: gainsboro;text-align: right; color: {{$difference > 0 ? 'blue' : 'red'}}">
                <b>{{ number_format($difference, 2) }}</b></td>
        </tr>
        </tbody>
    </table>
</div>
