<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLongTitle">Create Plan</h5>
</div>
{!! Form::open(['url' => '/sewing-plan-event-create', 'method' => 'POST', 'id' => 'sewingPlanCreateForm', 'autocomplete' => 'off']) !!}
<div class="modal-body table-responsive" style="height: 500px;">
    <div class="crate-plan-flash-message">
    </div>
    <div class="row" style="padding-bottom: 20px;">
        <div class="col-sm-4">
            <b>Buyer :</b> {{ $po_item_details->first()->buyer->name }}
            {!! Form::hidden('buyer_id', $po_item_details->first()->buyer_id, ['class' => 'form-control form-control-sm']) !!}
        </div>
        <div class="col-sm-4">
            <b>Style/Order :</b> {{ $po_item_details->first()->order->style_name }}
            {!! Form::hidden('order_id', $po_item_details->first()->order_id, ['class' => 'form-control form-control-sm']) !!}
            {!! Form::hidden('smv', $smv, ['class' => 'form-control form-control-sm']) !!}
        </div>
        <div class="col-sm-4">
            <b>Garments Item :</b> {{ $po_item_details->first()->garmentItem->name }}
            {!! Form::hidden('garments_item_id', $po_item_details->first()->garments_item_id, ['class' => 'form-control form-control-sm']) !!}
            {!! Form::hidden('smv', $po_item_details->first()->smv, ['class' => 'form-control form-control-sm']) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 table-responsive">
            <table class="reportTable">
                <thead>
                <tr>
                    <th>Sl</th>
                    <th>Purchase Order</th>
                    <th>PO Qty</th>
                    <th>Excess Qty</th>
                    <th>SMV</th>
                    <th>Shipment Date</th>
                    <th>Allocated Qty</th>
                </tr>
                </thead>
                <tbody id="plan-create-po-details-table-body">
                  @if($po_item_details->count())
                    @php
                      $total_excess_qty = 0;
                    @endphp
                    @foreach($po_item_details as $po_item_detail)
                      @php
                        $quantity_matrix = $po_item_detail->quantity_matrix;
                        $ex_cut_qty = 0;
                        if ($quantity_matrix && is_array($quantity_matrix) && count($quantity_matrix)) {
                          $cut_percent_count = collect($quantity_matrix)->where('particular', 'Ex. Cut %')->count();
                          $sum_cut_percent = collect($quantity_matrix)->where('particular', 'Ex. Cut %')->sum('value');
                          $avg_cut_percent = $cut_percent_count > 0 ? round($sum_cut_percent / $cut_percent_count) : 0;
                          $ex_cut_qty = collect($quantity_matrix)->where('particular', 'Plan Cut Qty.')->sum('value');
                        }
                        $total_excess_qty += $ex_cut_qty;
                        $ex_cut_percent = $avg_cut_percent ?? 0;
                        $ex_factory_date = $po_item_detail->purchaseOrder->ex_factory_date;
                      @endphp
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $po_item_detail->purchaseOrder->po_no }}</td>
                        <td>{{ $po_item_detail->quantity }}</td>
                        <td>{{ $ex_cut_qty }}</td>
                        <td>{{ $po_item_detail->smv }}</td>
                        <td>{{ $ex_factory_date ? date('d/m/Y', strtotime($ex_factory_date)) : null }}</td>
                        <td>
                          {!! Form::hidden('purchase_order_id[]', $po_item_detail->purchase_order_id, ['class' => 'form-control form-control-sm']) !!}
                          {!! Form::hidden('po_quantity[]', $ex_cut_qty) !!}
                          {!! Form::text('allocated_qty[]', $ex_cut_qty  - $sewing_planned_qty_array->where('garments_item_id', $po_item_detail->garments_item_id)->where('purchase_order_id', $po_item_detail->purchase_order_id)->sum('allocated_qty'), ['class' => 'form-control form-control-sm']) !!}
                          {!! Form::hidden('remaining_plan_qty[]', $ex_cut_qty  - $sewing_planned_qty_array->where('garments_item_id', $po_item_detail->garments_item_id)->where('purchase_order_id', $po_item_detail->purchase_order_id)->sum('allocated_qty')) !!}
                          <span class="text-danger allocated_qty"></span>
                          <span style="font-size: 11px; color: #d9534f;">Remaining Qty : <span class="remaining_plan_qty">0</span></span>
                        </td>
                      </tr>
                    @endforeach
                  @endif
                </tbody>
            </table>

        </div>
    </div>
    <hr>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-6">
                <label>Floor</label>
                {!! Form::select('floor_id', $floors ?? [], null, ['class' => 'form-control form-control-sm','id' => 'sewing-plan-floor-id', 'placeholder' => 'Select Floor']) !!}
                <span class="text-danger floor_id"></span>
            </div>
            <div class="col-sm-6">
                <label>Line</label>
                {!! Form::select('line_id', [], null, ['class' => 'form-control form-control-sm','id' => 'sewing-plan-line-id', 'placeholder' => 'Select Line']) !!}
                <span class="text-danger line_id"></span>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-2">
            <p><b>Manpower :</b> <span id="sewing-plan-manpower"></span></p>
        </div>
        <div class="col-sm-3">
            <p><b>Capacity(pcs) :</b> <span id="sewing-plan-capacity"></span></p>
            {!! Form::hidden('capacity_pcs', null, ['class' => 'form-control form-control-sm']) !!}
        </div>
        <div class="col-sm-3">
            <p><b>Capacity(mins) :</b> <span id="sewing-plan-capacity-min"></span></p>
        </div>
        <div class="col-sm-2">
            <p><b>Efficiency :</b> <span id="sewing-plan-efficiency"></span></p>
        </div>
        <div class="col-sm-2">
            <p><b>Working Hour :</b> <span id="sewing-plan-working-hour"></span></p>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-2">
            <label>Allocated Qty</label>
            {!! Form::hidden('sub_total_po_qty', $total_excess_qty) !!}
            {!! Form::text('master_allocated_qty', $total_excess_qty - $sewing_planned_qty_array->sum('allocated_qty'), ['class' => 'form-control form-control-sm', 'placeholder' => 'Allocated Qty', 'readonly' => true]) !!}
            {!! Form::hidden('remaining_master_plan_qty', $total_excess_qty - $sewing_planned_qty_array->sum('allocated_qty')) !!}
            <span class="text-danger master_allocated_qty"></span>
            <span style="font-size: 11px; color: #d9534f;">Remaining Qty : <span class="remaining_master_plan_qty">0</span></span>
        </div>
        <div class="col-sm-3">
            <label>Start Date <i class="fa fa-calendar"></i></label>
            {!! Form::date('start_date', null, ['class' => 'form-control form-control-sm', 'id' => 'plan-start-date']) !!}
            <span class="text-danger start_date"></span>
        </div>
        <div class="col-sm-2">
            <label>Time <i class="fa fa-clock-o"></i></label>
            <div class='input-group date datetimepicker_sewing_plan'>
                {!! Form::text('start_time', null, ['class' => 'form-control form-control-sm', 'id' => 'plan-start-time', 'readonly' => true]) !!}
                <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
            </div>
            <span class="text-danger start_time"></span>
        </div>
        <div class="col-sm-3">
            <label>End Date <i class="fa fa-calendar"></i></label>
            {!! Form::date('end_date', null, ['class' => 'form-control form-control-sm', 'id' => 'plan-end-date', 'readonly' => true]) !!}
            <span class="text-danger end_date"></span>
        </div>
        <div class="col-sm-2">
            <label>Time <i class="fa fa-clock-o"></i></label>
            <div class='input-group date datetimepicker_sewing_plan'>
                {!! Form::text('end_time', null, ['class' => 'form-control form-control-sm', 'id' => 'plan-end-time', 'readonly' => true]) !!}
                <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
            </div>
            <span class="text-danger end_time"></span>
            {!! Form::hidden('required_seconds', null, ['class' => 'form-control form-control-sm', 'id' => 'required_seconds', 'readonly' => true]) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-success pull-left">Submit</button>
    <button type="button" class="btn btn-sm white m-b" data-dismiss="modal">Close</button>
</div>
{!! Form::close() !!}

<script>
    $('.datetimepicker_sewing_plan').each(function(){
        $(this).datetimepicker({
            showClose: true,
            format: "HH:mm:ss",
            enabledHours: [8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
            ignoreReadonly : false
        });
    });
</script>
