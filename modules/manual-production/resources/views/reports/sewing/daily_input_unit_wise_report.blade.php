@extends('manual-production::layout')
@section('title', 'Daily Input Unit Wise Report')
@section('content')
    <div class="padding">
        <div class="row manual-style-report">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Daily Input Unit Wise
                            <span class="pull-right">
                                <a href="javascript:void(0)" onclick="submitPdf()">
                                    <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                                </a>
                            |
                                <a href="javascript:void(0)" onclick="submitExcel()">
                                  <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                                </a>
                            </span>
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        {!! Form::open(['url' => '/manual-daily-input-unit-wise-report', 'method' => 'GET', 'id'=>'search_form']) !!}
                        <div class="form-group">
                            <div class="row m-b">
                                <div class="col-sm-3">
                                    <label>Factory</label>
                                    {!! Form::select('factory_id', $factories ?? [], $factory_id ?? null, ['class' => 'form-control form-control-sm
                                    select2-input', 'placeholder' => 'Select Factory']) !!}
                                </div>
                                <div class="col-sm-3">
                                    <label>From</label>
                                    {!! Form::date('from_date', $from_date ?? \Carbon\Carbon::now()->firstOfMonth(), ['class' => 'form-control form-control-sm']) !!}
                                </div>
                                <div class="col-sm-3">
                                    <label>To</label>
                                    {!! Form::date('to_date', $to_date ?? \Carbon\Carbon::now(), ['class' => 'form-control form-control-sm']) !!}
                                </div>
                                <div class="col-sm-2">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-sm btn-info form-control form-control-sm">Search</button>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        @include('manual-production::reports.sewing.includes.daily_input_unit_wise_report_inlcude')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function submitPdf() {
            $("#search_form").attr('action', '/manual-daily-input-unit-wise-report/pdf').submit();
        }

        function submitExcel() {
            $("#search_form").attr('action', '/manual-daily-input-unit-wise-report/excel').submit();
        }
    </script>
@endsection
