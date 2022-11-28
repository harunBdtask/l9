@extends('cuttingdroplets::layout')
@section('title', 'Yearly Summary Report')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Yearly Summary Report || {{ date("jS F, Y") }}
                            <span class="pull-right">
                  <i data-url="/yearly-summary-report/pdf" style="color: #DC0A0B; cursor: pointer"
                     class="text-danger fa fa-file-pdf-o downloadBtn"
                  ></i>
                |
                <i data-url="/yearly-summary-report/xls"
                   style="cursor: pointer"
                   class="text-success downloadBtn fa fa-file-excel-o"
                ></i>
              </span>
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <form action="{{ url('/yearly-summary-report') }}" method="post" id="searchReportForm">
                            <div class="form-group">
                                <div class="row m-b">
                                    <div class="col-sm-3">
                                        {!! Form::selectRange('year',2019, date('Y'), date('Y'),['class'=>"form-control form-control-sm", 'id'=>'year']) !!}
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="submit"
                                                class="btn btn-sm white form-control form-control-sm">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div id="parentTableFixed" class="table-responsive report-div"
                             style="max-height: fit-content!important;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style type="text/css">
        @media screen and (-webkit-min-device-pixel-ratio: 0) {
            input[type=date].form-control form-control-sm {
                line-height: 1;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        function fetchReport() {
            let form = $("#searchReportForm").serializeArray();
            $.ajax({
                url: "/yearly-summary-report",
                type: "post",
                data: form,
                dataType: "html",
                success(response) {
                    $(".report-div").html(response);
                }
            })
        }

        $(document).ready(function () {
            setTimeout(() => fetchReport(), 1200);
        });

        $(document).on('submit', '#searchReportForm', function (e) {
            e.preventDefault();
            fetchReport();
        })

        $(document).on('click', '.downloadBtn', function () {
            let url = $(this).data('url');
            let year = $("#year").val();
            url += ("?year=" + year)
            location.assign(url);
        });
    </script>
@endsection
