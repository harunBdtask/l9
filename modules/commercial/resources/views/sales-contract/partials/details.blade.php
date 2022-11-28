<div class="row">
    <div class="col-md-12">

    </div>
</div>

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="selectOrder">Style</label>
                    {{ Form::select('selectedStyle', [], null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'selectedStyle']) }}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="selectOrder">Purchase Order</label>
                    {{ Form::select('selectedPurchaseOrder', [], null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'selectedPurchaseOrder']) }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" >
    <div class="col-md-10 col-md-offset-1" id="detail-select">
    </div>
</div>

<div class="row" >

    {!! Form::hidden('contract_id', $contract->id) !!}

    {!! Form::open(['url' => 'commercial/sales-contracts-details/' . $contract->id , 'id' => 'detail-form', 'method' => 'post']) !!}
    <div class="col-md-10 col-md-offset-1" id="detail-form-view">

    </div>
    {!! Form::close() !!}
</div>


<div class="row" >
    <div class="col-md-10 col-md-offset-1" id="detail-list">
        <table class="reportTable">
            <thead>
            <tr>
                <th>SL</th>
                <th>PO NO</th>
                <th>PO Quantity</th>
                <th>PO Value</th>
                <th>Attach Qty</th>
                <th>Rate</th>
                <th>Attach Value</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @php $totalPoValue = 0;@endphp
            @if(count($details))
                @foreach($details as $detail)
                    @php
                        $poQty = $detail->po->po_quantity;
                        $poValue = $detail->po->po_quantity * $detail->po->avg_rate_pc_set;
                        $totalPoValue += $poValue;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $detail->po->po_no }}</td>
                        <td>{{ $detail->po->po_quantity }}</td>
                        <td>{{ $detail->po->po_quantity * $detail->po->avg_rate_pc_set }}</td>
                        <td>{{ $detail->attach_qty }}</td>
                        <td>{{ $detail->rate }}</td>
                        <td>{{ $detail->attach_value }}</td>
                        <td>
                            <button style="background-color: #0d47a1; color: #fff;" class="edit-detail" data-po-id="{{ $detail->po->id }}">Edit</button>
                            <button style="background-color: #c1360b; color: #fff;" class="delete-detail" data-id="{{ $detail->id }}">Delete</button>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2">Total</td>
                    <td><b>{{ collect($details)->pluck('po')->sum('po_quantity') }}</b></td>
                    <td><b>{{ $totalPoValue }}</b></td>
                    <td><b>{{ collect($details)->sum('attach_qty') }}</b></td>
                    <td></th>
                    <td><b>{{ collect($details)->sum('attach_value') }}</b></td>
                    <ttdh></th>
                </tr>
            @else
                <tr>
                    <td colspan="8">No Data Found</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>


