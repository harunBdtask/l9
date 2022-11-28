@extends('manual-production::layout')
@section('title', 'Date Wise Cutting Production Summary')
@section('content')
    <div class="padding">
        <div class="row manual-date-wise-cutting-report">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Date Wise Cutting Production Summary
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
                        {!! Form::open(['url' => '/manual-date-wise-cutting-report', 'method' => 'GET', 'id'=>'search_form']) !!}
                        <div class="form-group">
                            <div class="row m-b">
                                <div class="col-sm-3">
                                    <label>Report Date</label>
                                    {!! Form::date('date', $date, ['class' => 'form-control form-control-sm', 'required' => 'required']) !!}
                                </div>
                                <div class="col-sm-3">
                                    <label>Factory</label>
                                    {!! Form::select('factory_id', $factory_options ?? [], $factory_id ?? null, ['class' => 'form-control form-control-sm
                                    select2-input', 'placeholder' => 'Select Factory']) !!}
                                </div>
                                <div class="col-sm-3">
                                    <label>Subcontract Factory</label>
                                    {!! Form::select('subcontract_factory_id', $subcontract_factory_options ?? [], $subcontract_factory_id
                                    ?? null, ['class' => 'form-control form-control-sm']) !!}
                                </div>
                                <div class="col-sm-2">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-sm btn-info form-control form-control-sm">Search</button>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}

                        @include('manual-production::reports.cutting.includes.date_wise_cutting_report_include')

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function submitPdf() {
            $("#search_form").attr('action', '/manual-date-wise-cutting-report/pdf').submit();
        }

        function submitExcel() {
            $("#search_form").attr('action', '/manual-date-wise-cutting-report/excel').submit();
        }

        $(document).on('change', '.manual-date-wise-cutting-report select[name="factory_id"]', function (e) {
            $('.manual-date-wise-cutting-report select[name="subcontract_factory_id"]').val('').change();
        });

        $('.manual-date-wise-cutting-report select[name="subcontract_factory_id"]').select2({
            ajax: {
                url: '/manual-production-search/subcontract-factories',
                data: function (params) {
                    return {
                        search_query: params.term,
                        operation_type: 1,
                        factory_id: $('.manual-date-wise-cutting-report select[name="factory_id"]').val() || ''
                    };
                },
                processResults: function (response, params) {
                    return {
                        results: response.data,
                        pagination: {
                            more: false
                        }
                    };
                },
                cache: true,
                delay: 250
            },
            placeholder: 'Subcontract Factory',
            allowClear: true
        })
    </script>
@endsection
