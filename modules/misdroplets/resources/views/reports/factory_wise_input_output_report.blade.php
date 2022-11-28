@php
$tableHeadColorClass = 'tableHeadColor';
if (isset($type) || request()->has('type') || request()->route('type')) {
$tableHeadColorClass = '';
}
@endphp
@extends('misdroplets::layout')
@section('title', 'Factory Wise Sewing Input &amp; Output Production Report')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Factory Wise Sewing Input &amp; Output Production Report || {{ date("jS F, Y") }} </h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">

          <form action="{{ url('/factory-wise-input-output-report') }}" method="get" autocomplete="off">
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-2">
                  <label>Factory</label>
                  {!! Form::select('factory_id', $factories, request()->factory_id ?? null, ['id' => 'factory_id', 'class' =>
                  'form-control form-control-sm select2-input', 'placeholder' => 'Select a Factory']) !!}
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
                </div>
                <div class="col-sm-2">
                  <label>&nbsp;</label>
                  <button class="btn btn-sm form-control btn-info" type="submit">Search</button>
                </div>
              </div>
            </div>
          </form>

          <table class="reportTable">
            <thead>
              <tr>
                <th>SL</th>
                <th>Factory Name</th>
                <th>Today Input</th>
                <th>Today Output</th>
                <th>Today Sewing Rejection</th>
                <th>Total Input</th>
                <th>Total Output</th>
                <th>Total Sewing Rejection</th>
              </tr>
            </thead>
            <tbody>
              @if($report_data && count($report_data))
                @php
                  $g_today_sewing_input = 0;
                  $g_today_sewing_output = 0;
                  $g_today_sewing_rejection = 0;
                  $g_total_sewing_input = 0;
                  $g_total_sewing_output = 0;
                  $g_total_sewing_rejection = 0;
                @endphp
                @foreach ($report_data as $report)
                  @php
                    $g_today_sewing_input += $report['today_sewing_input'] ?? 0;
                    $g_today_sewing_output += $report['today_sewing_output'] ?? 0;
                    $g_today_sewing_rejection += $report['today_sewing_rejection'] ?? 0;
                    $g_total_sewing_input += $report['total_sewing_input'] ?? 0;
                    $g_total_sewing_output += $report['total_sewing_output'] ?? 0;
                    $g_total_sewing_rejection += $report['total_sewing_rejection'] ?? 0;
                  @endphp
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $report['factory']->factory_name }}</td>
                    <td>{{ $report['today_sewing_input'] ?? 0 }}</td>
                    <td>{{ $report['today_sewing_output'] ?? 0 }}</td>
                    <td>{{ $report['today_sewing_rejection'] ?? 0 }}</td>
                    <td>{{ $report['total_sewing_input'] ?? 0 }}</td>
                    <td>{{ $report['total_sewing_output'] ?? 0 }}</td>
                    <td>{{ $report['total_sewing_rejection'] ?? 0 }}</td>
                  </tr>
                @endforeach
                <tr>
                  <th colspan="2">Grand Total</th>
                  <th>{{ $g_today_sewing_input }}</th>
                  <th>{{ $g_today_sewing_output }}</th>
                  <th>{{ $g_today_sewing_rejection }}</th>
                  <th>{{ $g_total_sewing_input }}</th>
                  <th>{{ $g_total_sewing_output }}</th>
                  <th>{{ $g_total_sewing_rejection }}</th>
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