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
                <th>Embroidery Send</th>
                <th>Embroidery Receive</th>
                <th>Embroidery Balance</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            @if($reports && count($reports))
                @php
                    $key=0;
                    $total_embr_send = 0;
                    $total_embr_receive = 0;
                    $balance = 0;
                @endphp
                @foreach($reports as $colorWiseReport)
                    @php
                        $colorName="";
                    @endphp
                    @foreach($colorWiseReport->sortBy('production_date') as $report)
                        @php
                            $total_embr_send += $report['embroidery_sent_qty'];
                            $total_embr_receive += $report['embroidery_receive_qty'];
                            $balance += (Arr::get($report,'embroidery_sent_qty',0)-Arr::get($report,'embroidery_receive_qty',0));
                        @endphp
                        <tr>
                            @if ($key==0)
                                <td rowspan="{{ $reports->flatten()->count() }}"> {{ $order }}</td>
                            @endif
                            @if(empty($colorName) || $colorName!=Arr::get($report,'color.name',null))
                                @php
                                    $colorName=Arr::get($report,'color.name',null);
                                @endphp
                                <td rowspan="{{count($colorWiseReport)}}">{{ $colorName }}</td>
                            @endif
                            <td>{{ Arr::get($report,'production_date',null) }}</td>
                            <td>{{ Arr::get($report,'challan_no',0) }}</td>
                            <td>{{ Arr::get($report,'embroidery_sent_qty',0) }}</td>
                            <td>{{ Arr::get($report,'embroidery_receive_qty',0) }}</td>
                            <td>{{ $balance }}</td>
                            <td>{{ Arr::get($report,'remarks',null) }}</td>
                        </tr>
                        @php($key++)
                    @endforeach
                @endforeach
                <tr style="background-color: #fcffc6">
                    <th colspan="4" class="text-right"><b>Total</b></th>
                    <th>{{ $total_embr_send }}</th>
                    <th>{{ $total_embr_receive }}</th>
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
