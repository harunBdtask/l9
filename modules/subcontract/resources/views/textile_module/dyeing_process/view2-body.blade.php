<table class="reportTable">
    <thead>
    <tr>
        <th>Batch Date</th>
        <th>Buyer/Party Name</th>
        <th>Batch Type</th>
        <th>Order No</th>
        <th>Batch No</th>
        <th>Color</th>
        <th>Fabric Description</th>
        <th>Fabric Type</th>
        <th>Dia Type</th>
        <th>GSM</th>
        <th>Fab Dia</th>
        <th>Batch Roll</th>
        <th>Batch Weight</th>
        <th>Remarks</th>
    </tr>
    </thead>
    <tbody>
    @foreach($dyeingBatch->batchDetails as $key => $detail)
        <tr>
            <td>{{ $dyeingBatch->batch_date }}</td>
            <td>{{ $dyeingBatch->supplier->name ?? '' }}</td>
            <td>
                @if($dyeingBatch->batch_entry == 1)
                    <span>Sample</span>
                @else
                    <span>Bulk</span>
                @endif
            </td>
            <td>{{ $detail->subTextileOrder->order_no ?? '' }}</td>
            <td>{{ $dyeingBatch->batch_no }}</td>
            <td>{{ $dyeingBatch->color->name }}</td>
            <td>{{ $detail->fabric_composition_value }}</td>
            <td>{{ $detail->fabricType->construction_name }}</td>
            <td>
                @if($detail->dia_type_id == 1)
                    <span>Open</span>
                @elseif($detail->dia_type_id == 2)
                    <span>Tabular</span>
                @elseif($detail->dia_type_id == 3)
                    <span>Niddle Open</span>
                @else
                    <span>Any Dia</span>
                @endif
            </td>
            <td>{{ $detail->gsm }}</td>
            <td>{{ $detail->finish_dia }}</td>
            <td>{{ $detail->batch_roll }}</td>
            <td>{{ $detail->batch_weight }}</td>
            <td>{{ $detail->remarks }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
