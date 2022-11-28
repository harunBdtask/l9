@extends('manual-production::layout')
@section('title', 'Yearly Rejection Report')
@section('content')
    <div class="padding">
        <div class="row manual-style-report">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Yearly Rejection Report
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
                        {!! Form::open(['url' => '/yearly-rejection-summary-report', 'method' => 'GET', 'id'=>'search_form']) !!}
                        <div class="form-group">
                            <div class="row m-b">
                                <div class="col-sm-3">
                                    <label>Year</label>
                                    {!! Form::selectRange('year', 2019, date('Y'), $year ?? null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Please select one', 'onchange' => 'this.form.submit();']) !!}
                                </div>
                                <div class="col-sm-2">
                                    <label>&nbsp;</label>
                                    {{--                                    <button type="submit" class="btn btn-sm btn-primary form-control form-control-sm">Search</button>--}}
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        @include('manual-production::reports.rejection.includes.yearly_rejection_report_include')
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
            $("#search_form").attr('action', '/yearly-rejection-summary-report/pdf').submit();
        }

        function submitExcel() {
            $("#search_form").attr('action', '/yearly-rejection-summary-report/excel').submit();
        }

    </script>
@endsection
