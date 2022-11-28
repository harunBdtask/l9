<Table class="reportTable detail-form-table" style="width: 100%">
    <thead>
    <tr>
        <th>PO No</th>
        <th>PO Qty</th>
        <th>PO Value</th>
        <th style="width: 120px;">Attach. Qty</th>
        <th style="width: 120px;">Rate</th>
        <th style="width: 120px;">Attach Value</th>
        <th>Style Ref</th>
        <th>Garments Item</th>
        <th>Unique ID</th>
        <th>Category</th>
        <th>H.S Code</th>
    </tr>
    </thead>
    <tbody id="detail-body">

    @foreach($purchaseOrders as $key => $po)

        @php
            $detail = \SkylarkSoft\GoRMG\Commercial\Models\ExportLCDetail::where('po_id', $po->id)->first();
            $attach_qty = $detail ? $detail->attach_qty : $po->po_quantity;
            $rate = $detail ? $detail->rate : $po->avg_rate_pc_set;
            $attach_value = $detail ? $detail->attach_value : $po->po_quantity * $po->avg_rate_pc_set;
        @endphp

        <tr class="detail-tr">
            <td>
                {!! Form::hidden('po_id[]', $po->id) !!}
                {!! Form::hidden('order_id[]', $po->order->id) !!}
                {{ $po->po_no }}
            </td>
            <td>{{ $po->po_quantity }}</td>
            <td>
                {!! Form::hidden('po_value[]', $po->po_quantity * $po->avg_rate_pc_set) !!}
                {{ $po->po_quantity * $po->avg_rate_pc_set }}
            </td>
            <td>
                {!! Form::text('attach_qty[]', $attach_qty, ['class' => 'form-control form-control-sm form-control form-control-sm-sm']) !!}
                <span class="text-danger attach_qty_error"></span>
            </td>
            <td>
                {!! Form::text('rate[]', $rate, ['class' => 'form-control form-control-sm form-control form-control-sm-sm']) !!}
                <span class="text-danger rate_error"></span>
            </td>
            <td>
                {!! Form::number('attach_value[]', $attach_value, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'max' => $po->po_quantity * $po->avg_rate_pc_set]) !!}
                <span class="text-danger attach_value_error"></span>
            </td>
            <td>{{ $po->order->style_name }}</td>
            <td>{{ "" }}</td>
            <td>{{ $po->order->job_no }}</td>
            <td>{{ $po->order->productCategory->category_name }}</td>
            <td></td>
        </tr>


    @endforeach

    @if(count($purchaseOrders))
        <tr>
            <td colspan="3">
                <button class="btn btn-sm btn-block btn-primary" id="detail-save">Save</button>
            </td>
            <td colspan="8"></td>
        </tr>
    @endif

    </tbody>

</Table>
