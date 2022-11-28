@extends('washingdroplets::layout')
@section('title', 'Washing Challan')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Washing Challan</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <div class="factory-area text-center" style="font-size: 1.1em;">
              @if($washingChallan)
                <strong>{{ currentUser()->factory->group_name ?? ''}}</strong><br>
                {{ currentUser()->factory->factory_address ?? '' }}<br>
                <span>Unit: {{ currentUser()->factory->factory_name ?? '' }}</span><br>
                <strong>Washing Challan</strong><br>
                <span>{{implode(', ', $floor_array)}}</span><br>
              @endif
            </div>
            <hr>
            <div class="col-sm-12 text-center" style="margin-bottom: 20px;">
              <div style="font-size: 1.05em"><strong>Sending To:</strong></div>
              <div class="row">
                <div class="col-sm-12">
                  <strong>Factory Name : </strong> {{ $washingChallan->printWashFactory->factory_name ?? '' }}
                  | <strong>Address : </strong> {{ $washingChallan->printWashFactory->factory_address ?? '' }}
                </div>
              </div>

              <div class="row">
                <div class="col-sm-12">
                  <strong>Challan No: </strong> {{ $washingChallan->washing_challan_no ?? '' }}
                  | <strong>Bag(s): </strong> {{ $washingChallan->bag ?? '' }}
                  @if(isset($washingChallan)) |
                  <strong>Date : </strong>  {{ date('jS F, Y', strtotime($washingChallan->updated_at)) }} @endif
                  {{--  | <strong>Time : </strong>@if(isset($washingChallan)) {{ date('h:i:s ', strtotime($washingChallan->updated_at)) }} @endif --}}
                </div>
              </div>
            </div>
            <br>
            <table class="reportTable">
              <thead>
              <tr>
                <th>Buyer</th>
                <th>Style</th>
                <th>PO</th>
                <th>Color</th>
                <th>Size</th>
                <th>No. of Bundle</th>
                <th>Quantity</th>
                <th width="30%">Remarks</th>
              </tr>
              </thead>
              <tbody>
              @if(isset($washingChallan))
                @php $totalQty = 0; @endphp
                @foreach ($washingChallan->washings->sortBy('buyer_id')->groupBy('buyer_id') as $buyerWsie)
                  @foreach ($buyerWsie->sortBy('order_id')->groupBy('order_id') as $orderStyleWise)
                    @foreach ($orderStyleWise->sortBy('purchase_order_id')->groupBy('purchase_order_id') as $purchaseOrderWise)
                      @foreach ($purchaseOrderWise->sortBy('color_id')->groupBy('color_id') as $color)
                        @foreach ($color->sortBy('size_id')->groupBy('size_id') as $sizeKey => $sizeBundles)
                          @php $qty = 0; @endphp
                          @foreach ($sizeBundles as $key => $bundle)
                            @php
                              $totalQty += $bundle->bundlecard->quantity - $bundle->bundlecard->total_rejection - $bundle->bundlecard->print_rejection - $bundle->bundlecard->sewing_rejection;

                              $qty += $bundle->bundlecard->quantity - $bundle->bundlecard->total_rejection
                              - $bundle->bundlecard->print_rejection - $bundle->bundlecard->sewing_rejection;
                            @endphp
                          @endforeach
                          <tr>
                            <td>{{ $bundle->buyer->name ?? '' }}</td>
                            <td>{{ $bundle->order->style_name ?? '' }}</td>
                            <td>{{ $bundle->purchaseOrder->po_no ?? '' }}</td>
                            <td>{{ $color->first()->bundlecard->color->name ?? '' }}</td>
                            <td>{{ $bundle->size->name ?? '' }}</td>
                            <td>{{ count($sizeBundles) ?? '' }}</td>
                            <td>{{ $qty }}</td>
                            <td></td>
                          </tr>
                        @endforeach
                      @endforeach
                    @endforeach
                  @endforeach
                @endforeach

                <tr style="font-weight: bold">
                  <td colspan="5" class="text-right"><b>Total :</b></td>
                  <td>{{ str_pad(count($washingChallan->washings), 2, '0', STR_PAD_LEFT) }}</td>
                  <td>{{ str_pad($totalQty, 3, '0', STR_PAD_LEFT) }}</td>
                  <td></td>
                </tr>
              @else
                <tr>
                  <td colspan="6" align="center">Not found
                  <td>
                </tr>
              @endif
              </tbody>
            </table>

            @if(isset($washingChallan))
              <table align="center" width="100%" style="margin-top: 100px; margin-bottom: 35px" class="autorized_table">
                <tr style="font-weight: bold">
                  <td style="width: 5%"></td>
                  <td style="width: 15%">Prepared By</td>
                  <td style="width: 20%">Manager</td>
                  <td style="width: 20%">Authorised By</td>
                  <td style="width: 20%">Received By</td>
                  <td style="width: 15%">Driver</td>
                  <td style="width: 5%"></td>
                </tr>
              </table>
            @endif

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
