@extends('cuttingdroplets::layout')
@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@section('title', 'Daily Fabric Consumption Report')
@section('content')
  <div class="padding">
    <div class="box">
      <div class="box-header">
        <h2>Daily Fabric Consumption Report
          <span class="pull-right">
              <a href="{{url('/daily-fabric-consumption-report-download?type=pdf&cutting_date='.$cutting_date)}}">
                  <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
              </a>
              |
              <a href="{{url('/daily-fabric-consumption-report-download?type=xls&cutting_date='.$cutting_date)}}">
                  <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
              </a>
          </span>
        </h2>
      </div>
      <div class="box-divider m-a-0"></div>
      <div class="box-body">
        {!! Form::open(['url' => 'daily-fabric-consumption-report', 'method' => 'GET']) !!}
        <div class="row form-group">
          <div class="col-sm-3">
            <label>Cutting Date</label>
            {!! Form::date('cutting_date', $cutting_date ?? date('Y-m-d'), ['class' => 'form-control form-control-sm', 'style' => 'height: 28px;', 'onchange' => 'this.form.submit();']) !!}
          </div>
        </div>
        {!! Form::close() !!}
        <div id="parentTableFixed" class="table-responsive">
          <table class='reportTable' id="fixTable">
            <thead>
            <tr>
              <th>Buyer</th>
              <th>Style Name</th>
              <th>Color</th>
              <th>SID</th>
              <th>Cutting No</th>
              <th>Fabric Save/Loss</th>
              <th>Cutting Date</th>
            </tr>
            </thead>
            <tbody>
            @if($reports && $reports->count())
              @foreach($reports as $reportBundle)
                @php
                  $report = $reportBundle->details;
                  $colors = $report->allColors ?? '';
                  $cuttingNo = $report->cutting_no;

                  if ($report->colors) {
                      $cuttingNosWithColor = explode('; ', $cuttingNo);

                      $cuttingNo = '';
                      foreach ($cuttingNosWithColor as $cuttingNoWithColor) {
                          $cutting = explode(': ', $cuttingNoWithColor);
                          $cuttingNo .= \SkylarkSoft\GoRMG\SystemSettings\Models\Color::findOrFail($cutting[0])->name . ': ' . $cutting[1] . '; ';
                      }
                      $cuttingNo = rtrim($cuttingNo, '; ');
                  }

                @endphp
                <tr>
                  <td>{{ $report->buyer->name }}</td>
                  <td>{{ $report->order->style_name }}</td>
                  <td>{{ $colors }}</td>
                  <td>{{ $report->sid }}</td>
                  <td>{{ $cuttingNo }}</td>
                  <td>{{ number_format($report->fabric_save, 2) }} KGs</td>
                  <td>{{ $reportBundle->cutting_date ? date('d/m/Y', strtotime($reportBundle->cutting_date)) : '' }}</td>
                </tr>
              @endforeach
              <tr>
                <th colspan="5">Total</th>
                <th>{{ number_format($reports->sum('details.fabric_save'), 2) }} KGs</th>
                <th>&nbsp;</th>
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
@endsection
@section('scripts')
  <script src="{{ asset('/js/tableHeadFixer.js') }}"></script>
  <script>
    $(document).ready(function () {
      $("#fixTable").tableHeadFixer();
    });
  </script>
@endsection
