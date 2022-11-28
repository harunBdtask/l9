@extends('cuttingdroplets::layout')
@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@section('title', 'Monthly Fabric Consumption Report')
@section('content')
  <div class="padding">
    <div class="box">
      <div class="box-header">
        @php
          $currentPage = $reports ? $reports->currentPage() : 1;
        @endphp
        <h2>Monthly Fabric Consumption Report
          <span class="pull-right">
                        <a href="{{url('/monthly-fabric-consumption-report-download?type=pdf&year='.$year.'&month='.$month.'&current_page='.$currentPage)}}">
                            <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                        </a>
                        |
                        <a href="{{url('/monthly-fabric-consumption-report-download?type=xls&year='.$year.'&month='.$month.'&current_page='.$currentPage)}}">
                            <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                        </a>
                    </span>
        </h2>
      </div>
      <div class="box-divider m-a-0"></div>
      <div class="box-body">
        <div class="row form-group">
        {!! Form::open(['url' => 'monthly-fabric-consumption-report', 'method' => 'GET']) !!}
          <div class="col-sm-3">
            <label>Month</label>
            {!! Form::selectMonth('month', $month ?? (int)date('m'), ['class' => 'form-control form-control-sm', 'onchange' => 'this.form.submit();']) !!}
          </div>
          <div class="col-sm-3">
            <label>Year</label>
            {!! Form::selectYear('year', date('Y'), 1970, $year ?? (int)date('Y'), ['class' => 'form-control form-control-sm', 'onchange' => 'this.form.submit();']) !!}
          </div>
          {!! Form::close() !!}
        </div>
        <div id="parentTableFixed" class="table-responsive">
          <table class='reportTable' id="fixTable">
            <thead>
            <tr>
              <th>Buyer</th>
              <th>Style</th>
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
              @if($reports->total() > 15)
                <tr>
                  <td colspan="7"
                      align="center">{{ $reports->appends(request()->except('page'))->links() }}</td>
                </tr>
              @endif
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
