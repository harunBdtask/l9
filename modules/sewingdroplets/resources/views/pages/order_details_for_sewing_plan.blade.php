<div class="modal-header">
  <div class="col-sm-9">
    <h5 class="modal-title">Plan Details</h5>
  </div>
  <div class="col-sm-3">
    <button type="button" class="label-danger close pull-right" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
</div>
<div class="modal-body">
  <div class="row">
    <div class="col-sm-12 table-responsive">
      <table class="reportTable">
        <thead>
        <tr>
          <th><b>Buyer :</b></th>
          <td>{{ $sewing_plan->buyer->name }}</td>
          <th><b>Style/Order :</b></th>
          <td>{{ $sewing_plan->order->style_name ?? '' }}</td>
        </tr>
        <tr>
          <th><b>Ref./ Booking No:</b></th>
          <td>{{ $sewing_plan->order->reference_no ?? '' }}</td>
          <th><b>Garments Item</b></th>
          <td>{{ $sewing_plan->garmentsItem->name ?? '' }}</td>
        </tr>
        <tr>
          <th><b>Order Qty:</b></th>
          <td>{{ $sewing_plan->order->purchaseOrders()->sum("po_quantity") ?? '' }}</td>
          <th><b>Plan Allocated Qty :</b></th>
          <td>{{ $sewing_plan->allocated_qty ?? '' }}</td>
        </tr>
        </thead>
      </table>
    </div>
    <div class="col-sm-12 table-responsive">
      <table class="reportTable">
        <thead>
        <tr>
          <td><b>PO NO</b></td>
          <td><b>PO Qty</b></td>
          <td><b>SMV</b></td>
          <td><b>Plan Allocated Qty</b></td>
          <td><b>Colors</b></td>
          <td><b>Shipment Date</b></td>
        </tr>
        </thead>
        <tbody>
        @if($sewing_plan->sewingPlanDetails->count())
          @foreach($sewing_plan->sewingPlanDetails as $sewing_plan_detail)
            @php
              $colors_array = \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail::getColors($sewing_plan_detail->purchase_order_id, true);
              $colors = '';
              if (is_array($colors_array) && count($colors_array) > 0) {
                  $colors = implode(', ', $colors_array);
              }
            @endphp
            <tr>
              <td>{{ $sewing_plan_detail->purchaseOrder->po_no }}</td>
              <td>{{ $sewing_plan_detail->purchaseOrder->po_quantity }}</td>
              <td>{{ $sewing_plan_detail->sewingPlan->smv }}</td>
              <td>{{ $sewing_plan_detail->allocated_qty }}</td>
              <td>{{ $colors }}</td>
              <td>{{ $sewing_plan_detail->purchaseOrder->ex_factory_date }}</td>
            </tr>
          @endforeach
        @endif
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-sm white m-b" data-dismiss="modal">Close</button>
</div>
