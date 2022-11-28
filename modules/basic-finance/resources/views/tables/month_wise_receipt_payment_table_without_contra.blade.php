@php
    $reportDatas =  collect($reportData)->toArray();
    $dataList = [];
    foreach($reportDatas as $report){
    foreach($report as $monthReport){
        array_push($dataList,$monthReport);
    }
    }
    $reportData = $dataList;
    $balanceHead = collect($reportData)->pluck('balance')->flatten(1)->pluck('name')->unique()->toArray();
    $recievedHead = collect($reportData)->pluck('debitVoucherData')->flatten(2)->pluck('name')->unique()->toArray();
    $paymentHead = collect($reportData)->pluck('creditVoucherData')->flatten(2)->pluck('name')->unique()->toArray();
    $recievedHead = collect($reportData)->pluck('debitVoucherData')->flatten(2)->pluck('name')->unique()->toArray();
    $paymentHead = collect($reportData)->pluck('creditVoucherData')->flatten(2)->pluck('name')->unique()->toArray();
    $openingGroupBalanceData =  $openingSetBalanceData = $closingGroupBalanceData = $closingSetBalanceData =  $difference = $totalOpeningBalance = $totalClosingBalance = [];

    for($i=0; $i<count($openingBalanceData); $i++)
    {
        if($i==0)
        {
            array_push($openingGroupBalanceData, $openingBalanceData[$i]);
            array_push($closingGroupBalanceData, $closingBalanceData[$i]);
        }
        else
        {
            for($j=$i; $j>=0; $j--){
                array_push($openingGroupBalanceData, $openingBalanceData[$j]);
                array_push($closingGroupBalanceData, $closingBalanceData[$j]);
            }
        }
        array_push($openingSetBalanceData,$openingGroupBalanceData);
        $openingGroupBalanceData = [];
        array_push($closingSetBalanceData,$closingGroupBalanceData);
        $closingGroupBalanceData = [];
    }
    foreach($reportData as $report)
    {
        array_push($difference,collect($report['debitVoucherData'])->flatten(1)->sum('amount') - collect($report['creditVoucherData'])->flatten(1)->sum('amount'));
    }
    foreach($openingSetBalanceData as $openingGrpBalanceData)
    {
        array_push($totalOpeningBalance, collect($openingGrpBalanceData[0])->sum('opening_balance'));
    }
    foreach($closingSetBalanceData as $closingGrpBalanceData)
    {
        array_push($totalClosingBalance, collect($closingGrpBalanceData[0])->sum('closing_balance'));
    }
@endphp


<div class="row">
    <div class="col-md-12">
        <table class="reportTable">
            <thead>
            <tr style="background-color: springgreen">
                <td style="text-align: left; background-color: lightblue;"><b>Description</b></td>
                @foreach($reportData as $report)
                    <td style="background-color: lightblue;">
                        <b>{{(\Carbon\Carbon::parse($report['date'])->format('M-Y'))}}</b></td>
                @endforeach
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="text-align: left;background-color: lightgray;"><b>Opening Balance</b></td>

                @foreach($openingSetBalanceData as $openingGrpBalanceData)
                    <td style="text-align: right; background-color: lightgray;">
                        <b>{{number_format(collect($openingGrpBalanceData[0])->sum('opening_balance'),2)}}</b></td>
                @endforeach
            </tr>

            @for($i=0; $i<count($accountName); $i++)
                <tr>
                    <td style="text-align: left">{{$accountName[$i] }}</td>
                    @foreach($openingSetBalanceData as $openingGrpBalanceData)
                        <td style="text-align: right;">{{number_format($openingGrpBalanceData[0][$i]['opening_balance'],2)}}</td>
                    @endforeach
                </tr>
            @endfor


            <tr>
                <td style="text-align: left;background-color: lightgray;"><b>Total Received</b></td>
                @foreach($reportData as $report)
                    <td style="text-align: right;background-color: lightgray;">
                        <b>{{ number_format(collect($report['debitVoucherData'])->flatten(1)->sum('amount'), 2) }}</b>
                    </td>
                @endforeach
            </tr>

            @foreach($recievedHead as $head)
                <tr>
                    <td style="text-align: left">{{$head }}</td>
                    @foreach($reportData as $report)
                        <td style="text-align: right"> {{ number_format(collect($report['debitVoucherData'])->flatten(1)->where('name',$head)->sum('amount'), 2) }}</td>
                    @endforeach
                </tr>
            @endforeach

            <tr>
                <td style="text-align: left;background-color: lightgray;"><b>Total Payments</b></td>
                @foreach($reportData as $report)
                    <td style="text-align: right;background-color: lightgray;">
                        <b>{{ number_format(collect($report['creditVoucherData'])->flatten(1)->sum('amount'), 2) }}</b>
                    </td>
                @endforeach

            </tr>

            @foreach($paymentHead as $head)
                <tr>
                    <td style="text-align: left">{{ $head }}</td>
                    @foreach($reportData as $report)
                        <td style="text-align: right"> {{ number_format(collect($report['creditVoucherData'])->flatten(1)->where('name',$head)->sum('amount'), 2) }}</td>
                    @endforeach
                </tr>
            @endforeach


            <tr>
                <td style="text-align: left;background-color: lightgray;"><b>Closing Balance</b></td>
                @foreach($closingSetBalanceData as $closingGrpBalanceData)
                    <td style="text-align: right; background-color: lightgray;">
                        <b>{{number_format(collect($closingGrpBalanceData[0])->sum('closing_balance'),2)}}</b></td>
                @endforeach
            </tr>
            @for($i=0; $i<count($accountName); $i++)
                <tr>
                    <td style="text-align: left">{{ $accountName[$i] }}</td>
                    @foreach($closingSetBalanceData as $closingGrpBalanceData)
                        <td style="text-align: right;">{{number_format($closingGrpBalanceData[0][$i]['closing_balance'],2)}}</td>
                    @endforeach
                </tr>
            @endfor

            <tr>
                <td style="text-align: left; color: red;background-color: lightgray;">Difference</td>
                @for($i=0;$i<count($difference);$i++)
                    <td style="text-align: right; color: red; background-color: lightgray; ">
                        <b>{{number_format(($totalOpeningBalance[$i] + $difference[$i] - $totalClosingBalance[$i]),2)}}</b>
                    </td>
                @endfor
            </tr>

            </tbody>
        </table>
    </div>
</div>
