@extends('cuttingdroplets::layout')
@section('title', 'Date Wise Cutting Production Summary')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Date Wise Cutting Report
                            <span class="pull-right">
              <a href="{{ url('/v2/date-wise-report-download/pdf/'.$date.'?floor=' . $floor ?? '' ) }}">
                <em style="color: #DC0A0B" class="fa fa-file-pdf-o"></em>
              </a>
              |
              <a href="{{ url('/v2/date-wise-report-download/xls/'.$date. '?floor=' . $floor ?? '') }}">
                <em style="color: #0F733B" class="fa fa-file-excel-o"></em>
              </a>
            </span>
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        {!! Form::open(['url' => url('/v2/date-wise-cutting-report'), 'method' => 'GET']) !!}
                        <div class="form-group">
                            <div class="row m-b">
                                <div class="col-sm-3">
                                    <label>Report Date</label>
                                    {!! Form::date('date', $date, ['class' => 'form-control form-control-sm', 'required']) !!}
                                </div>
                                <div class="col-sm-3">
                                    <label>Floor</label>
                                    {!! Form::select('floor', $floors, $floor, ['class' => 'select2-input', 'required']) !!}
                                </div>
                                <div class="col-sm-1">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-sm btn-info form-control form-control-sm">
                                        Search
                                    </button>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div id="parentTableFixed" class="table-responsive">
                            <table class="reportTable" id="fixTable">
                                @include('cuttingdroplets::reports.tables.v2.date_wise_cutting_report_table')
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection
        @section('scripts')
            <script src="{{ asset('/js/tableHeadFixer.js') }}"></script>
@endsection
