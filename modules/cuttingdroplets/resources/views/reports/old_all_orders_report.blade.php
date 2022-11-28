@extends('cuttingdroplets::layout')
@section('title', 'All PO\'s Cutting Production Report')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>All PO's Cutting Production Report
              <span class="pull-right">
                <a href="{{ url('/all-orders-cutting-report-download/pdf') }}" >
                  <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                </a> 
                | 
                <a href="{{ url('/all-orders-cutting-report-download/xls') }}" >
                  <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                </a>
              </span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
              <thead>
                <tr>
                  <th rowspan="2">Buyer</th>
                  <th colspan="7">Order Details</th>
                </tr>
                <tr>
                  <th>Order</th>
                  <th>Purchase Order</th>
                  <th>PO Qty</th>
                  <th>Today's Cutting</th>
                  <th>Total Cutting</th>
                  <th>Left/Extra Qty</th>
                  <th>Extra Cutting (%)</th>
                </tr>
              </thead>
              <tbody>
              @foreach($orders->getCollection()->groupBy('buyer_id') as $ordersByBuyer)
                <tr>
                  <td rowspan="{{ $ordersByBuyer->count() + $ordersByBuyer->groupBy('order_id')->count()*3 + 1}}">{{ $ordersByBuyer->first()->buyer->name ?? '' }}</td>
                </tr>

                @foreach($ordersByBuyer->groupBy('order_id') as $ordersByStyle)
                  <tr>
                    <td rowspan="{{ $ordersByStyle->count() + 3 }}">
                      {{ $ordersByStyle->first()->order->order_style_no ?? 'Order/Style' }}
                    </td>
                  </tr>

                  @php
                    $totalOrderQty = 0;
                    $totalTodaysCutting = 0;
                    $totalCuttingForStyle = 0;
                    $totalLeftQty = 0;
                    $totalXtra = 0;
                  @endphp

                  @foreach($ordersByStyle as $order)
                    @php
                      $todaysCutting = $order->todays_cutting;
                      $totalCutting = $order->total_cutting;

                      $xtra = ($order->purchaseOrder->po_quantity > 0) ? ((( $totalCutting - $order->purchaseOrder->po_quantity) * 100) / $order->purchaseOrder->po_quantity) : 0;
                      $xtra = $xtra > 0 ? $xtra : 0;
                      $leftQty = $order->purchaseOrder->po_quantity - $order->total_cutting;

                      $totalOrderQty += $order->purchaseOrder->po_quantity;
                      $totalTodaysCutting += $order->todays_cutting;
                      $totalCuttingForStyle += $order->total_cutting;
                      $totalLeftQty += $leftQty;
                      //$totalXtra += $xtra;
                    @endphp
                    <tr>
                      <td>{{ $order->purchaseOrder->po_no }}</td>
                      <td>{{ $order->purchaseOrder->po_quantity }}</td>
                      <td>{{ $order->todays_cutting }}</td>
                      <td>{{ $order->total_cutting }}</td>
                      <td>{{ $leftQty }}</td>
                      <td>{{ number_format($xtra, 2).'%' }}</td>
                    </tr>
                  @endforeach
                  <tr>
                    <td><strong>{{ 'TOTAL' }}</strong></td>
                    <td><strong>{{ $totalOrderQty }}</strong></td>
                    <td><strong>{{ $totalTodaysCutting }}</strong></td>
                    <td><strong>{{ $totalCuttingForStyle }}</strong></td>
                    <td><strong>{{ $totalLeftQty }}</strong></td>
                    <td><strong>{{-- number_format($totalXtra, 2).'%' --}}</strong></td>
                  </tr>
                  <tr>
                    <td colspan="6">&nbsp;</td>
                  </tr>
                @endforeach
              @endforeach
              </tbody>
              <tfoot>
              @if($orders->total() > 10)
                <tr>
                  <td colspan="9" align="center">{{ $orders->appends(request()->except('page'))->links() }}</td>
                </tr>
              @endif
              </tfoot>
            </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection