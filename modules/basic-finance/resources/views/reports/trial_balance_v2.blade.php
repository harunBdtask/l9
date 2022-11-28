@extends('basic-finance::layout')

@section('title', 'Trial-Balance-v2')

@section('styles')
    <style type="text/css">
        .addon-btn-primary {
            padding: 0;
            margin: 0px;
            background: #0275d8;
        }

        .addon-btn-primary:hover {
            background: #025aa5;
        }

        select.c-select {
            min-height: 2.375rem;
        }

        input[type=date].form-control, input[type=time].form-control, input[type=datetime-local].form-control, input[type=month].form-control {
            line-height: 1rem;
        }

        .select2-selection {
            min-height: 2.375rem;
        }

        .select2-selection__rendered, .select2-selection__arrow {
            margin: 4px;
        }

        .invalid, .invalid + .select2 .select2-selection {
            border-color: red !important;
        }

        td {
            padding-right: 8px;
        }

        .reportTable th.text-left, .reportTable td.text-left {
            text-align: left;
            padding-left: 5px;
        }

        .reportTable th.text-right, .reportTable td.text-right {
            text-align: right;
            padding-right: 5px;
        }

        .reportTable th.text-center, .reportTable td.text-center {
            text-align: center;
        }

        .custom-padding {
            padding: 0px 40px 0 40px;
        }
    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>TRIAL BALANCE</h2>
            </div>
            <div class="box-body b-t">
                <div class="row">
                    {!! Form::open(['url'=>'basic-finance/trial-balance-v2', 'method'=>'get']) !!}
                    @php
                        $groups=collect($groups)->prepend('Select Group','');
                        $companies=collect($companies)->prepend('Select Company','');
                        $projects=collect($projects)->prepend('All Project',0);
                        $units=collect($units)->prepend('All Unit',0);
                    @endphp
                    <div class="form-group row custom-padding">
                        <div class="col-sm-4">
                            <label class="col-sm-4 form-control-label"><b>From Date</b></label>
                            <div class="col-sm-8">
                                {!! Form::date("start_date", request('start_date') ?? now(),['class'=>"form-control form-control-sm", 'onchange' => 'this.form.submit();']) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label class="col-sm-4 form-control-label"><b>To Date</b></label>
                            <div class="col-sm-8">
                                {!! Form::date("end_date", request('end_date') ?? now(),['class'=>"form-control form-control-sm", 'onchange' => 'this.form.submit();']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="col-sm-4 form-control-label"><b>Group Name</b></label>
                            <div class="col-sm-8">
                                {!! Form::select("group_id", $groups ?? [], request('group_id') ?? null,["class"=>"form-control c-select select2-input", "id"=>"group_id", 'onchange' => 'this.form.submit();']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row custom-padding">
                        <div class="col-md-4">
                            <label class="col-sm-4 form-control-label"><b>Company Name</b></label>
                            <div class="col-sm-8">
                                {!! Form::select("company_id", $companies ?? [],request('company_id') ?? null,["class"=>"form-control c-select select2-input", "id"=>"company_id", 'onchange' => 'this.form.submit();']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="col-sm-4 form-control-label"><b>Project Name</b></label>
                            <div class="col-sm-8">
                                {!! Form::select("project_id", $projects,request('project_id') ?? null,["class"=>"form-control c-select select2-input", "id"=>"project_id", 'onchange' => 'this.form.submit();']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="col-sm-4 form-control-label"><b>Unit Name</b></label>
                            <div class="col-sm-8">
                                {!! Form::select("unit_id", $units,request('unit_id') ?? null,["class"=>"form-control c-select select2-input", "id"=>"unit_id", 'onchange' => 'this.form.submit();']) !!}
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

                <div class="row">
                    <div class="col-md-12">
                        {!! $report_data !!}
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('scripts')
@endsection
