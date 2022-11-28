<div class="row">
    <div class="col-md-12">
        {!! $header !!}
        <table class="reportTable">
            <thead>
            <tr>
                <th> Head Of Account</th>
                <th> Notes</th>
                <th> Balance</th>
            </tr>
            </thead>
            <tbody>
                @php $totalEquityLiability = 0; @endphp
            @foreach($allBalanceSheetFormattedData as $type_id => $singleBalanceSheetTypeData)
                @if($type_id == 1)
                    @php $totalAssetBalance = 0; @endphp
                    <tr>
                        <td colspan="3" class="text-left"><b>ASSET</b></td>
                    </tr>
                    @foreach($singleBalanceSheetTypeData as $singleBalanceSheetData)
                        @php
                            $val = 40*((int)$singleBalanceSheetData['space_level']  ) . 'px';
                            $totalAssetBalance = $totalAssetBalance + $singleBalanceSheetData['balance'];
                        @endphp
                        <tr>
                            <td class="text-left"
                                style="padding-left: {{$val}};">{{$singleBalanceSheetData['name'].'('.$singleBalanceSheetData['code'].')'}}</td>
                            <td></td>
                            <td class="text-right">{{$singleBalanceSheetData['balance'] >= 0 ? number_format($singleBalanceSheetData['balance'],2) : '('.number_format(abs($singleBalanceSheetData['balance']),2).')'}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" class="text-center"><b>Total Assets:</b></td>
                        <td class="text-right">{{number_format(($totalAssetBalance/2),2)}}</td>
                    </tr>
                @elseif($type_id == 2)
                    @php $totalEquityBalance = 0; @endphp
                    <tr>
                        <td colspan="3" class="text-left"><b>EQUITY</b></td>
                    </tr>
                    @foreach($singleBalanceSheetTypeData as $singleBalanceSheetData)
                        @php
                            $val = 40*((int)$singleBalanceSheetData['space_level'] ) . 'px';
                            $totalEquityBalance = $totalEquityBalance + $singleBalanceSheetData['balance'];
                        @endphp
                        <tr>
                            <td class="text-left"
                                style="padding-left: {{$val}};">{{$singleBalanceSheetData['name'].'('.$singleBalanceSheetData['code'].')'}}</td>
                            <td></td>
                            @if(($singleBalanceSheetData['code'] == '2102000000000')||($singleBalanceSheetData['code'] == '2100000000000'))
                                <td class="text-right">{{$singleBalanceSheetData['balance'] >= 0 ? number_format(($singleBalanceSheetData['balance'] + $balanceOfIncomeExpense ),2) : '('.number_format(abs(($singleBalanceSheetData['balance'] + $balanceOfIncomeExpense)),2).')'}}</td>
                            @else
                                <td class="text-right">{{$singleBalanceSheetData['balance'] >= 0 ? number_format($singleBalanceSheetData['balance'],2) : '('.number_format(abs($singleBalanceSheetData['balance']),2).')'}}</td>
                            @endif
                        </tr>
                    @endforeach
                    <tr>

                        @php 
                            $totalEquityAmt = (($totalEquityBalance/2)+$balanceOfIncomeExpense);
                            $totalEquityLiability = $totalEquityLiability+$totalEquityAmt; @endphp

                        <td colspan="2" class="text-center"><b>Total Equity:</b></td>
                        <td class="text-right">{{number_format($totalEquityAmt,2)}}</td>
                    </tr>
                @elseif($type_id == 3)
                    @php $totalLiabilitiesBalance = 0; @endphp
                    <tr>
                        <td colspan="3" class="text-left"><b>LIABILITIES</b></td>
                    </tr>
                    @foreach($singleBalanceSheetTypeData as $singleBalanceSheetData)
                        @php
                            $val = 40*((int)$singleBalanceSheetData['space_level'] ) . 'px';
                            $totalLiabilitiesBalance = $totalLiabilitiesBalance + $singleBalanceSheetData['balance'];
                        @endphp
                        <tr>
                            <td class="text-left"
                                style="padding-left: {{$val}};">{{$singleBalanceSheetData['name'].'('.$singleBalanceSheetData['code'].')'}}</td>
                            <td></td>
                            <td class="text-right">{{$singleBalanceSheetData['balance'] >= 0 ? number_format($singleBalanceSheetData['balance'],2) : '('.number_format(abs($singleBalanceSheetData['balance']),2).')'}}</td>
                        </tr>
                    @endforeach
                    @php 
                    $totalLiabilityAmt = ($totalLiabilitiesBalance/2);
                    $totalEquityLiability = $totalEquityLiability+$totalLiabilityAmt;
                     @endphp
                    <tr>
                        <td colspan="2" class="text-center"><b>Total Liabilities:</b></td>
                        <td class="text-right">{{number_format($totalLiabilityAmt,2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-center"><b>Total Equity and Liabilities:</b></td>
                        <td class="text-right">{{number_format($totalEquityLiability,2)}}</td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
        <table class="reportTable">
            <tr>
                <td colspan="3" class="text-center"><b>Difference:</b></td>
                <td class="text-right">{{number_format(((($totalAssetBalance - $totalEquityBalance - $totalLiabilitiesBalance)/2) - $balanceOfIncomeExpense),2)}}</td>
            </tr>
        </table>
    </div>
</div>
