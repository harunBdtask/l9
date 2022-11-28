@extends('manual-production::layout')
@section('title', 'Date Wise Cutting Production Summary')
@section('content')
    <div class="padding">
        <div class="row manual-date-wise-cutting-report">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Daily Sewing Production Report</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="row heading">
                            <form action="" method="GET">
                                <div class="col-sm-3">
                                    <label for="factory_id">Factory</label>
                                    {!! Form::select('factory_id', $factories ?? [], $factory_id ?? null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'factory_id']) !!}
                                </div>
                                <div class="col-sm-3">
                                    <label for="floor_id">Floor</label>
                                    {!! Form::select('floor_id', $floors ?? [], $floor_id ?? null, ['class' => 'form-control form-control-sm', 'id' => 'floor_id', 'required' => true]) !!}
                                </div>
                                <div class="col-sm-3">
                                    <label for="date">Date</label>
                                    {!! Form::date('date', $date ?? null, ['class' => 'form-control form-control-sm form-control form-control-sm-lg', 'id' => 'date', 'required' => true]) !!}
                                </div>
                                <div class="col-sm-3">
                                    <button style="margin-top: 27px" type="submit" class="btn btn-sm btn-info">Search
                                    </button>
                                    <div class="pull-right" style="margin-top: 40px">
                                        <button id="pdf" class="btn btn-xs btn-info"><i class="fa fa-file-pdf-o"></i>
                                        </button>
                                        <button id="excel" class="btn btn-xs btn-primary"><i
                                                class="fa fa-file-excel-o"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @includeIf('manual-production::reports.dailySewingProductionReport.data')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('.heading select[name="floor_id"]').select2({
            ajax: {
                url: '/manual-product/common-api/get-floors',
                data: function (params) {
                    return {
                        search_query: params.term,
                        factory_id: $("#factory_id").val() || ''
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
            placeholder: 'Units',
            allowClear: true
        });

        $(document).on('click', '#pdf', function () {
            event.preventDefault();
            const urlSearchParams = new URLSearchParams(window.location.search);
            let factoryId = Object.fromEntries(urlSearchParams.entries()).factory_id;
            let floorId = Object.fromEntries(urlSearchParams.entries()).floor_id;
            let date = Object.fromEntries(urlSearchParams.entries()).date;
            let url = `?factory_id=${factoryId}&floor_id=${floorId}&date=${date}`;
            window.location = '{{ url('daily-sewing-production-report-pdf') }}' + url;
        });

        $(document).on('click', '#excel', function () {
            event.preventDefault();
            const urlSearchParams = new URLSearchParams(window.location.search);
            let factoryId = Object.fromEntries(urlSearchParams.entries()).factory_id;
            let floorId = Object.fromEntries(urlSearchParams.entries()).floor_id;
            let date = Object.fromEntries(urlSearchParams.entries()).date;
            let url = `?factory_id=${factoryId}&floor_id=${floorId}&date=${date}`;
            window.location = '/daily-sewing-production-report-excel' + url;
        });
    </script>
@endsection
