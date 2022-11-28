@extends('sewingdroplets::layout')
@section('title', 'Sewing Output Challan')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Sewing Output Challan</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <div class="factory-area text-center" style="font-size: 1.1em;">
              @if($challanData)
                @php
                  $sewingoutput = $challanData->first()->sewingoutput ?? null;
                @endphp
                <strong>User: {{ $sewingoutput->user->email ?? '' }}</strong><br/>
                <strong>Date: {{ $sewingoutput->created_at->toDateString() ?? '' }}</strong>
              @endif
            </div>
            <hr>
            <div class="table-responsive">
              <table class="reportTable">
                <thead>
                <tr>
                  <th>Buyer</th>
                  <th>Style</th>
                  <th>Purchase Order</th>
                  <th>Color</th>
                  <th>Size</th>
                  <th>Total Bundle</th>
                  <th>Quantity</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($challanData))
                  @foreach($challanData->groupBy('buyer_id') as $groupByBuyer)
                    @foreach($groupByBuyer->groupBy('order_id') as $groupByOrder)
                      @foreach($groupByOrder->groupBy('purchase_order_id') as $groupByPurchaseOrder)
                        @foreach($groupByPurchaseOrder->groupBy('color_id') as $groupByColor)
                          @foreach($groupByColor->groupBy('size_id') as $groupBySize)
                            @php
                              $singleBundle = $groupBySize->first();
                              $sizeWisetotal = $groupBySize->sum('quantity')
                                - $groupBySize->sum('total_rejection')
                                - $groupBySize->sum('print_embroidary_rejection')
                                - $groupBySize->sum('sewing_rejection');
                            @endphp
                            <tr>
                              <td>{{ $singleBundle->buyer->name ?? '' }}</td>
                              <td>{{ $singleBundle->order->style_name ?? '' }}</td>
                              <td>{{ $singleBundle->purchaseOrder->po_no ?? '' }}</td>
                              <td>{{ $singleBundle->color->name ?? '' }}</td>
                              <td>{{ $singleBundle->size->name ?? '' }}</td>
                              <td>{{ count($groupBySize) }}</td>
                              <td>{{ $sizeWisetotal }}
                              </td>
                            </tr>
                          @endforeach
                        @endforeach
                      @endforeach
                    @endforeach
                  @endforeach
                  <tr style="font-weight: bold">
                    <td colspan="5" class="text-right"><b>Total &nbsp;</b></td>
                    <td>{{ count($challanData) }}</td>
                    <td>
                      {{
                        $challanData->sum('quantity')
                          - $challanData->sum('total_rejection')
                          - $challanData->sum('print_embroidary_rejection')
                          - $challanData->sum('sewing_rejection')
                      }}
                    </td>
                  </tr>
                @else
                  <tr>
                    <td colspan="6" align="center">Not found
                    <td>
                  </tr>
                @endif
                </tbody>
              </table>
            </div>
            <hr>
            <div class="table-responsive">
              <table class="reportTable">
                <thead>
                <tr>
                  <th>Buyer</th>
                  <th>Style</th>
                  <th>Purchase Order</th>
                  <th>Color</th>
                  <th>Total Bundle</th>
                  <th>Quantity</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($challanData))
                  @foreach($challanData->groupBy('buyer_id') as $groupByBuyer)
                    @foreach($groupByBuyer->groupBy('order_id') as $groupByOrder)
                      @foreach($groupByOrder->groupBy('purchase_order_id') as $groupByPurchaseOrder)
                        @foreach($groupByPurchaseOrder->groupBy('color_id') as $groupByColor)
                          @php
                            $singleBundle = $groupByColor->first();
                            $sizeWisetotal = $groupByColor->sum('quantity')
                              - $groupByColor->sum('total_rejection')
                              - $groupByColor->sum('print_embroidary_rejection')
                              - $groupByColor->sum('sewing_rejection');
                          @endphp
                          <tr>
                            <td>{{ $singleBundle->buyer->name ?? '' }}</td>
                            <td>{{ $singleBundle->order->style_name ?? '' }}</td>
                            <td>{{ $singleBundle->purchaseOrder->po_no ?? '' }}</td>
                            <td>{{ $singleBundle->color->name ?? '' }}</td>
                            <td>{{ count($groupByColor) }}</td>
                            <td>{{ $sizeWisetotal }}
                            </td>
                          </tr>
                        @endforeach
                      @endforeach
                    @endforeach
                  @endforeach
                  <tr style="font-weight: bold">
                    <td colspan="4" class="text-right"><b>Total &nbsp;</b></td>
                    <td>{{ count($challanData) }}</td>
                    <td>
                      {{
                        $challanData->sum('quantity')
                          - $challanData->sum('total_rejection')
                          - $challanData->sum('print_embroidary_rejection')
                          - $challanData->sum('sewing_rejection')
                      }}
                    </td>
                  </tr>
                @else
                  <tr>
                    <td colspan="6" align="center">Not found
                    <td>
                  </tr>
                @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection