<div class="modal-header">
    <div class="col-sm-9">
        <h5 class="modal-title">Change Line</h5>
    </div>
    <div class="col-sm-3">
        <button type="button" class="label-danger close pull-right" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
{!! Form::open(['url' => '/sewing-plan-line-change', 'method' => 'post', 'id' => 'sewingPlanChangeLineForm', 'autocomplete' => 'off']) !!}
{!! Form::hidden('id', $sewing_plan->id, ['class' => 'form-control form-control-sm']) !!}
<div class="modal-body table-responsive" style="max-height: 480px;">
    <div class="change-line-plan-flash-message">
    </div>
    <div class="row">
        <div class="col-sm-12">
            <table class="reportTable">
                <thead>
                <tr>
                    <th><b>Buyer :</b></th>
                    <td>{{ $sewing_plan->buyer->name }}</td>
                    <th><b>Style/Order :</b></th>
                    <td>{{ $sewing_plan->order->style_name ?? '' }} {{ $sewing_plan->order->reference_no ?? '' }}</td>
                </tr>
                <tr>
                    <th><b>Garments Item :</b></th>
                    <td>{{ $sewing_plan->garmentsItem->name ?? '' }}</td>
                    <th><b>Order Qty:</b></th>
                    <td>{{ $sewing_plan->order->purchaseOrders()->sum('po_quantity') ?? '' }}</td>
                </tr>
                <tr>
                    <th><b>SMV :</b></th>
                    <td>
                        {!! Form::hidden('smv', $sewing_plan->first()->smv ?? null, ['class' => 'form-control form-control-sm']) !!}
                        {{ $sewing_plan->first()->smv ?? '' }}
                    </td>
                    <th><b>Allocated Qty :</b></th>
                    <td>{{ $sewing_plan->allocated_qty ?? '' }}</td>
                </tr>
                <tr>
                    <th colspan="2"><b>Shipment Date :</b></th>
                    <td colspan="2">{{ $sewing_plan->sewingPlanDetails->first()->purchaseOrder->ex_factory_date ?? '' }}</td>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h5 align="center">PO Deatils</h5>
        </div>
        <div class="col-md-12">
            <table class="reportTable">
                <thead>
                <tr>
                    <th>PO NO</th>
                    <th>PO Qty</th>
                    <th>Allocated Qty</th>
                </tr>
                </thead>
                <tbody id="change-line-plan-qty-table-body">
                @if($sewing_plan->sewingPlanDetails->count())
                    @foreach($sewing_plan->sewingPlanDetails as $sewing_plan_detail)
                        <tr>
                            <td>{{ $sewing_plan_detail->purchaseOrder->po_no }}</td>
                            <td>{{ $sewing_plan_detail->purchaseOrder->po_quantity }}</td>
                            <td>
                                {{ $sewing_plan_detail->allocated_qty }}
                                {!! Form::hidden('sewing_plan_detail_id[]', $sewing_plan_detail->id) !!}
                                {!! Form::hidden('purchase_order_id[]', $sewing_plan_detail->purchase_order_id) !!}
                                {!! Form::hidden('allocated_qty[]', $sewing_plan_detail->allocated_qty, ['class' => 'form-control form-control-sm', 'readonly' => true]) !!}
                                <span class="text-danger allocated_qty"></span>
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
                {!! Form::select('floor_id', $floors ?? [], null, ['class' => 'form-control form-control-sm','id' => 'change-line-sewing-plan-floor-id', 'placeholder' => 'Select Floor']) !!}
                <span class="text-danger floor_id"></span>
            </div>
            <div class="col-sm-6">
                <label>Line</label>
                {!! Form::select('line_id', [], null, ['class' => 'form-control form-control-sm','id' => 'change-line-sewing-plan-line-id', 'placeholder' => 'Select Line']) !!}
                <span class="text-danger line_id"></span>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-2">
            <p><b>Manpower :</b> <span id="change-line-sewing-plan-manpower"></span></p>
        </div>
        <div class="col-sm-3">
            <p><b>Capacity(pcs) :</b> <span id="change-line-sewing-plan-capacity"></span></p>
            {!! Form::hidden('capacity_pcs', null, ['class' => 'form-control form-control-sm']) !!}
        </div>
        <div class="col-sm-3">
            <p><b>Capacity(mins) :</b> <span id="change-line-sewing-plan-capacity-min"></span></p>
        </div>
        <div class="col-sm-2">
            <p><b>Efficiency :</b> <span id="change-line-sewing-plan-efficiency"></span></p>
        </div>
        <div class="col-sm-2">
            <p><b>Working Hour :</b> <span id="change-line-sewing-plan-working-hour"></span></p>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-2">
            <label>Allocated Qty</label>
            {!! Form::text('master_allocated_qty', $sewing_plan->allocated_qty, ['class' => 'form-control form-control-sm', 'placeholder' => 'Total Allocated Qty', 'readonly' => true]) !!}
            <span class="text-danger master_allocated_qty"></span>
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
    <button type="submit" class="btn btn-success pull-left">Save Changes</button>
    <button type="button" class="btn btn-sm white m-b" data-dismiss="modal">Close</button>
</div>
{!! Form::close() !!}
