@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('printembrdroplets::layout')
@section('title', 'Date Wise Print/Embr. Send Summary')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Date Wise Print/Embr. Send Summary || {{ date("jS F, Y") }} <span class="pull-right"><a
                    href="{{url('date-wise-print-send-report-download?type=pdf&from_date='.($from_date ?? null).'&to_date='.($to_date ?? null))}}"><i
                      style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | <a
                    href="{{url('date-wise-print-send-report-download?type=xls&from_date='.($from_date ?? null).'&to_date='.($to_date ?? null))}}"><i
                      style="color: #0F733B" class="fa fa-file-excel-o"></i></a></span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <form action="{{ url('/date-wise-print-send-report-post') }}" method="post">
              @csrf
              <div class="form-group">
                <div class="row m-b">
                  <div class="col-sm-2">
                    <label>From Date</label>
                    <input type="date" name="from_date" class="form-control form-control-sm" value="{{ $from_date ?? 0 }}"
                           required="required">
                  </div>
                  <div class="col-sm-2">
                    <label>To Date</label>
                    <input type="date" name="to_date" class="form-control form-control-sm" value="{{ $to_date ?? 0 }}"
                           required="required">
                    @if(Session::has('error'))
                      <span class="text-danger">{{ Session::get('error') }}</span>
                    @endif
                  </div>
                  <div class="col-sm-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-sm white form-control form-control-sm">Search</button>
                  </div>
                </div>
              </div>
            </form>
            {{-- Old Code --}}
            <table class="reportTable">
              <thead>
              <tr>
                <th colspan="4">Section-1: PO Wise</th>
              </tr>
              <tr>
                <th>Buyer</th>
                <th>Booking No</th>
                <th>Style/Order No</th>
                <th>PO</th>
                <th>Send Quantity</th>
              </tr>
              </thead>
              <tbody class="color-wise-report">
              @if(!empty($order_wise_report))
                @php $tsend_qty_order = 0; @endphp
                @foreach($order_wise_report as $order_wise)
                  @php
                    $tsend_qty_order += $order_wise['send_qty_order'];
                  @endphp
                  <tr>
                    <td>{{ $order_wise['buyer'] }}</td>
                    <td>{{ $order_wise['style'] }}</td>
                    <td>{{ $order_wise['order'] }}</td>
                    <td>{{ $order_wise['send_qty_order'] }}</td>
                  </tr>
                @endforeach
                <tr style="font-weight: bold">
                  <td colspan="3">Total</td>
                  <td>{{ $tsend_qty_order }}</td>
                </tr>
              @else
                <tr>
                  <td colspan="4" class="text-danger text-center">Not found
                  <td>
                </tr>
              @endif
              </tbody>
            </table>

            <table class="reportTable">
              <thead>
              <tr>
                <th colspan="6">Section-2: Color Wise</th>
              </tr>
              <tr>
                <th>Buyer</th>
                <th>Booking No</th>
                <th>Style/Order No</th>
                <th>PO</th>
                <th>Colour Name</th>
                <!--  <th>Size Name</th> -->
                <th>Send Quantity</th>
              </tr>
              </thead>
              <tbody class="color-wise-report">
              @if(!empty($color_wise_report))
                @php $tsend_qty_color = 0; @endphp
                @foreach($color_wise_report as $report)
                  @php
                    $tsend_qty_color += $report['send_qty_color'];
                  @endphp
                  <tr>
                    <td>{{ $report['buyer'] }}</td>
                    <td>{{ $report['style'] }}</td>
                    <td>{{ $report['order'] }}</td>
                    <td>{{ $report['color'] }}</td>
                    {{-- <td>{{ $report['size'] }}</td> --}}
                    <td>{{ $report['send_qty_color'] }}</td>
                  </tr>
                @endforeach
                <tr style="font-weight: bold">
                  <td colspan="4">Total</td>
                  <td>{{ $tsend_qty_color }}</td>
                </tr>
              @else
                <tr>
                  <td colspan="6" class="text-danger text-center">Not found
                  <td>
                </tr>
              @endif
              </tbody>
            </table>

            {{-- New Code with bug
            <table class="reportTable">
              <thead>
                <tr>
                  <th colspan="4">Section-1: PO Wise</th>
                </tr>
                <tr>
                  <th>Buyer</th>
                  <th>Style</th>
                  <th>PO</th>
                  <th>Send Quantity</th>
                </tr>
              </thead>
              <tbody class="color-wise-report">
                @if(!empty($po_wise_print_summary))
                  @php $tsend_qty_order = 0; @endphp
                  @foreach($po_wise_print_summary->groupBy('purchase_order_id') as $reportByOrder)
                    @php $send_qty = 0; @endphp
                    @foreach($reportByOrder as $order_wise)
                    @php
                       $send_qty += $order_wise['send_qty'];
                       $tsend_qty_order += $order_wise['send_qty'];
                       $buyer_name = $order_wise['buyer_name'];
                       $style_name = $order_wise['style_name'];
                       $order_no = $order_wise['order_id'].'-'.$order_wise['order_no'];
                    @endphp
                    @endforeach
                    <tr>
                      <td>{{ $buyer_name }}</td>
                      <td>{{ $style_name }}</td>
                      <td>{{ $order_no }}</td>
                      <td>{{ $send_qty }}</td>
                    </tr>
                  @endforeach
                    <tr style="font-weight: bold">
                      <td colspan="3">Total</td>
                      <td>{{ $tsend_qty_order }}</td>
                    </tr>
                @else
                  <tr>
                    <td colspan="4" class="text-danger text-center">Not found<td>
                  </tr>
                @endif
              </tbody>
            </table>

            <table class="reportTable">
              <thead>
                <tr>
                   <th colspan="5">Section-2: Color Wise</th>
                </tr>
                <tr>
                  <th>Buyer</th>
                  <th>Style</th>
                  <th>PO</th>
                  <th>Colour Name</th>
                  <th>Send Quantity</th>
                </tr>
              </thead>
              <tbody class="color-wise-report">
                @if(!empty($color_wise_print_summary))
                    @php $tsend_qty_color = 0; @endphp
                  @foreach($color_wise_print_summary['print_details'] as $report)
                    @php
                       $tsend_qty_color += $report['send_qty'];
                    @endphp
                    <tr>
                      <td>{{ $report['buyer_name'] }}</td>
                      <td>{{ $report['style_name'] }}</td>
                      <td>{{ $report['order_no'] }}</td>
                      <td>{{ $report['color'] }}</td>
                      <td>{{ $report['send_qty'] }}</td>
                    </tr>
                  @endforeach
                    <tr style="font-weight: bold">
                      <td colspan="4">Total</td>
                      <td>{{ $tsend_qty_color }}</td>
                    </tr>
                @else
                  <tr>
                    <td colspan="5" class="text-danger text-center">Not found<td>
                  </tr>
                @endif
              </tbody>
            </table>
            --}}


            <table class="reportTable">
              <thead>
              <tr>
                <th colspan="5">Section-3: Challan Wise</th>
              </tr>
              <tr>
                <th>Challan</th>
                <th>Factory Name</th>
                <th>Part</th>
                <th>Bag (S)</th>
                <th>Quantity</th>
              </tr>
              </thead>
              <tbody class="color-wise-report">
              @if(count($challan_wise) > 0)
                @php $tsend_qtyy = 0; @endphp
                @foreach($challan_wise as $challan)
                  @php
                    $send_qtyy = 0;
                    foreach($challan->print_inventory as $print_inv){
                       $send_qtyy += $print_inv->bundle_card->quantity ?? 0;
                    }
                    $tsend_qtyy += $send_qtyy;
                  @endphp
                  <tr>
                    <td>{{ $challan->challan_no }}</td>
                    <td>{{ $challan->factory->factory_name }}</td>
                    <td>{{ $challan->part->name ?? '' }}</td>
                    <td>{{ $challan->bag }}</td>
                    <td>{{ $send_qtyy }}</td>
                  </tr>
                @endforeach
                <tr style="font-weight: bold">
                  <td colspan="4">Total</td>
                  <td>{{ $tsend_qtyy }}</td>
                </tr>
              @else
                <tr>
                  <td colspan="5" class="text-danger text-center">Not found
                  <td>
                </tr>
              @endif
              </tbody>
            </table>

            <table class="reportTable">
              <thead>
              <tr>
                <th colspan="3">Section-3: Factory Wise</th>
              </tr>
              <tr>
                <th>Factory Name</th>
                <th>Factory Address</th>
                <th>Quantity</th>
              </tr>
              </thead>
              <tbody>
              @if(!empty($factory_wise_report))
                @php $tsend_qty_factory = 0; @endphp
                @foreach($factory_wise_report as $factory)
                  @php $tsend_qty_factory += $factory['send_qty_factory']; @endphp
                  <tr>
                    <td>{{ $factory['factory_name'] }}</td>
                    <td>{{ $factory['factory_address'] }}</td>
                    <td>{{ $factory['send_qty_factory'] }}</td>
                  </tr>
                @endforeach
                <tr style="font-weight: bold">
                  <td colspan="2">Total</td>
                  <td>{{ $tsend_qty_factory }}</td>
                </tr>
              @else
                <tr>
                  <td colspan="3" class="text-danger text-center">Not found
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

  <style type="text/css">
    @media screen and (-webkit-min-device-pixel-ratio: 0) {
      input[type=date].form-control form-control-sm {
        line-height: .75;
      }
    }
  </style>
@endsection
