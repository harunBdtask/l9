@extends('general-store::layout')
@section('content')
    <style>
        th {
            padding: 0 !important;
        }

        th,
        tr,
        td {
            border: 1px solid #696969 !important;
            text-align: center;
            font-size: 12px;
        }

        .select-field {
            margin-top: -20px;
        }

        .tr-style {
            background: darkseagreen;
            height: 35px;
        }

        .tr-total {
            background: burlywood;
        }

        .month {
            width: 50%;
        }

    </style>
    <div class="padding" id="content">
        <div class="box">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Trims Booking Report
                                    <span class="pull-right">
                                        <a href="{{ url('/trims-booking-stock-report-download?type=pdf&' . (request()->getQueryString() ?? null)) }}"
                                           title="Download Pdf">
                                            <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> &nbsp;|
                                        <a
                                            href="{{ url('/trims-booking-stock-report-download?type=excel&' . (request()->getQueryString() ?? 'start_date=' . ($start_date ?? null) . '&end_date=' . ($end_date ?? null))) }}"><i
                                                style="color: #0F733B" class="fa fa-file-excel-o"></i></a>
                                    </span>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="box-body b-t">
                        {!! Form::open(['url' => '/trims-booking/report/index', 'method' => 'get']) !!}

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="month">Month</label><br>
                                    {{ Form::select('month', [], null, ['class' => 'form-control', 'placeholder' => request('month') ?? $currentMonth]) }}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="year">Year</label>

                                    {{ Form::text('year', $year ?? null, ['class' => 'form-control', 'id' => 'year', 'placeholder' => 'Year']) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="start_date">Form Date</label>
                                    {{ Form::date('from_date', request('from_date') ?? null, ['class' => 'form-control', 'id' => 'from_date', 'placeholder' => 'Form Date']) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="end_date">To Date</label>
                                    {!! Form::date('to_date', request('to_date') ?? null, ['class' => 'form-control', 'id' => 'to_date', 'placeholder' => 'To Date']) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">&nbsp;</label>
                                    {!! Form::submit('Submit', ['class' => 'btn btn-sm btn-primary form-control', 'id' => 'submit', 'placeholder' => 'Search']) !!}
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div class="table-responsive">
                            <table class="reportTable">
                                @includeIf('inventory::report.includes.trims_accessories.trims_booking_report_table')
                            </table>
                        </div>
                        @if(!empty($trimsBookings))
                            @if ( $trimsBookings->total() > 15)
                                <div class="text-center print-delete">
                                    {{ $trimsBookings->appends(request()->except('page'))->links() }}</div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-head')

    <script>
        $(document).ready(function () {
            setSelect2();
        });

        function setSelect2() {
            $('select').select2({
                allowClear: true
            });
        }


        $(document).ready(function () {

            var month = {
                "January": "January",
                "February": "February",
                "March": "March",
                "April": "April",
                "May": 'May',
                "June": 'June',
                "July": 'July',
                "August": 'August',
                "September": 'September',
                "October": 'October',
                "November": 'November',
                "December": 'December',
            };
            $.each(month, function (key, value) {
                $('[name="month"]').append('<Option value="' + key + '">' + value + '</Option>');
            });

        });


    </script>

@endpush
