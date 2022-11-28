@extends('printembrdroplets::layout')
@section('styles')
  <style type="text/css">
    @media print {
      .app-header ~ .app-body {
        padding: 0px !important;
      }

      .no-print {
        display: none;
      }

      .reportTable thead, .reportTable tbody, .reportTable th {
        padding: 0px;
        font-size: 11px;
        text-align: center;
      }

      hr {
        margin-top: .0rem;
        margin-bottom: 0rem;
      }

      .box-header {
        padding: .25rem !important;
      }

      .box-body {
        padding-top: 0rem !important;
      }

      .reportTable {
        margin-bottom: 0;
      }

      .single-challan-row:nth-of-type(2n) {
        margin-top: 70px !important;
      }

      .box-header-second {
        margin-top: 80px !important;
      }

      .autorized_table {
        margin-bottom: 30px !important;
      }

      .reportTable th {
        padding: 0px !important;
      }
    }

    .box-header {
      padding: .10rem !important;
    }

    .autorized_table {
      width: 100%;
      margin-top: 50px;
      margin-bottom: 130px;
    }

    .reportTable th,
    .reportTable td {
      border: 1px solid #000000;
    }
  </style>
@endsection
@section('title', 'Gate Pass / Challan')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="single-challan-row">
          <div class="box">
            <div class="box-header text-center">
              <span style="font-size: 18px; font-weight: bold;">Gate Pass / Challan</span>
              <a class="btn btn-xs pull-right no-print" style="font-size: 17px;">
                <i class="fa fa-print" aria-hidden="true"></i>
              </a>
            </div>
            <div class="box-divider m-a-0"></div>
            <div class="box-body">
              <div class="factory-area text-center" style="font-size: 1.1em;">
                @if($userFactoryInfo)
                  <strong>{{ $userFactoryInfo->factory_name }}</strong><br>
                  {{ $userFactoryInfo->factory_address ?? '' }}<br>
                @endif
              </div>
              <hr>
              <div class="col-sm-12 text-center" style="margin-bottom: 20px;">
                <div style="font-size: 1.05em"><strong>Sending To:</strong></div>
                <div class="row">
                  <div class="col-sm-12">
                    <strong>Challan No: </strong> {{ $gatePassChallan->challan_no ?? '' }} |
                    <strong>Factory Name : </strong>
                    {{ $gatePassChallan->printFactory->factory_name ?? '' }}
                    |
                    <strong>Address : </strong>
                    {{ $gatePassChallan->printFactory->factory_address ?? '' }}
                    | <strong>Operation
                      Name: </strong> {{ $gatePassChallan->operation_name ? OPERATION[$gatePassChallan->operation_name] : '' }}
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    @php
                      $new_challan_time = '';
                      if (isset($gatePassChallan)) {
                        $challan_originial_time = $gatePassChallan->updated_at;
                        if (date('H', strtotime($challan_originial_time)) < 8) {
                          $new_challan_time = '08:'.date('i:s', strtotime($challan_originial_time));
                        } elseif (date('H', strtotime($challan_originial_time)) >= 19) {
                          $new_challan_time = '18:'.date('i:s', strtotime($challan_originial_time));
                        } else {
                          $new_challan_time = date('H:i:s', strtotime($challan_originial_time));
                        }
                      }
                    @endphp
                    <strong>C/N : </strong> {{ implode(", ", $cuttingNos) }}
                    | <strong>Lot : </strong> {{ implode(", ", $lotNos) }}
                    | <strong>Bag(s): </strong> {{ $gatePassChallan->bag ?? '' }}
                    | <strong>Part(s): </strong> {{ $gatePassChallan->part->name ?? '' }}
                    | <strong>Date
                      : </strong> @if(isset($gatePassChallan)) {{ date('jS F, Y', strtotime($gatePassChallan->updated_at)) }} @endif
                    | <strong>Time : </strong>@if(isset($new_challan_time)) {{ $new_challan_time }} @endif
                  </div>
                </div>
              </div>
              {{-- <br> --}}
              <table class="reportTable">
                <thead>
                <tr>
                  <th rowspan="2">Buyer</th>
                  <th rowspan="2">Style</th>
                  <th rowspan="2">PO</th>
                  <th rowspan="2">PO Qty</th>
                  <th rowspan="2">Order Qty</th>
                  <th rowspan="2">Color</th>
                  @if($sizes && count($sizes))
                    @foreach($sizes as $key => $size)
                      <th colspan="2">{{ $size }}</th>
                    @endforeach
                  @endif
                  <th colspan="2">Grand Total</th>
                </tr>
                <tr>
                  @if($sizes && count($sizes))
                    @foreach($sizes as $key => $size)
                      @php
                        ${'total_bundle_'.$key} = 0;
                        ${'total_quantity_'.$key} = 0;
                      @endphp
                      <th>Bundle</th>
                      <th>Qty</th>
                    @endforeach
                  @endif
                  <th>Bundle</th>
                  <th>Qty</th>
                </tr>
                </thead>
                <tbody>
                @isset($bundleCards)
                  @php
                    $total_po_qty = 0;
                    $grand_total_quantity = 0;
                    $grand_total_bundle_count = 0;
                    $order_ids = [];
                  @endphp
                  @foreach($bundleCards->groupBy('buyer_id') as $groupByBuyer)
                    @foreach($groupByBuyer->groupBy('order_id') as $groupByOrder)
                      @foreach($groupByOrder->groupBy('purchase_order_id') as $groupByPurchaseOrder)
                        @foreach($groupByPurchaseOrder->groupBy('color_id') as $groupByColor)
                          @php
                            $total_po_qty += $groupByPurchaseOrder->first()->purchaseOrder->po_quantity ?? 0;
                            $po_color_wise_bundle = $groupByColor->count();
                            $po_color_wise_quantity = $groupByColor->sum('quantity') - $groupByColor->sum('total_rejection');
                            $grand_total_bundle_count += $groupByColor->count();
                            $grand_total_quantity += $groupByColor->sum('quantity') - $groupByColor->sum('total_rejection');
                          @endphp
                          <tr>
                            <td>{{ $groupByBuyer->first()->buyer->name ?? '' }}</td>
                            <td>{{ $groupByOrder->first()->order->style_name ?? '' }}</td>
                            <td>{{ $groupByPurchaseOrder->first()->purchaseOrder->po_no ?? '' }}</td>
                            <td>{{ $groupByPurchaseOrder->first()->purchaseOrder->po_quantity ?? 0 }}</td>
                            @if(!in_array($groupByOrder->first()->order_id, $order_ids))
                              <td rowspan="{{ $groupByOrder->groupBy('purchase_order_id')->count() * $groupByPurchaseOrder->groupBy('color_id')->count() }}">{{ $groupByOrder->first()->order->pq_qty_sum ?? 0 }}</td>
                            @endif
                            <td>{{ $groupByColor->first()->color->name ?? '' }}</td>
                            @if($sizes && count($sizes))
                              @foreach($sizes as $key => $size)
                                @php
                                  $bundle = 0;
                                  $quantity = 0;
                                  if($groupByColor->where('size_id', $key)->count()) {
                                    $bundle = $groupByColor->where('size_id', $key)->count();
                                    $quantity = $groupByColor->where('size_id', $key)->sum('quantity') - $groupByColor->where('size_id', $key)->sum('total_rejection');
                                    ${'total_bundle_'.$key} += $bundle;
                                    ${'total_quantity_'.$key} += $quantity;
                                  }
                                @endphp
                                <td>{{ $bundle }}</td>
                                <td>{{ $quantity }}</td>
                              @endforeach
                            @endif
                            <td>{{ $po_color_wise_bundle }}</td>
                            <td>{{ $po_color_wise_quantity }}</td>
                          </tr>
                          @php
                            $order_ids[] = $groupByPurchaseOrder->first()->order_id;
                          @endphp
                        @endforeach
                      @endforeach
                    @endforeach
                  @endforeach
                  <tr style="font-weight: bold">
                    <td colspan="6" class="text-right"><b>Total :</b></td>
                    @if($sizes && count($sizes))
                      @foreach($sizes as $key => $size)
                        <td>{{ ${'total_bundle_'.$key} }}</td>
                        <td>{{ ${'total_quantity_'.$key} }}</td>
                      @endforeach
                    @endif
                    <td>{{ $grand_total_bundle_count }}</td>
                    <td>{{ $grand_total_quantity }}</td>
                  </tr>
                @endisset
                </tbody>
              </table>

              @if($gatePassChallan)
                <table align="center" class="autorized_table">
                  <tr class="text-center" style="font-weight: bold">
                    <td>Prepared By</td>
                    <td>Cutting Incharge/Manager</td>
                    <td>Authorised Signature</td>
                    <td>Print/Embr. Recieve</td>
                    <td>Security Dept.</td>
                  </tr>
                </table>
              @endif

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('script-head')
  <script>
    $(function () {
      $('body').on('click', '.no-print', function () {
        let challan = $(this).closest('.single-challan-row').clone();
        $('.box').append(challan);
        window.print();
        $(".single-challan-row").not(':first').remove();
      });
    });
  </script>
@endpush