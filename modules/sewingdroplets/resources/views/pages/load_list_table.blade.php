<div class="col-sm-12">
  {!! Form::open(['url' => '/get-plan-create-form', 'method' => 'GET', 'autocomplete' => 'off', 'id' => 'sewing-plan-po-selection-form']) !!}
  {!! Form::hidden('garments_item_id', $garments_item_id) !!}
  <div class="table-responsive" style="height: 260px;">
    <table class="reportTable fixTable">
      <thead>
      <tr>
        <th>Sl</th>
        <th>Purchase Order</th>
        <th>SMV</th>
        <th>PO Qty</th>
        <th>Excess Cut(&#37;)</th>
        <th>Excess Qty</th>
        <th>Shipment Date</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody>
      @if($po_item_details->count())
        {!! Form::hidden('smv', $po_item_details->first()->smv) !!}
        @foreach($po_item_details as $po_item_detail)
          @if (!count($po_item_detail->purchaseOrder->toArray()))
            @continue
          @endif
          @php
            $quantity_matrix = $po_item_detail->quantity_matrix;
            $ex_cut_qty = 0;
            if ($quantity_matrix && is_array($quantity_matrix) && count($quantity_matrix)) {
              $cut_percent_count = collect($quantity_matrix)->where('particular', 'Ex. Cut %')->count();
              $sum_cut_percent = collect($quantity_matrix)->where('particular', 'Ex. Cut %')->sum('value');
              $avg_cut_percent = $cut_percent_count > 0 ? round($sum_cut_percent / $cut_percent_count) : 0;
              $ex_cut_qty = collect($quantity_matrix)->where('particular', 'Plan Cut Qty.')->sum('value');
            }
            $ex_cut_percent = $avg_cut_percent ?? 0;
            $ex_factory_date = count($po_item_detail->purchaseOrder->toArray()) ? (isset($po_item_detail->purchaseOrder->ex_factory_date) ? $po_item_detail->purchaseOrder->ex_factory_date : null) : null;
          @endphp
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $po_item_detail->purchaseOrder->po_no }}</td>
            <td>{{ $po_item_detail->smv }}</td>
            <td>{{ $po_item_detail->quantity }}</td>
            <td>{{ $ex_cut_percent }}</td>
            <td>{{ $ex_cut_qty }}</td>
            <td>{{ $ex_factory_date ? date('d/m/Y', strtotime($ex_factory_date)) : null }}</td>
            <td>
              <div style="padding: 5px 0px;">
                <label class="md-check">
                  {!! Form::checkbox('purchase_order_id[]', $po_item_detail->purchase_order_id, null, ['class' => 'permission_check']) !!}
                  <i class="teal-200"></i>
                </label>
                <span class="text-danger purchase_order_id"></span>
                {{--<button type="button" class="btn btn-primary btn-sm btn-success planCreateBtn"
                        data-id="{{ $po_item_detail->id }}">Create Plan
                </button>--}}
              </div>
            </td>
          </tr>
        @endforeach
      @else
        <tr>
          <td colspan="6">No Data</td>
        </tr>
      @endif
      </tbody>
    </table>
  </div>
  <button type="submit" class="btn btn-primary btn-sm btn-success pull-right" style="margin-top: 1rem">Create Plan
  </button>
  {!! Form::close() !!}
</div>
