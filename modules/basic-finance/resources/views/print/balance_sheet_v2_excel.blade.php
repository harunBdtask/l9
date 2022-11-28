<div>
    <table>
        <tr>
            <td colspan="3" class="text-left" style="background-color: lightblue"><b>{{factoryName()}}</b></td>
        </tr>
        <tr>
            <td colspan="3" class="text-left" style="background-color: lightblue"><b>{{factoryAddress()}}</b></td>
        </tr>
    </table>
</div>
<div>
    <table>
        <tr>
            <td colspan="3" style="background-color: lightblue"><h3>Balance Sheet Report</h3></td>
        </tr>
    </table>
</div>

<div>
    <div class="row">
        <div class="col-md-12">
            {!! $header !!}
            <table class="reportTable">
                <thead>
                <tr>
                    <td><b>Head Of Account</b> </td>
                    <td><b>Notes</b> </td>
                    <td><b>Balance</b> </td>
                </tr>
                </thead>
                <tbody>
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
                                <td class="text-left" style="padding-left: {{$val}};">{{$singleBalanceSheetData['name'].'('.$singleBalanceSheetData['code'].')'}}</td>
                                <td></td>
                                <td class="text-right">{{$singleBalanceSheetData['balance'] >= 0 ? number_format($singleBalanceSheetData['balance'],2) : '('.number_format(abs($singleBalanceSheetData['balance']),2).')'}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" class="text-center"><b>Total Assets:</b></td>
                            <td class="text-right">{{number_format($totalAssetBalance,2)}}</td>
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
                                <td class="text-left" style="padding-left: {{$val}};">{{$singleBalanceSheetData['name'].'('.$singleBalanceSheetData['code'].')'}}</td>
                                <td></td>
                                <td class="text-right">{{$singleBalanceSheetData['balance'] >= 0 ? number_format($singleBalanceSheetData['balance'],2) : '('.number_format(abs($singleBalanceSheetData['balance']),2).')'}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" class="text-center"><b>Total Equity:</b></td>
                            <td class="text-right">{{number_format($totalEquityBalance,2)}}</td>
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
                                <td class="text-left" style="padding-left: {{$val}};">{{$singleBalanceSheetData['name'].'('.$singleBalanceSheetData['code'].')'}}</td>
                                <td></td>
                                <td class="text-right">{{$singleBalanceSheetData['balance'] >= 0 ? number_format($singleBalanceSheetData['balance'],2) : '('.number_format(abs($singleBalanceSheetData['balance']),2).')'}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" class="text-center"><b>Total Liabilities:</b></td>
                            <td class="text-right">{{number_format($totalLiabilitiesBalance,2)}}</td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
            <table class="reportTable">
                <tr>
                    <td colspan="2" class="text-center"><b>Diffence:</b></td>
                    <td class="text-right">{{number_format($totalAssetBalance + $totalEquityBalance + $totalLiabilitiesBalance,2)}}</td>
                </tr>
            </table>
        </div>
    </div>
</div>

