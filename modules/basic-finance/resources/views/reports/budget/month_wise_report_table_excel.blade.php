<div>
    <div>
        <div>
            <table>
                <tr>
                    <td colspan="9" class="text-left" style="background-color: lightblue"><b>{{factoryName()}}</b></td>
                </tr>
                <tr>
                    <td colspan="9" class="text-left" style="background-color: lightblue"><b>{{factoryAddress()}}</b>
                    </td>
                </tr>
            </table>
        </div>
        <div>
            <table>
                <tr>
                    <td colspan="9" style="background-color: lightblue"><h3>Trial Balance Report</h3></td>
                </tr>
            </table>
        </div>
        <div>

            <table class="reportTable">
                <thead class="thead-light">
                <tr>
                    <th class="text-left">SL No</th>
                    <th class="text-left">Head Of Accounts</th>
                    @foreach(collect($report_data)->groupBy('month') as $monthKey=>$grpMonth)
                        <th class="text-left">{{ $monthKey }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @php
                    $i=0;
                    $totalAmount = []
                @endphp
                @forelse(collect($report_data)->groupBy('account') as $key => $report)
                    <tr>
                        <td class="text-left">{{ $i+1 }}</td>
                        <td class="text-left">{{ $key }}</td>
                        @foreach(collect($report_data)->groupBy('month') as $monthKey => $grpMonth)
                            @php
                                $amount = collect($report_data)
                                    ->where('account',$report->first()['account'])
                                    ->where('month',$monthKey)->first();

                                $totalAmount[$monthKey] = $totalAmount[$monthKey] ?? 0;
                                $totalAmount[$monthKey] += $amount['budget_amount']
                            @endphp
                            <td class="text-right">{{ number_format($amount['budget_amount'], 2) }}</td>
                        @endforeach
                    </tr>
                    @php
                        $i++
                    @endphp
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-danger">No Report Found</td>
                    </tr>
                @endforelse
                </tbody>
                <tbody>
                <tr>
                    <td colspan="2"><b>Total</b></td>
                    @foreach(collect($report_data)->groupBy('month') as $monthKey=>$grpMonth)
                        <td class="text-right"><b>{{ number_format($totalAmount[$monthKey], 2) }}</b></td>
                    @endforeach
                </tr>
                </tbody>
            </table>
        </div>


