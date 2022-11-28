<div class="row m-t">
    <div class="col-sm-12">
        <table class="reportTable">
            <thead>
            <tr style="background-color: #d7f6d3">
                <th colspan="13" align="center">YEARLY SUMMARY OF CUT TO SEW %</th>
            </tr>
            <tr style="background-color: #d7f6d3" align="center">
                <th>Month</th>
                <th>No of Styles</th>
                <th>Cutting Qty</th>
                <th>Cutting Rejection</th>
                <th>Print Rejection</th>
                <th>Embroidery Rejection</th>
                <th>Sewing Rejection</th>
                <th>Total Rejection</th>
                <th>Total Rejection (%)</th>
                <th>Input Qty</th>
                <th>Output Qty</th>
                <th>Line WIP</th>
                <th>Cut - Sewing (%)</th>
            </tr>
            </thead>
            <tbody>
            @if($reports && count($reports))
                @foreach($reports->groupBy('month') as $month => $report)
                    @php
                        $total_rejection= $report->map(function($item){
                                     return ['total_rejection'=> $item->cutting_rejection_qty + $item->print_rejection_qty
                                     + $item->embroidery_rejection_qty+ $item->sewing_rejection_qty];
                                 })->sum('total_rejection');
                    @endphp
                    <tr>
                        <td> {{ DateTime::createFromFormat('!m', $month)->format('F') }}</td>
                        <td>{{ $report->unique('order_id')->count() }}</td>
                        <td>{{ (int)$report->sum('cutting_qty') }}</td>
                        <td>{{ (int)$report->sum('cutting_rejection_qty') }}</td>
                        <td>{{ (int)$report->sum('print_rejection_qty') }}</td>
                        <td>{{ (int)$report->sum('embroidery_rejection_qty') }}</td>
                        <td>{{ (int)$report->sum('sewing_rejection_qty') }}</td>
                        <td>{{ (int)$total_rejection }}</td>
                        <td>{{ $report->sum('cutting_qty') > 0 ? round(($total_rejection/$report->sum('cutting_qty') * 100), 2) : 0 }}
                            %
                        </td>
                        <td>{{ (int)$report->sum('input_qty') }}</td>
                        <td>{{ (int)$report->sum('sewing_output_qty') }}</td>
                        <td>{{ (int)$report->sum('input_qty') - $report->sum('sewing_output_qty') }}</td>
                        <td>{{ $report->sum('cutting_qty') ? round(($report->sum('sewing_output_qty')/$report->sum('cutting_qty')*100), 2) : 0 }}
                            %
                        </td>
                    </tr>
                @endforeach
                @php
                    $t_total_rejection = $reports->map(function($item){
                                    return ['total_rejection'=> $item->cutting_rejection_qty + $item->print_rejection_qty
                                    + $item->embroidery_rejection_qty+ $item->sewing_rejection_qty];
                                })->sum('total_rejection');
                   $t_total_rejection_percentage = $reports->sum('cutting_qty') > 0 ?
                   number_format(($t_total_rejection/$reports->sum('cutting_qty')) * 100, 2) : 0;
                   $total_cut_sewing_percentage = $reports->sum('cutting_qty') > 0 ?
                   number_format(($reports->sum('sewing_output_qty')/$reports->sum('cutting_qty'))*100, 2) : 0;
                @endphp
                <tr style="background-color: #fcffc6">
                    <th colspan="2">Total</th>
                    <th>{{ (int)$reports->sum('cutting_qty') }}</th>
                    <th>{{ (int)$reports->sum('cutting_rejection_qty') }}</th>
                    <th>{{ (int)$reports->sum('print_rejection_qty') }}</th>
                    <th>{{ (int)$reports->sum('embroidery_rejection_qty') }}</th>
                    <th>{{ (int)$reports->sum('sewing_rejection_qty') }}</th>
                    <th>{{ (int)$reports->map(function($item){
                                 return ['total_rejection'=> $item->cutting_rejection_qty + $item->print_rejection_qty
                                 + $item->embroidery_rejection_qty+ $item->sewing_rejection_qty];
                             })->sum('total_rejection') }}
                    </th>
                    <th>{{$t_total_rejection_percentage}}%</th>
                    <th>{{ (int)$reports->sum('input_qty') }}</th>
                    <th>{{ (int)$reports->sum('sewing_output_qty') }}</th>
                    <th>{{ (int)$reports->sum('input_qty') - $report->sum('sewing_output_qty') }}</th>
                    <th>{{$total_cut_sewing_percentage}}%</th>
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
