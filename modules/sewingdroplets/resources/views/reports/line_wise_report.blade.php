@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('sewingdroplets::layout')
@section('title', 'Line Wise Input,Output & In-Line WIP Summary')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Line Wise Input,Output &amp; In-Line WIP Summary || {{ date("jS F, Y") }} <span class="pull-right"><a
                    href="{{$line_id ? url('line-wise-sewing-output-download/pdf/'.$line_id) : '#'}}"><i
                      style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | <a
                    href="{{$line_id ? url('line-wise-sewing-output-download/xls/'.$line_id) : '#'}}"><i
                      style="color: #0F733B" class="fa fa-file-excel-o"></i></a></span></h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <form action="{{ url('/line-wise-sewing-input-output') }}" method="post">
              @csrf
              <div class="form-group">
                <div class="row m-b">
                  <div class="col-sm-2">
                    <label>Floor</label>
                    {!! Form::select('floor_id', $floors, $floor_id ?? null, ['class' => 'select2-input line-wise-report-floor-dropdown form-control form-control-sm c-select', 'required', 'id' => 'floor', 'placeholder' => 'Select a floor']) !!}

                    @if($errors->has('floor_id'))
                      <span class="text-danger">{{ $errors->first('floor_id') }}</span>
                    @endif
                  </div>

                  <div class="col-sm-2">
                    <label>Line</label>
                    {!! Form::select('line_id', $lines ?? [], $line_id ?? null, ['class' => 'line-wise-report-lines-dropdown form-control form-control-sm', 'required', 'id' => 'line_id', 'placeholder' => 'Select a Line']) !!}

                    @if($errors->has('line_id'))
                      <span class="text-danger">{{ $errors->first('line_id') }}</span>
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
                  <th>Floor</th>
                  <th>Line</th>
                  <th>Buyer</th>
                  <th>Style/Order</th>
                  <th>Order/Style</th>
                  <th>PO</th>
                  <th>Today's Input</th>
                  <th>Total Input</th>
                  <th>Today's Output</th>
                  <th>Total Output</th>
                  <th>Rejection</th>
                  <th>Rejection(%)</th>
                  <th>In-Line WIP</th>
                  <th>WIP (%)</th>
                </tr>
                </thead>
                <tbody>
                @if(count($lineReport))
                  @foreach($lineReport->getCollection() as $report)
                    <tr>
                      <td>{{ $report->line->floor->floor_no ?? '' }}</td>
                      <td>{{ $report->line->line_no ?? '' }}</td>
                      <td>{{ $report->buyer->name ?? '' }}</td>
                      <td>{{ $report->purchaseOrder->order->booking_no ?? '' }}</td>
                      <td>{{ $report->purchaseOrder->order->order_style_no ?? '' }}</td>
                      <td>{{ $report->purchaseOrder->po_no ?? '' }}</td>
                      <td>{{ $report->todays_input ?? 0 }}</td>
                      <td>{{ $report->total_input ?? 0 }}</td>
                      <td>{{ $report->todays_output ?? 0 }}</td>
                      <td>{{ $report->total_output ?? 0 }}</td>
                      <td>{{ $report->rejection ?? 0 }}</td>
                      <td>{{ ($report->rejection > 0 && $report->total_input > 0)  ? number_format((($report->rejection * 100) / $report->total_input) ?? 0, 2) : 0 }}</td>
                      <td>{{ $report->total_input - $report->total_output }}</td>
                      <td>{{ (($report->total_input - $report->total_output) > 0 && $report->total_input > 0) ? number_format((($report->total_input - $report->total_output) / $report->total_input) * 100, 2) : 0 }}
                        %
                      </td>
                    </tr>
                  @endforeach
                @elseif(count($lines))
                  <tr>
                    <td colspan="14" class="text-danger text-center">Data not found</td>
                  </tr>
                @endif
                </tbody>

                @if($lineReport)
                  <tfoot>
                  @if($lineReport->total() > PAGINATION)
                    <tr>
                      <td colspan="14"
                          align="center">{{ $lineReport->appends(request()->except('page'))->links() }}</td>
                    </tr>
                  @endif
                  </tfoot>
                @endif
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
