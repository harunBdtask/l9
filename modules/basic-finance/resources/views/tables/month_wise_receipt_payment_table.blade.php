<div class="row">
    <div class="col-md-12">
        <table class="reportTable">
            <thead>
            <tr style="background-color: springgreen">
                <td style="text-align: left; background-color: lightblue;"><b>Description</b></td>
                @php
                    $reportDatas =  collect($reportData)->toArray();
                    $dataList = [];
                    foreach($reportDatas as $report){
                    foreach($report as $monthReport){
                        array_push($dataList,$monthReport);
                    }
                    }
                    $reportData = $dataList
                @endphp
                @foreach($reportData as $report)
                    <td style="background-color: lightblue;"><b>{{(\Carbon\Carbon::parse($report['date'])->format('M-Y'))}}</b></td>
                @endforeach
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="text-align: left;background-color: lightgray;"><b>Opening Balance</b></td>
                @foreach($reportData as $report)
                    <td style="text-align: right; background-color: lightgray;"><b>{{ number_format(collect($report['balance'])->sum('openingBalance'), 2) }}</b></td>
                @endforeach
            </tr>

            @php
                $balanceHead = collect($reportData)->pluck('balance')->flatten(1)->pluck('name')->unique()->toArray();
                $recievedHead = collect($reportData)->pluck('debitVoucherData')->flatten(2)->pluck('name')->unique()->toArray();
                $paymentHead = collect($reportData)->pluck('creditVoucherData')->flatten(2)->pluck('name')->unique()->toArray();
                $recievedHead = collect($reportData)->pluck('debitVoucherData')->flatten(2)->pluck('name')->unique()->toArray();
                $paymentHead = collect($reportData)->pluck('creditVoucherData')->flatten(2)->pluck('name')->unique()->toArray();

             //  foreach($recievedHead as $head){
              //     foreach($reportData as $report){
               //         dump(collect($report['debitVoucherData'])->flatten(1)->where('name',$head)->sum('amount'));
               //    }
              // }
              // dd(collect($reportData[0]['debitVoucherData'])->flatten(1)->where('name','CBC#1804008662')->sum('amount'));
            @endphp

            @foreach($balanceHead as $head)
                <tr>
                    <td style="text-align: left">{{ str_replace("&","&amp;",$head) }}</td>
                    @foreach($reportData as $report)
                        <td style="text-align: right">{{collect($report['balance'])->where('name', $head)->sum('openingBalance')}}</td>
                    @endforeach
                </tr>
            @endforeach


            <tr>
                <td style="text-align: left;background-color: lightgray;"><b>Total Received</b></td>
                @foreach($reportData as $report)
                    <td style="text-align: right;background-color: lightgray;"><b>{{ number_format(collect($report['debitVoucherData'])->flatten(1)->sum('amount'), 2) }}</b></td>
                @endforeach
            </tr>

            @foreach($recievedHead as $head)
                <tr>
                    <td style="text-align: left">{{ str_replace("&","&amp;",$head) }}</td>
                    @foreach($reportData as $report)
                        <td style="text-align: right"> {{ collect($report['debitVoucherData'])->flatten(1)->where('name',$head)->sum('amount') }}</td>
                    @endforeach
                </tr>
            @endforeach

            <tr>
                <td style="text-align: left;background-color: lightgray;"><b>Total Payments</b></td>
                @foreach($reportData as $report)
                    <td style="text-align: right;background-color: lightgray;"><b>{{ number_format(collect($report['creditVoucherData'])->flatten(1)->sum('amount'), 2) }}</b></td>
                @endforeach

            </tr>

            @foreach($paymentHead as $head)
                <tr>
                    <td style="text-align: left">{{ str_replace("&","&amp;",$head) }}</td>
                    @foreach($reportData as $report)
                        <td style="text-align: right"> {{ collect($report['creditVoucherData'])->flatten(1)->where('name',$head)->sum('amount') }}</td>
                    @endforeach
                </tr>
            @endforeach


            <tr>
                <td style="text-align: left;background-color: lightgray;"><b>Closing Balance</b></td>
                @foreach($reportData as $report)
                    <td style="text-align: right;background-color: lightgray;"><b>{{ number_format(collect($report['balance'])->sum('closingBalance'), 2) }}</b></td>
                @endforeach

            </tr>

            @foreach($balanceHead as $head)
                <tr>
                    <td style="text-align: left">{{ str_replace("&","&amp;",$head) }}</td>
                    @foreach($reportData as $report)
                        <td style="text-align: right">{{collect($report['balance'])->where('name', $head)->sum('closingBalance')}}</td>
                    @endforeach
                </tr>
            @endforeach

            <tr>
                <td style="text-align: left; color: red;background-color: lightgray;">Difference</td>
                @foreach($reportData as $report)
                    @php
                    $totalOpening = collect($report['balance'])->sum('openingBalance');
                    $totalReceived = collect($report['debitVoucherData'])->flatten(2)->sum('amount');
                    $totalPayments = collect($report['creditVoucherData'])->flatten(2)->sum('amount');
                    $totalClosing = collect($report['balance'])->sum('closingBalance');
                    $difference = ($totalOpening + $totalReceived - $totalPayments) - $totalClosing;
                    @endphp
                    <td style="text-align: right;color: red; background-color: lightgray;"><b>{{ number_format($difference, 2) }}</b></td>
                @endforeach
            </tr>

            </tbody>
        </table>
    </div>
</div>
