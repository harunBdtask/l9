@includeWhen($batchDetails, \SkylarkSoft\GoRMG\Subcontract\PackageConst::VIEW_PATH.'report.batch-costing.batch-body')

<table class="reportTable">
    <thead>
    <tr>
        <td>
            <strong>DATE</strong>
        </td>
        <td>
            <strong>BATCH NO</strong>
        </td>
        <td>
            <strong>BATCH QTY</strong>
        </td>
        <td>
            <strong>ITEM</strong>
        </td>
        <td>
            <strong>UNIT</strong>
        </td>
        <td>
            <strong>QTY</strong>
        </td>
        <td>
            <strong>RATE</strong>
        </td>
        <td>
            <strong>VALUE</strong>
        </td>
    </tr>
    </thead>
    <tbody>
    @if(collect($reportData)->count())
        @foreach($reportData as $batchWiseReport)
            @foreach($batchWiseReport as $report)
                <tr>
                    @if($loop->first)
                        <td rowspan="{{ $batchWiseReport->count() }}"> {{ $report['date'] ? \Carbon\Carbon::make($report['date'])->format('d-M-Y') : '' }} </td>
                        <td rowspan="{{ $batchWiseReport->count() }}"> {{ $report['batch_no'] }} </td>
                        <td rowspan="{{ $batchWiseReport->count() }}"> {{ $report['batch_qty'] }} </td>
                    @endif
                    <td> {{ $report['item'] }} </td>
                    <td> {{ $report['unit'] }} </td>
                    <td style="text-align: right"> {{ $report['qty'] }} </td>
                    <td style="text-align: right"> {{ $report['rate'] }} </td>
                    <td style="text-align: right"> {{ $report['value'] }} </td>
                </tr>
            @endforeach
        @endforeach
        <tr>
            <td colspan="8" style="padding-top: 5px"></td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: right">
                <strong>TOTAL</strong>
            </td>
            <td style="text-align: right">
                <strong>{{ $reportData->first()->sum('value') }}</strong>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: right">
                <strong>COST PER KG</strong>
            </td>
            <td style="text-align: right">
                @php
                    $batchQty = $reportData->first()->first()['batch_qty'];

                    $totalValue = collect($reportData)->first()->filter(function ($item){
                            return is_numeric($item['value']);
                        })->sum('value');

                    $costPerKg = $batchQty > 0 ? $totalValue/$batchQty : 0;
                @endphp
                <strong>{{ $costPerKg ? round($costPerKg, 4) : 0 }}</strong>
            </td>
        </tr>
    @else
        <tr>
            <td colspan="8" style="text-align: center"><strong>NO DATA FOUND</strong></td>
        </tr>
    @endif

    </tbody>
</table>
