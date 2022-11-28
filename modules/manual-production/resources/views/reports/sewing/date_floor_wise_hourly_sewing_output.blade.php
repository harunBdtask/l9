@extends('manual-production::layout')
@section('title', 'Hourly Production Report')
@section('content')
    <div class="padding">
        <div class="row manual-challan-report">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Hourly Production Report || {{ date("jS F, Y") }}
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
                        {!! Form::open(['url' => '/manual-date-floor-wise-hourly-sewing-output', 'method' => 'GET', 'id'=>'search_form']) !!}
                        <div class="form-group">
                            <div class="row m-b">
                                <div class="col-sm-3">
                                    <label>Unit</label>
                                    {!! Form::select('floor_id', $floors ?? [], $floor_id ?? null, ['class' => 'form-control form-control-sm
                                    select2-input', 'placeholder' => 'Select Unit']) !!}
                                </div>
                                <div class="col-sm-3">
                                    <label>Date</label>
                                    {!! Form::date('date', $date ?? \Carbon\Carbon::now(), ['class' => 'form-control form-control-sm']) !!}
                                </div>
                                <div class="col-sm-2">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-sm btn-info form-control form-control-sm">Search</button>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div class="table-responsive">
                            @include('manual-production::reports.sewing.includes.date_floor_wise_hourly_sewing_output_inlcude')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function submitPdf() {
            $("#search_form").attr('action', '/manual-date-floor-wise-hourly-sewing-output/pdf').submit();
        }

        function submitExcel() {
            $("#search_form").attr('action', '/manual-date-floor-wise-hourly-sewing-output/excel').submit();
        }
    </script>
@endsection
