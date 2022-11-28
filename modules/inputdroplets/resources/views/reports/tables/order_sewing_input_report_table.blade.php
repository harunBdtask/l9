<thead>
  <tr>
    <th>Order</th>
    <th>Buyer</th>
    <th>Garments Item</th>
    <th>PO</th>
    <th>Ex-Factory Date</th>
    <th>Order Qty</th>
    <th>Input Qty</th>
    <th>Ttl Input Qty</th>
    <th>Input &#37;</th>
    <th>Input Balance</th>
  </tr>
</thead>
<tbody>
  @if($reports && $reports->count())
    @php
      $gt_order_qty = 0;
      $gt_input_qty = 0;
      $gt_input_balance = 0;
    @endphp
    @foreach ($reports->groupBy('order_id') as $order_id => $reportByOrder)
      @php
        $order = $reportByOrder->first()->order->style_name ?? null;
        $buyer = $reportByOrder->first()->buyer->name ?? null;
        $garmentsItem = $reportByOrder->unique('garments_item_id')->pluck('garmentsItem.name')->implode(', ') ?? null;
        $orderRowSpan = $reportByOrder->groupBy('purchase_order_id')->count();
        $orderQty = 0;
        $reportByOrder->groupBy('purchase_order_id')->each(function($poGroup) use(&$orderQty) {
          $orderQty += $poGroup->first()->purchaseOrder->po_pc_quantity ?? 0;
        });
        $orderInputQty = $reportByOrder->sum('total_input');
        $orderInputPercent = $orderQty > 0 ? round(($orderInputQty * 100) / $orderQty) : 0;
        $orderInputBalance = $orderQty - $orderInputQty;
        $orderInputBalance = $orderInputBalance > 0 ? $orderInputBalance : 0;
        
        $gt_order_qty += $orderQty;
        $gt_input_qty += $orderInputQty;
        $gt_input_balance += $orderInputBalance;
      @endphp
      <tr>
        <td rowspan="{{ $orderRowSpan }}">{{ $order }}</td>
        <td rowspan="{{ $orderRowSpan }}">{{ $buyer }}</td>
        <td rowspan="{{ $orderRowSpan }}">{{ $garmentsItem }}</td>
      @foreach ($reportByOrder->groupBy('purchase_order_id') as $purchase_order_id => $reportByPO)
        @php
          $po = $reportByPO->first()->purchaseOrder->po_no ?? null;
          $poQty = $reportByPO->first()->purchaseOrder->po_pc_quantity ?? 0;
          $exFactoryDate = $reportByPO->first()->purchaseOrder->ex_factory_date ?? null;
          $exFactoryDate = $exFactoryDate ? date('d-m-Y', strtotime($exFactoryDate)) : null;
          $inputQty = $reportByPO->sum('total_input'); 
        @endphp
        @if(!$loop->first)
          <tr>
        @endif
        <td>{{ $po }}</td>
        <td>{{ $exFactoryDate }}</td>
        <td>{{ $poQty }}</td>
        <td>{{ $inputQty }}</td>
        @if($loop->first)
        <td rowspan="{{ $orderRowSpan }}">{{ $orderInputQty }}</td>
        <td rowspan="{{ $orderRowSpan }}">{{ $orderInputPercent }}</td>
        <td rowspan="{{ $orderRowSpan }}">{{ $orderInputBalance }}</td>
        @endif
      </tr>
      @endforeach
    @endforeach
    <tr>
      <th colspan="5">Grand Total</th>
      <th>{{ $gt_order_qty }}</th>
      <th colspan="2">{{ $gt_input_qty }}</th>
      <th>&nbsp;</th>
      <th>{{ $gt_input_balance }}</th>
    </tr>
  @else
  <tr>
    <td colspan="10">No Data</td>
  </tr>
  @endif
</tbody>
@if($reports->total() > 15 && !request()->has('type'))
<tfoot>
  <tr>
    <td colspan="10" align="center">{{ $reports->appends(request()->except('page'))->links() }}</td>
  </tr>
</tfoot>
@endif
