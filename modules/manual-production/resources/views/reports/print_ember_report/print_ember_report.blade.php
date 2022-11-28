@extends('manual-production::layout')
@section('title', 'Date Wise Cutting Production Summary')
@section('content')
<div class="padding">
  <div class="row manual-date-wise-cutting-report">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Date Wise Print Embroidery Report</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <div class="row">
            <div class="col-sm-12">
              @error('date_to')
              <div class="flash-message">
                <p class="text-center alert alert-danger">{{ $message }}</p>
              </div>
              @enderror
              <form action="">
                <div class="row">
                  <div class="col-sm-3">
                    <label for="date_from">Date From</label>
                    <input id="date_from" value="{{ $date_from }}" name="date_from" type="date" class="form-control form-control-sm">
                  </div>
                  <div class="col-sm-3">
                    <label for="date_to">Date To</label>
                    <input id="date_to" value="{{ $date_to }}" name="date_to" type="date" class="form-control form-control-sm">
                  </div>
                  <div class="col-sm-3">
                    <button style="margin-top: 28px" class="btn btn-sm btn-info">Search</button>
                  </div>
                  <div class="col-sm-3">
                    <div class="pull-right" style="margin-top: 28px">
                      <a href="{{ url('manual-date-wise-print-embr-report-pdf?date_from='.$date_from.'&date_to='.$date_to) }}"
                        class="btn btn-sm white m-b"><i class="fa fa-file-pdf-o"></i></a>
                      <a href="{{ url('manual-date-wise-print-embr-report-excel?date_from='.$date_from.'&date_to='.$date_to) }}"
                        class="btn btn-sm btn-primary"><i class="fa fa-file-excel-o"></i></a>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="row m-t">
            <div class="col-sm-12">
              <table class="reportTable">
                <thead>
                  <tr>
                    <th colspan="9">Section-1: PO Wise</th>
                  </tr>
                  <tr>
                    <th>SL</th>
                    <th>Factory Name</th>
                    <th>Buyer</th>
                    <th>Style/Order No</th>
                    <th>PO</th>
                    <th>Print Send Quantity</th>
                    <th>Print Receive Quantity</th>
                    <th>Embroidery Send Quantity</th>
                    <th>Embroidery Receive Quantity</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse(collect($data)->groupBy('purchase_order_id')->values() as $key => $item)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ collect($item)->first()['factory']['factory_name'] ?? 'N/A' }}</td>
                    <td>{{ collect($item)->first()['buyer']['name'] ?? 'N/A' }}</td>
                    <td>{{ collect($item)->first()['order']['style_name'] ?? 'N/A' }}</td>
                    <td>{{ collect($item)->first()['purchaseOrder']['po_no'] ?? 'N/A' }}</td>
                    <td>{{ collect($item)->sum('print_sent_qty') }}</td>
                    <td>{{ collect($item)->sum('print_receive_qty') }}</td>
                    <td>{{ collect($item)->sum('embroidery_sent_qty') }}</td>
                    <td>{{ collect($item)->sum('embroidery_receive_qty') }}</td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="9">No Data Found</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
          <div class="row m-t">
            <div class="col-sm-12">
              <table class="reportTable">
                <thead>
                  <tr>
                    <th colspan="10">Section-2: Color Wise</th>
                  </tr>
                  <tr>
                    <th>SL</th>
                    <th>Factory Name</th>
                    <th>Buyer</th>
                    <th>Style/Order No</th>
                    <th>PO</th>
                    <th>Color</th>
                    <th>Print Send Quantity</th>
                    <th>Print Receive Quantity</th>
                    <th>Embroidery Send Quantity</th>
                    <th>Embroidery Receive Quantity</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse(collect($data)->groupBy(['purchase_order_id', 'color_id'])->values() as $itemAsPO)
                  @foreach($itemAsPO as $item)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ collect($item)->first()['factory']['factory_name'] ?? 'N/A' }}</td>
                    <td>{{ collect($item)->first()['buyer']['name'] ?? 'N/A' }}</td>
                    <td>{{ collect($item)->first()['order']['style_name'] ?? 'N/A' }}</td>
                    <td>{{ collect($item)->first()['purchaseOrder']['po_no'] ?? 'N/A' }}</td>
                    <td>{{ collect($item)->first()['color']['name'] ?? 'N/A' }}</td>
                    <td>{{ collect($item)->sum('print_sent_qty') }}</td>
                    <td>{{ collect($item)->sum('print_receive_qty') }}</td>
                    <td>{{ collect($item)->sum('embroidery_sent_qty') }}</td>
                    <td>{{ collect($item)->sum('embroidery_receive_qty') }}</td>
                  </tr>
                  @endforeach
                  @empty
                  <tr>
                    <td colspan="10">No Data Found</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
          <div class="row m-t">
            <div class="col-sm-12">
              <table class="reportTable">
                <thead>
                  <tr>
                    <th colspan="6">Section-3: Factory Wise</th>
                  </tr>
                  <tr>
                    <th>Factory Name</th>
                    <th>Factory Address</th>
                    <th>Print Send Quantity</th>
                    <th>Print Receive Quantity</th>
                    <th>Embroidery Send Quantity</th>
                    <th>Embroidery Receive Quantity</th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($data))
                  <tr>
                    <td>{{ collect($data)->first()['factory']['factory_name'] ?? 'N/A' }}</td>
                    <td>{{ collect($data)->first()['factory']['factory_address'] ?? 'N/A' }}</td>
                    <td>{{ collect($data)->sum('print_sent_qty') }}</td>
                    <td>{{ collect($data)->sum('print_receive_qty') }}</td>
                    <td>{{ collect($data)->sum('embroidery_sent_qty') }}</td>
                    <td>{{ collect($data)->sum('embroidery_receive_qty') }}</td>
                  </tr>
                  @else
                  <tr>
                    <td colspan="6">No Data Found</td>
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
</div>
@endsection
@section('scripts')
@endsection
