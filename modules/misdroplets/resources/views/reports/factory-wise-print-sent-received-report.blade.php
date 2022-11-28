@php
$tableHeadColorClass = 'tableHeadColor';
if (isset($type) || request()->has('type') || request()->route('type')) {
$tableHeadColorClass = '';
}
@endphp
@extends('misdroplets::layout')
@section('title', 'Factory Wise Print Sent &amp; Received Report')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Factory Wise Print Sent &amp; Received Report || {{ date("jS F, Y") }}</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">

          <form action="{{ url('/factory-wise-print-sent-received-report') }}" method="get" autocomplete="off">
            @csrf
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-2">
                  <label>Factory</label>
                  {!! Form::select('factory_id', $factories, request()->factory_id ?? null, ['id' => 'factory_id',
                  'class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select a Factory']) !!}
                  @if($errors->has('factory_id'))
                  <span class="text-danger">{{ $errors->first('factory_id') }}</span>
                  @endif
                </div>
                <div class="col-sm-2">
                  <label>Form Date</label>
                  {!! Form::date('from_date', request()->from_date ?? null, ['id' => 'from_date', 'class' =>
                  'form-control form-control-sm', 'placeholder' => 'Enter from date']) !!}
                </div>
                <div class="col-sm-2">
                  <label>To Date</label>
                  {!! Form::date('to_date', request()->to_date ?? null, ['id' => 'to_date', 'class' =>
                  'form-control form-control-sm', 'placeholder' => 'Enter from date']) !!}

                  @if($errors->has('to_date'))
                  <span class="text-danger">{{ $errors->first('to_date') }}</span>
                  @endif
                </div>
                <div class="col-sm-2">
                  <label>&nbsp;</label>
                  <button class="btn btn-sm btn-info form-control form-control-sm" type="submit">Search</button>
                </div>
              </div>
            </div>
          </form>

          <table class="reportTable">
            <thead>
              <tr>
                <th rowspan="2">Sl</th>
                <th rowspan="2">Factory Name</th>
                <th colspan="6">PRINT SECTION</th>
                <th colspan="6">EMBR. SECTION</th>
              </tr>
              <tr>
                <th>Today Sent</th>
                <th>Today Received</th>
                <th>Today Rejection</th>
                <th>Total Sent</th>
                <th>Total Received</th>
                <th>Total Rejection</th>
                <th>Today Sent</th>
                <th>Today Received</th>
                <th>Today Rejection</th>
                <th>Total Sent</th>
                <th>Total Received</th>
                <th>Total Rejection</th>
              </tr>
            </thead>
            <tbody>
              @php
                $g_today_print_sent_qty = 0;
                $g_today_print_received_qty = 0;
                $g_today_print_rejection_qty = 0;
                $g_today_embroidery_sent_qty = 0;
                $g_today_embroidery_received_qty = 0;
                $g_today_embroidery_rejection_qty = 0;
                $g_total_print_sent_qty = 0;
                $g_total_print_received_qty = 0;
                $g_total_print_rejection_qty = 0;
                $g_total_embroidery_sent_qty = 0;
                $g_total_embroidery_received_qty = 0;
                $g_total_embroidery_rejection_qty = 0;
              @endphp
              @if($factory_wise_report_data && count($factory_wise_report_data))
                @foreach ($factory_wise_report_data as $report)
                  @php
                    $g_today_print_sent_qty += $report['today_print_sent_qty'] ?? 0;
                    $g_today_print_received_qty += $report['today_print_received_qty'] ?? 0;
                    $g_today_print_rejection_qty += $report['today_print_rejection_qty'] ?? 0;
                    $g_today_embroidery_sent_qty += $report['today_embroidery_sent_qty'] ?? 0;
                    $g_today_embroidery_received_qty += $report['today_embroidery_received_qty'] ?? 0;
                    $g_today_embroidery_rejection_qty += $report['today_embroidery_rejection_qty'] ?? 0;
                    $g_total_print_sent_qty += $report['total_print_sent_qty'] ?? 0;
                    $g_total_print_received_qty += $report['total_print_received_qty'] ?? 0;
                    $g_total_print_rejection_qty += $report['total_print_rejection_qty'] ?? 0;
                    $g_total_embroidery_sent_qty += $report['total_embroidery_sent_qty'] ?? 0;
                    $g_total_embroidery_received_qty += $report['total_embroidery_received_qty'] ?? 0;
                    $g_total_embroidery_rejection_qty += $report['total_embroidery_rejection_qty'] ?? 0;
                  @endphp
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $report['factory']->factory_name }}</td>
                    <td>{{ $report['today_print_sent_qty'] }}</td>
                    <td>{{ $report['today_print_received_qty'] }}</td>
                    <td>{{ $report['today_print_rejection_qty'] }}</td>
                    <td>{{ $report['total_print_sent_qty'] }}</td>
                    <td>{{ $report['total_print_received_qty'] }}</td>
                    <td>{{ $report['total_print_rejection_qty'] }}</td>
                    <td>{{ $report['today_embroidery_sent_qty'] }}</td>
                    <td>{{ $report['today_embroidery_received_qty'] }}</td>
                    <td>{{ $report['today_embroidery_rejection_qty'] }}</td>
                    <td>{{ $report['total_embroidery_sent_qty'] }}</td>
                    <td>{{ $report['total_embroidery_received_qty'] }}</td>
                    <td>{{ $report['total_embroidery_rejection_qty'] }}</td>
                  </tr>
                @endforeach
                <tr>
                  <th colspan="2">Grand Total</th>
                  <th>{{ $g_today_print_sent_qty }}</th>
                  <th>{{ $g_today_print_received_qty }}</th>
                  <th>{{ $g_today_print_rejection_qty }}</th>
                  <th>{{ $g_total_print_sent_qty }}</th>
                  <th>{{ $g_total_print_received_qty }}</th>
                  <th>{{ $g_total_print_rejection_qty }}</th>
                  <th>{{ $g_today_embroidery_sent_qty }}</th>
                  <th>{{ $g_today_embroidery_received_qty }}</th>
                  <th>{{ $g_today_embroidery_rejection_qty }}</th>
                  <th>{{ $g_total_embroidery_sent_qty }}</th>
                  <th>{{ $g_total_embroidery_received_qty }}</th>
                  <th>{{ $g_total_embroidery_rejection_qty }}</th>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection