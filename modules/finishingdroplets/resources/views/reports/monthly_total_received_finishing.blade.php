@extends('finishingdroplets::layout')
@section('title', 'Monthly Total Received Finishing')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Monthly Total Received Finishing || {{ date("F, Y") }}
                            <span class="pull-right">
              <i data-url="/monthly_total_received_finishing/pdf" style="color: #DC0A0B; cursor: pointer"
                 class="text-danger fa fa-file-pdf-o downloadBtn"
              ></i>
            |
            <i data-url="/monthly_total_received_finishing/xls"
               style="cursor: pointer"
               class="text-success downloadBtn fa fa-file-excel-o"
            ></i>
          </span>
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">

                        <form action="{{ url('/monthly-total-received-finishing-report') }}" method="post"
                              id="searchReportForm">
                            <div class="form-group">
                                <label><b>Month</b></label>
                                <div class="row m-b">
                                    <div class="col-sm-3">
                                        <input type="month" value="{{ date('Y-m') }}" name="month" id="month"
                                               class="form-control" style="line-height: 1rem !important;">
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="submit"
                                                class="btn btn-sm btn-info form-control form-control-sm">
                                            Search
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
            input[type=month].form-control form-control-sm {
                line-height: 1;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).on('submit', '#searchReportForm', function (e) {
            e.preventDefault();
            let form = $(this).serializeArray();
            $.ajax({
                url: "/monthly-total-received-finishing-report",
                type: "post",
                data: form,
                dataType: "html",
                success(response) {
                    $(".report-div").html(response);
                }
            })
        })

        $(document).on('click', '.downloadBtn', function () {
            let url = $(this).data('url');
            let month = $("#month").val();
            url += ("?month=" + month)
            location.assign(url);
        });

    </script>
@endsection
