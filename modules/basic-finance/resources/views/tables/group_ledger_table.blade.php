<div class="row">
    <div class="col-md-12">
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
                    {{Carbon\carbon::parse($start_date)->format('F d, Y')}}
                    - {{Carbon\carbon::parse($end_date)->format('F d, Y')}}

                </td>
            </tr>
        </table>

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
            @if(isset($value)):
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
                        <td class="text-right"><strong>{{number_format($value['debit'] - $value['credit'], 2)}}</strong>
                        </td>
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
            @else
                <tr>
                    <td colspan="5">
                        No Data Found.
                    </td>
                </tr>
            @endif
            </tbody>
        </table>

    </div>
</div>


{{--<td>--}}
{{--    @php--}}
{{--        $date = \Carbon\Carbon::today();--}}
{{--        if (request()->has('end_date')) {--}}
{{--            $date = \Carbon\Carbon::parse(request('end_date'));--}}
{{--        }--}}
{{--    @endphp--}}
{{--    {{$date}}--}}
{{--</td>--}}
