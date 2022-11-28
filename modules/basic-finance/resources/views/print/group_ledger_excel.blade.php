<div>
    <table>
        <tr>
            <td colspan="5" class="text-center" style="height: 30px;font-size: 20pt; font-weight: bold;">
                <b>{{factoryName()}}</b></td>
        </tr>
        <tr>
            <td colspan="5" class="text-center" style="height: 20px;"><h3>Trial Balance Report</h3></td>
        </tr>
    </table>
</div>
<div>
    <table style="margin-bottom: 5px;" class="borderless">
        <tr>
            <td style="width:150px;">
                <b>Account Head:</b>
            </td>
            <td>
                {{$account}}

            </td>
        </tr>
        <tr>
            <td style="width:102px;">
                <b>Date:</b>
            </td>
            <td>
                {{Carbon\carbon::parse($start_date)->format('F d, Y')}} - {{Carbon\carbon::parse($end_date)->format('F d, Y')}}

            </td>
        </tr>
    </table>
</div>
<div>

    <div class="row">
        <div class="col-md-12">
            <table class="reportTable">
                <thead class="thead-light">
                <tr>
                    <th class='text-left'>ACCOUNT CODE</th>
                    <th class='text-left'>ACCOUNT HEAD</th>
                    <th class="text-right">DEBIT</th>
                    <th class="text-right">CREDIT</th>
                    <th class="text-right">BALANCE</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class='text-center' colspan="4"><strong>Opening Balance</strong></td>
                    <td class="text-right">
                        @if((collect($values)->first())['totalOpeningBalance'] >= 0)
                            <strong>{{ number_format(abs((collect($values)->first())['totalOpeningBalance']), 2).' Dr' }}</strong>
                        @else
                            <strong>{{ number_format(abs((collect($values)->first())['totalOpeningBalance']), 2).' Cr' }}</strong>
                        @endif
                    </td>
                </tr>
                @if($values)

                    @foreach($values as $value)
                        <tr>
                            <td>{{$value['accountCode']}}</td>
                            <td>{{$value['accountHead']}}</td>
                            <td class="text-right">{{number_format($value['debit'], 2)}}</td>
                            <td class="text-right">{{number_format($value['credit'], 2)}}</td>
                            <td class="text-right"><strong>{{number_format($value['debit'] - $value['credit'], 2)}}</strong></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class='text-center' colspan="4"><strong>Closing Balance</strong></td>

                        <td class="text-right">

                            @if((collect($values)->first())['totalClosingBalance'] >= 0)
                                <strong>{{ number_format(abs((collect($values)->first())['totalClosingBalance']), 2).' Dr' }}</strong>
                            @else
                                <strong>{{ number_format(abs((collect($values)->first())['totalClosingBalance']), 2).' Cr' }}</strong>
                            @endif
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="7" class="text-center text-danger">No transaction</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
