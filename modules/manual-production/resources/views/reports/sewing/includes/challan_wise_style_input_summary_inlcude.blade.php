<div class="row m-t">
    <div class="col-sm-12">
        <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;"
               id="fixTable">
            <thead>
            <tr style="background-color: #d7f6d3" align="center">
                <th>Buyer</th>
                <th>Style</th>
                <th>Input Challan No</th>
                <th>Input Color Name</th>
                <th>Input Qty in PCS</th>
                <th>Input Date</th>
                <th>Unit</th>
                <th>Sewing Line No</th>
            </tr>
            </thead>
            <tbody>
            @if($reports && count($reports))
                @php
                    $total_input_qty = 0;
                @endphp
                @foreach($reports as $report)
                    @php
                        $total_input_qty += $report['production_qty'];
                    @endphp
                    <tr>
                        @if($loop->first)
                            <td rowspan="{{ count($reports) }}"> {{ $buyers[$buyer_id] }}</td>
                            <td rowspan="{{ count($reports) }}"> {{ $orders[$order_id] }}</td>
                        @endif
                        <td>{{ Arr::get($report,'challan_no',null) }}</td>
                        <td>{{ Arr::get($report,'color.name',null) }}</td>
                        <td>{{ (int)Arr::get($report,'production_qty',0) }}</td>
                        <td>{{ Arr::get($report,'production_date',null) }}</td>
                        <td>{{ Arr::get($report,'floor.floor_no',null) }}</td>
                        <td>{{ Arr::get($report,'line.line_no',null) }}</td>
                    </tr>
                @endforeach
                <tr style="background-color: #fcffc6">
                    <th colspan="4">Total</th>
                    <th>{{ (int)$total_input_qty }}</th>
                    <th colspan="3"></th>
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
