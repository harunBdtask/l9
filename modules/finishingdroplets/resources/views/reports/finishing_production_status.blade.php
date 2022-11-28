@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('finishingdroplets::layout')
@section('title', 'Finishing Production Status Report')

@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Finishing Production Status Report || {{ date("jS F, Y") }}
              <span class="pull-right">
                                <a href="{{$pdf_download_link ?? '#'}}">
                                    <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                                </a>
                                | <a href="{{$excel_download_link ?? '#'}}">
                                    <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                                </a>
                            </span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body" id="po-shipment-report">
            <div class="form-group">
              <div class="row m-b">
                <form action="{{ url('/finishing-production-status') }}">
                  <div class="col-sm-2">
                    <label>Buyer</label>
                    {!! Form::select('buyer_id', $buyers, $buyer_id ??  null, ['class' => 'form-control form-control-sm']) !!}
                  </div>
                  <div class="col-sm-2">
                    <label>Style/Order</label>
                    {!! Form::select('order_id', $orders ?? [], $order_id ?? null, ['class' => 'form-control form-control-sm']) !!}
                  </div>
                  <div class="col-sm-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn form-control form-control-sm btn btn-sm btn-info">Search</button>
                  </div>
                </form>
              </div>
            </div>
            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                <thead>
                <tr>
                  <th>Color</th>
                  <th>Order Qty</th>
                  <th>Cutting Qty</th>
                  <th>Cutting(%)</th>
                  <th>Sewing Qty</th>
                  <th>Sewing Balance</th>
                  <th>Wash Sent</th>
                  <th>Wash Received</th>
                  <th>Wash Balance</th>
                  <th>Poly Qty</th>
                  <th>Poly Balance</th>
                  <th>Shipment Date</th>
                  <th>Remarks</th>
                </tr>
                </thead>
                <tbody class="finishing_status_report_table">
                @if(isset($finishing_production_report) && $finishing_production_report)
                  @php
                    $g_total_color_wise_order_qty = 0;
                    $g_total_cutting_qty = 0;
                    $g_total_sewing_qty = 0;
                    $g_total_sewing_balance = 0;
                    $g_total_wash_sent = 0;
                    $g_total_wash_received = 0;
                    $g_total_wash_balance = 0;
                    $g_total_poly_qty = 0;
                    $g_total_poly_balance = 0;
                  @endphp
                  @foreach($finishing_production_report->sortByDesc('purchaseOrder.ex_factory_date')->groupBy('color_id') as $groupByColor)
                    @php
                      $total_color_wise_order_qty = 0;
                      $total_cutting_qty = 0;
                      $total_sewing_qty = 0;
                      $total_sewing_balance = 0;
                      $total_wash_sent = 0;
                      $total_wash_received = 0;
                      $total_wash_balance = 0;
                      $total_poly_qty = 0;
                      $total_poly_balance = 0;
                    @endphp
                    @foreach($groupByColor as $report)
                      @php
                        $color_wise_order_qty = 0;
                        if(isset($report->purchaseOrder->purchaseOrderDetails)){
                            foreach($report->purchaseOrder->purchaseOrderDetails as $detail){
                                if($detail->color_id == $report->color_id){
                                    $color_wise_order_qty += $detail->quantity;
                                }
                            }
                        }

                        // Order Qty
                        $total_color_wise_order_qty += $color_wise_order_qty;
                        // Cutting Qty
                        $cutting_qty = $report->total_cutting - $report->total_cutting_rejection ?? 0;
                        $total_cutting_qty += $cutting_qty;
                        // Cutting(%)
                        $cutting_percent = isset($total_color_wise_order_qty) && $total_color_wise_order_qty != 0 ? number_format(($total_cutting_qty * 100 / $total_color_wise_order_qty),2) : 0;
                        // Sewing Qty
                        $sewing_qty = $report->total_sewing_output ?? 0;
                        $total_sewing_qty += $sewing_qty;
                        // Sewing Balance
                        $sewing_balance = $report->total_cutting - $report->total_cutting_rejection - $report->total_sewing_output ?? 0;
                        $total_sewing_balance += $sewing_balance;
                        // Wash Sent
                        $washing_sent = $report->total_washing_sent ?? 0;
                        $total_wash_sent += $washing_sent;
                        // Wash Received
                        $washing_received = $report->total_washing_received ?? 0;
                        $total_wash_received += $washing_received;
                        // Wash Balance
                        $washing_balance = $report->total_washing_sent - $report->total_washing_received ?? 0;
                        $total_wash_balance += $washing_balance;
                        // Poly Qty
                        $poly_qty = $report->total_poly ?? 0;
                        $total_poly_qty += $poly_qty;
                        // Poly Balance
                        $poly_balance = $report->total_cutting - $report->total_cutting_rejection  - $report->total_poly ?? 0;
                        $total_poly_balance += $poly_balance;

                        // Remarks
                        $remarks_exists = \SkylarkSoft\GoRMG\Finishingdroplets\Models\PolyCartoon::getRemarks($report->order_id , $report->color_id);
                        $remarks = $remarks_exists;
                        if($remarks_exists == null) {
                            $remarks = '';
                        }
                      @endphp
                      @if($loop->last)
                        <tr>
                          @php
                            $g_total_color_wise_order_qty += $total_color_wise_order_qty;
                            $g_total_cutting_qty += $total_cutting_qty;
                            $g_total_sewing_qty += $total_sewing_qty;
                            $g_total_sewing_balance += $total_sewing_balance;
                            $g_total_wash_sent += $total_wash_sent;
                            $g_total_wash_received += $total_wash_received;
                            $g_total_wash_balance += $total_wash_balance;
                            $g_total_poly_qty += $total_poly_qty;
                            $g_total_poly_balance += $total_poly_balance;
                          @endphp
                          <td>{{$report->colors->name}}</td>
                          <td>{{$total_color_wise_order_qty}}</td>
                          <td>{{$total_cutting_qty}}</td>
                          <td>{{$cutting_percent}} %</td>
                          <td>{{$total_sewing_qty}}</td>
                          <td>{{$total_sewing_balance}}</td>
                          <td>{{$total_wash_sent}}</td>
                          <td>{{$total_wash_received}}</td>
                          <td>{{$total_wash_balance}}</td>
                          <td>{{$total_poly_qty}}</td>
                          <td>{{$total_poly_balance}}</td>
                          <td>{{date('d M,Y',strtotime($report->purchaseOrder->ex_factory_date)) ?? '-'}}</td>
                          <td>{{ $remarks }}</td>
                        </tr>
                      @endif
                    @endforeach
                  @endforeach
                @elseif(isset($finishing_production_report) && empty($finishing_production_report))
                  <tr>
                    <td colspan="13" align="center">No Data</td>
                  </tr>
                @endif
                </tbody>
                <tfoot>
                @if(isset($finishing_production_report))
                  <tr>
                    <th>Total</th>
                    <th>{{$g_total_color_wise_order_qty}}</th>
                    <th>{{$g_total_cutting_qty}}</th>
                    <th></th>
                    <th>{{$g_total_sewing_qty}}</th>
                    <th>{{$g_total_sewing_balance}}</th>
                    <th>{{$g_total_wash_sent}}</th>
                    <th>{{$g_total_wash_received}}</th>
                    <th>{{$g_total_wash_balance}}</th>
                    <th>{{$g_total_poly_qty}}</th>
                    <th>{{$g_total_poly_balance}}</th>
                    <th colspan="2"></th>
                  </tr>
                @endif
                </tfoot>
              </table>
            </div>
            <div class="loader"></div>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
<script src="{{ asset('protracker/custom.js') }}"></script>
<script>
  $(function() {
    const buyerSelectDom = $('[name="buyer_id"]');
    const orderSelectDom = $('[name="order_id"]');

    buyerSelectDom.change(() => {
      orderSelectDom.empty().val('').change();
    });

    buyerSelectDom.select2({
      ajax: {
        url: '/utility/get-buyers-for-select2-search',
        data: function (params) {
          return {
            search: params.term,
          }
        },
        processResults: function (data, params) {
          return {
            results: data.results,
            pagination: {
              more: false
            }
          }
        },
        cache: true,
        delay: 250
      },
      placeholder: 'Select Buyer',
      allowClear: true
    });

    orderSelectDom.select2({
      ajax: {
        url: function (params) {
          return `/utility/get-styles-for-select2-search`
        },
        data: function (params) {
          const buyerId = buyerSelectDom.val();
          return {
            search: params.term,
            buyer_id: buyerId,
          }
        },
        processResults: function (data, params) {
          return {
            results: data.results,
            pagination: {
                more: false
            }
          }
        },
        cache: true,
        delay: 250
      },
      placeholder: 'Select a Style/Order',
      allowClear: true
    });
  });
  
</script>
@endsection
