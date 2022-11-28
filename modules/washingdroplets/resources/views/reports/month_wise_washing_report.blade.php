@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('washingdroplets::layout')
@section('title', 'Month Wise Washing Report')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Month Wise Washing Report || {{ date("jS F, Y") }} <span class="pull-right"><a
                    href="{{url('month-wise-washing-report-download/pdf/'.$from_date.'/'.$to_date)}}"><i
                      style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | <a
                    href="{{url('month-wise-washing-report-download/xls/'.$from_date.'/'.$to_date)}}"><i
                      style="color: #0F733B" class="fa fa-file-excel-o"></i></a></span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">

            <form action="{{ url('/month-wise-washing-report') }}" method="post">
              @csrf
              <div class="form-group">
                <div class="row m-b">
                  <div class="col-sm-3">
                    <label>Date</label>
                    <input type="date" name="from_date" class="form-control form-control-sm" required="required"
                           value="{{ $from_date ?? date('Y-m-d') }}">
                    @if($errors->has('from_date'))
                      <span class="text-danger">{{ $errors->first('from_date') }}</span>
                    @endif
                  </div>
                  <div class="col-sm-3">
                    <label>To Date</label>
                    <input type="date" name="to_date" class="form-control form-control-sm" required="required"
                           value="{{ $to_date ?? date('Y-m-d') }}">

                    @if($errors->has('to_date'))
                      <span class="text-danger">{{ $errors->first('to_date') }}</span>
                    @endif
                  </div>
                  <div class="col-sm-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-sm white form-control form-control-sm">Search</button>
                  </div>
                </div>
              </div>
            </form>
            {{--                        @include('washingdroplets::reports.table.date_wise_washing_report')--}}
            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                <thead>
                <tr>
                  <th>Buyer</th>
                  <th>Style/Order No.</th>
                  <th>PO</th>
                  <th>Color</th>
                  <th>Total Sent</th>
                  <th>Total Received</th>
                  <th>Washing Rejection</th>
                </tr>
                </thead>
                <tbody class="date-wise-report">
                @if($washing_report)
                  @foreach($washing_report->groupBy('purchase_order_id') as $groupByOrder)
                    @foreach($groupByOrder->groupBy('color_id') as $groupByColor)
                      @php
                        $buyer_name = $groupByOrder->first()['buyer_name'] ?? 'Buyer';
                        $order_style_no = $groupByOrder->first()['order_style_no'] ?? '';
                        $po_no = $groupByOrder->first()['po_no'] ?? '';
                        $color = $groupByColor->first()['color'] ?? '';

                        $total_sent = 0;
                        $total_received = 0;
                        $total_rejected = 0;
                      @endphp
                      @foreach($groupByColor as $details)
                        @php

                          $total_sent += $details['total_wash_sent'];
                          $total_received += $details['total_wash_received'];
                          $total_rejected += $details['total_wash_rejection'];

                        @endphp
                      @endforeach
                      <tr>
                        <td>{{ $buyer_name }}</td>
                        <td>{{ $order_style_no }}</td>
                        <td>{{ $po_no }}</td>
                        <td>{{ $color }}</td>
                        <td>{{ $total_sent }}</td>
                        <td>{{ $total_received }}</td>
                        <td>{{ $total_rejected }}</td>
                      </tr>
                    @endforeach
                  @endforeach
                  <tr>
                    <th colspan="4">Total</th>
                    <th>{{$grand_total_sent}}</th>
                    <th>{{$grand_total_received}}</th>
                    <th>{{$grand_total_rejected}}</th>
                  </tr>
                @else
                  <tr>
                    <th colspan="7" align="center">No Data</th>
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
