<table class="reportTable">
    <thead>
    <tr>
        <th><b>SL</b></th>
        <th><b>Production Date</b></th>
        <th><b>Batch No</b></th>
        <th><b>Machine Name</b></th>
        <th><b>Buyer Name</b></th>
        <th><b>Sales Order No</b></th>
        <th><b>Fabric Description</b></th>
        <th><b>Dia</b></th>
        <th><b>Gsm</b></th>
        <th><b>Fabric Color</b></th>
        <th><b>Order Qty</b></th>
        <th><b>Production Qty</b></th>
    </tr>
    </thead>
    <tbody>
        @if (isset($dyeingProduction))
            @foreach ($dyeingProduction as $production)
            @php
                $machines = collect($production->dyeingBatch->machineAllocations)
                            ->pluck('machine.name')
                            ->implode(',');
            @endphp
            @foreach ($production->dyeingProductionDetails as $key => $details)
            <tr>
                @if ($loop->first)
                @php
                    $rowSpan = $production->dyeingProductionDetails->count();
                @endphp
                <td class="text-center" rowspan="{{ $rowSpan }}">{{ $key+1 }}</td>
                <td class="text-center" rowspan="{{ $rowSpan }}">{{ $production->production_date }}</td>
                <td class="text-center" rowspan="{{ $rowSpan }}">{{ $production->dyeing_batch_no }}</td>
                <td class="text-center" rowspan="{{ $rowSpan }}">{{ $machines }}</td>
                <td class="text-center" rowspan="{{ $rowSpan }}">{{ $production->buyer->name }}</td>
                <td class="text-center" rowspan="{{ $rowSpan }}">{{ $production->dyeing_order_no }}</td>
                @endif
               
                <td class="text-center">{{ $details->fabric_composition_value }}</td>
                <td class="text-center">{{ $details->dia_type_value['name'] }}</td>
                <td class="text-center">{{ $details->gsm }}</td>
                <td class="text-center">{{ $details->color->name }}</td>
                <td class="text-center">{{ $details->batch_qty }}</td>
                <td class="text-center">{{ $details->dyeing_production_qty }}</td>
            </tr>

            @endforeach
            @endforeach
        @endif
    </tbody>
</table>