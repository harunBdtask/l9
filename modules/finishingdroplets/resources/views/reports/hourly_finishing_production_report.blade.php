@extends('finishingdroplets::layout')
@section('title', 'Hourly Finishing Production Report')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Hourly Finishing Production Report || {{ date("jS F, Y") }}
                            <span class="pull-right">
                                <a class="btn pdf-excel-btn"
                                   href="{{url('hourly-finishing-production-report/get-report/pdf')}}">
                                    <em style="color: #DC0A0B"
                                        class="fa fa-file-pdf-o"></em>
                                </a> |
                                <a class="btn pdf-excel-btn"
                                   href="{{url('hourly-finishing-production-report/get-report/excel')}}">
                                    <em style="color: #0F733B"
                                        class="fa fa-file-excel-o"></em>
                                </a>
                            </span>
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        {!! Form::open([
                            'id'=>'searchForm',
                            'url'=>'/hourly-finishing-production-report/get-report',
                            'method'=>'post'
                            ]) !!}

                        <div class="form-group">
                            <div class="row m-b">
                                <div class="col-sm-2">
                                    <label>Date</label>
                                    {!! Form::date('date', date('Y-m-d'),['class'=>'form-control form-control-sm', 'id' => 'date']) !!}
                                </div>
                                <div class="col-sm-1">
                                    <button class="btn btn-info btn-xs" type="button" id="searchBtn"
                                            onclick="getReport()"
                                            style="margin-top: 30px">
                                        <em class="fa fa-search"></em>
                                    </button>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div id="parentTableFixed" class="table-responsive" style="max-height: 100%!important;">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            getReport();
            $(".pdf-excel-btn").click(function (event) {
                event.preventDefault();
                let date = $("#date").val();
                let url = $(this).attr("href");

                const urlParams = new URLSearchParams({date, floor_no: getURLParams().floor_no});

                url += `?${urlParams}`;

                window.location.assign(url);
            });
        });

        function getURLParams() {
            const urlParams = new URL(window.location.href);
            const floor_no = urlParams.searchParams.get('floor_no');
            return {floor_no};
        }

        function getReport() {
            let formData = $("#searchForm").serializeArray();

            const urlParams = new URLSearchParams({page: "view", floor_no: getURLParams().floor_no});

            $.ajax({
                url: `/hourly-finishing-production-report/get-report?${urlParams}`,
                type: "get",
                data: formData,
                dataType: "html",
                success(response) {
                    $("#parentTableFixed").html(response);
                }
            });

        }
    </script>
@endsection
