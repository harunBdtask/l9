@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('washingdroplets::layout')
@section('title', 'Color Wise Washing Report')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Color Wise Washing Report || {{ date("jS F, Y") }} <span class="pull-right"><a
                    href="{{ $pdf_url ? url($pdf_url) : '#' }}"><i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | <a
                    href="{{ $excel_url ? url($excel_url) : '#' }}"><i style="color: #0F733B"
                                                                       class="fa fa-file-excel-o"></i></a></span></h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body order-wise-print">
            <form action="{{ url('/color-wise-washing-report') }}" method="post">
              @csrf

              {!! Form::hidden('form_submit', 1) !!}
              <div class="form-group">
                <div class="row m-b">
                  <div class="col-sm-2">
                    <label>Buyer</label>
                    {!! Form::select('buyer_id', $buyers, $reportParameter['buyer_id'] ?? null, ['class' => 'buyer-color-washing-report form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer']) !!}

                    @if($errors->has('buyer_id'))
                      <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
                    @endif
                  </div>
                  <div class="col-sm-2">
                    <label>Style/Order No</label>
                    {!! Form::select('order_id', $styles ?? [], $reportParameter['order_id'] ?? null, ['class' => 'style-color-washing-report form-control form-control-sm select2-input', 'placeholder' => 'Select Style']) !!}

                    @if($errors->has('order_id'))
                      <span class="text-danger">{{ $errors->first('order_id') }}</span>
                    @endif
                  </div>
                  <div class="col-sm-2">
                    <label>PO</label>
                    {!! Form::select('purchase_order_id', $orders ?? [], $reportParameter['purchase_order_id'] ?? null, ['class' => 'order-color-washing-report form-control form-control-sm select2-input', 'placeholder' => 'Select a Order']) !!}

                    @if($errors->has('purchase_order_id'))
                      <span class="text-danger">{{ $errors->first('purchase_order_id') }}</span>
                    @endif
                  </div>

                  <div class="col-sm-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-sm white form-control form-control-sm">Search</button>
                  </div>
                </div>
              </div>
            </form>

            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                <thead>
                <tr>
                  <th>Color</th>
                  <th>Cutting Qty</th>
                  <th>Sewing Output</th>
                  <th>Today <br/> Sent</th>
                  <th>Total <br/> Sent</th>
                  <th>Today <br/> Received</th>
                  <th>Total <br/> Received</th>
                  <th>Total <br/> Balance</th>
                  <th>Washing <br/> Rejection</th>
                </tr>
                </thead>
                <tbody class="print-sent-received-report">
                @if($reportData)
                  @php
                    $sum_cuttingQty = 0;
                    $sum_sewingOutputQty = 0;
                    $sum_today_send = 0;
                    $sum_total_send = 0;
                    $sum_today_received = 0;
                    $sum_total_received = 0;
                    $sum_total_balance = 0;
                    $sum_total_rejection = 0;
                  @endphp
                  @foreach($reportData as $report)
                    @php
                      $sum_cuttingQty += $report->total_cutting - $report->total_cutting_rejection ?? 0;
                      $sum_sewingOutputQty += $report->total_sewing_output ?? 0;
                      $sum_today_send += $report->todays_washing_sent ?? 0;
                      $sum_total_send += $report->total_washing_sent ?? 0;
                      $sum_today_received += $report->todays_washing_received ?? 0;
                      $sum_total_received += $report->total_washing_received ?? 0;
                      $sum_total_balance += $report->total_washing_sent - $report->total_washing_received ?? 0;
                      $sum_total_rejection += $report->total_washing_rejection ?? 0;
                    @endphp
                    <tr style="height: 35px !important;">
                      <td>{{$report->colors->name ?? 'Color'}}</td>
                      <td>{{$report->total_cutting - $report->total_cutting_rejection ?? 0}}</td>
                      <td>{{$report->total_sewing_output ?? 0}}</td>
                      <td>{{$report->todays_washing_sent ?? 0}}</td>
                      <td>{{$report->total_washing_sent ?? 0}}</td>
                      <td>{{$report->todays_washing_received ?? 0}}</td>
                      <td>{{$report->total_washing_received ?? 0}}</td>
                      <td>{{$report->total_washing_sent - $report->total_washing_received ?? 0}}</td>
                      <td>{{$report->total_washing_rejection ?? 0}}</td>
                    </tr>
                  @endforeach
                  <tr style="height: 35px !important; font-weight: bold;">
                    <td>Total</td>
                    <td>{{ $sum_cuttingQty }}</td>
                    <td>{{ $sum_sewingOutputQty }}</td>
                    <td>{{ $sum_today_send }}</td>
                    <td>{{ $sum_total_send }}</td>
                    <td>{{ $sum_today_received }}</td>
                    <td>{{ $sum_total_received }}</td>
                    <td>{{ $sum_total_balance }}</td>
                    <td>{{ $sum_total_rejection }}</td>
                  </tr>
                @else
                  <tr style="height: 30px !important; font-weight: bold;">
                    <td colspan="15" class="text-danger">Data not found</td>
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
