@php
$tableHeadColorClass = 'tableHeadColor';
if (isset($type) || request()->has('type') || request()->route('type')) {
$tableHeadColorClass = '';
}
@endphp
@extends('misdroplets::layout')
@section('title', 'Factory Wise Cutting Production Report')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Factory Wise Cutting Production Report || {{ date("jS F, Y") }}
            {{-- <span class="pull-right"><a href="{{ url('/all-orders-cutting-report-download/pdf') }}" ><i
              style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | <a
              href="{{ url('/all-orders-cutting-report-download/xls') }}"><i style="color: #0F733B"
                class="fa fa-file-excel-o"></i></a></span> --}}</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">

          <form action="{{ url('/factory-wise-cutting-report') }}" method="get" autocomplete="off">
            @csrf
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-2">
                  <label>Factory</label>
                  {!! Form::select('factory_id', $factories, request()->factory_id ?? null, ['id' => 'factory_id',
                  'class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select a Factory']) !!}
                </div>
                <div class="col-sm-2">
                  <label>Form Date</label>
                  {!! Form::date('from_date', request()->from_date ?? null, ['id' => 'factory_id', 'class' =>
                  'form-control form-control-sm', 'placeholder' => 'Enter from date']) !!}
                </div>
                <div class="col-sm-2">
                  <label>To Date</label>
                  {!! Form::date('to_date', request()->to_date ?? null, ['id' => 'factory_id', 'class' =>
                  'form-control form-control-sm', 'placeholder' => 'Enter from date']) !!}
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
                <th>SL</th>
                <th>Factory Name</th>
                <th>Today's Cutting</th>
                <th>Today's Rejection</th>
                <th>Today's OK Cutting</th>
                <th>Total Cutting</th>
                <th>Total Rejection</th>
                <th>Total OK Cutting</th>
                <th>Total Target</th>
                <th>Achievement(&#37;)</th>
              </tr>
            </thead>
            <tbody>
              @if($report_data && $report_data->count())
                @php
                  $total_todays_cutting_quantity = 0;
                  $total_todays_cutting_rejection = 0;
                  $total_todays_ok_cutting_quantity = 0;
                  $g_total_cutting_quantity = 0;
                  $g_total_cutting_rejection = 0;
                  $g_total_ok_cutting_quantity = 0;
                  $total_target = 0;
                @endphp
              @foreach($report_data as $report)
                @php
                  $total_todays_cutting_quantity += $report['todays_cutting'];
                  $total_todays_cutting_rejection += $report['todays_cutting_rejection'];
                  $total_todays_ok_cutting_quantity += $report['todays_ok_cutting'];
                  $g_total_cutting_quantity += $report['total_cutting'];
                  $g_total_cutting_rejection += $report['total_cutting_rejection'];
                  $g_total_ok_cutting_quantity += $report['total_ok_cutting'];
                  $total_target += $report['cutting_target'];
                @endphp
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $report['factory']->factory_name }}</td>
                  <td>{{ $report['todays_cutting'] }}</td>
                  <td>{{ $report['todays_cutting_rejection'] }}</td>
                  <td>{{ $report['todays_ok_cutting'] }}</td>
                  <td>{{ $report['total_cutting'] }}</td>
                  <td>{{ $report['total_cutting_rejection'] }}</td>
                  <td>{{ $report['total_ok_cutting'] }}</td>
                  <td>{{ $report['cutting_target'] }}</td>
                  <td>{{ round($report['achievement']) }}</td>
                </tr>
              @endforeach
              @php
                $total_achievement = $report['total_ok_cutting'] ? round(($report['cutting_target'] * 100) / $report['total_ok_cutting']) : 0;
              @endphp
              <tr style="font-weight: bold">
                <td colspan="2">{{ 'TOTAL' }}</td>
                <td>{{ $total_todays_cutting_quantity }}</td>
                <td>{{ $total_todays_cutting_rejection }}</td>
                <td>{{ $total_todays_ok_cutting_quantity }}</td>
                <td>{{ $g_total_cutting_quantity }}</td>
                <td>{{ $g_total_cutting_rejection }}</td>
                <td>{{ $g_total_ok_cutting_quantity }}</td>
                <td>{{ $total_target }}</td>
                <td>{{ $total_achievement }}</td>
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
