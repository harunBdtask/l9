<div class="row m-t">
    <div class="col-sm-12">
        <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;"
               id="fixTable">
            <thead>
            <tr style="background-color: #d7f6d3">
                <th>Style</th>
                <th>Color</th>
                <th>Date</th>
                <th>Challan No</th>
                <th>Print Send</th>
                <th>Print Receive</th>
                <th>Print Balance</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            @if($reports && count($reports))
                @php
                    $key=0;
                    $total_print_send = 0;
                    $total_print_receive = 0;
                    $balance=0;
                @endphp
                @foreach($reports as $colorWiseReport)
                    @php
                        $colorName="";
                    @endphp
                    @foreach($colorWiseReport->sortBy('production_date') as $report)
                        @php
                            $total_print_send += $report['print_sent_qty'];
                            $total_print_receive += $report['print_receive_qty'];
                        @endphp
                        <tr>
                            @if ($key==0)
                                <td rowspan="{{ $reports->flatten()->count() }}"> {{ $order }}</td>
                            @endif
                            @if(empty($colorName) || $colorName!=Arr::get($report,'color.name',null))
                                @php
                                    $colorName = Arr::get($report,'color.name',null);
                                    $balance += (Arr::get($report,'print_sent_qty',0)-Arr::get($report,'print_receive_qty',0));
                                @endphp
                                <td rowspan="{{count($colorWiseReport)}}">{{ $colorName }}</td>
                            @endif
                            <td>{{ Arr::get($report,'production_date',null) }}</td>
                            <td>{{ Arr::get($report,'challan_no',0) }}</td>
                            <td>{{ Arr::get($report,'print_sent_qty',0) }}</td>
                            <td>{{ Arr::get($report,'print_receive_qty',0) }}</td>
                            <td>{{ $balance }}</td>
                            <td>{{ Arr::get($report,'remarks',null) }}</td>
                        </tr>
                        @php($key++)
                    @endforeach
                @endforeach
                <tr style="background-color: #fcffc6">
                    <th colspan="4" class="text-right"><b>Total</b></th>
                    <th>{{ $total_print_send }}</th>
                    <th>{{ $total_print_receive }}</th>
                    <th>{{ $balance }}</th>
                    <th></th>
                </tr>
            @else
                <tr>
                    <th colspan="14">No Data</th>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
