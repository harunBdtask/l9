@extends('finishingdroplets::layout')
@section('title', 'Finishing Production Report')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Finishing Production Report || {{ date("jS F, Y") }}
                            <span class="pull-right">
                  <i data-url="/finishing-production-report-v2/pdf" style="color: #DC0A0B; cursor: pointer"
                     class="text-danger fa fa-file-pdf-o downloadBtn"
                  ></i>
                |
                <i data-url="/finishing-production-report-v2/excel"
                   style="cursor: pointer"
                   class="text-success downloadBtn fa fa-file-excel-o"
                ></i>
              </span>
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <form action="{{ url('/finishing-production-report-v2') }}" method="post" id="searchReportForm">
                            <div class="form-group">
                                <div class="row m-b">
                                    <div class="col-sm-2">
                                        <label>From</label>
                                        <input type="date" class="form-control form-control-sm"
                                               value="{{ date('Y-m-d') }}" name="from_date" id="from_date">
                                    </div>
                                    <div class="col-sm-2">
                                        <label>To</label>
                                        <input type="date" class="form-control form-control-sm"
                                               value="{{ date('Y-m-d') }}" name="to_date" id="to_date">
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Buyer</label>
                                        <select name="buyer_id" id="buyer_id"
                                                class="form-control form-control-sm select2-input">
                                            <option selected disabled hidden>-- Select Buyer --</option>
                                            @foreach($buyers as $buyer)
                                                <option value="{{ $buyer->id }}">{{ $buyer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Finish Floor</label>
                                        <select name="floor_id" id="floor_id"
                                                class="form-control form-control-sm select2-input">
                                            <option selected disabled hidden>-- Select Finish Floor --</option>
                                            @foreach($finishFloors as $floor)
                                                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-1">
                                        <label>&nbsp;</label>
                                        <button type="submit"
                                                class="btn btn-sm btn-info form-control form-control-sm">
                                            <i class="fa fa-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div id="parentTableFixed" class="table-responsive"
                             style="max-height: fit-content!important;">
                            <table class="reportTable">
                                <thead>
                                <tr>
                                    <td style="background-color: #a1c9ed;"><strong>Buyer</strong></td>
                                    <td style="background-color: #a1c9ed;"><strong>Date</strong></td>
                                    <td style="background-color: #a1c9ed;"><strong>Style Name</strong></td>
                                    <td style="background-color: #a1c9ed;"><strong>PO No</strong></td>
                                    <td style="background-color: #a1c9ed;"><strong>Country</strong></td>
                                    <td style="background-color: #a1c9ed;"><strong>Color</strong></td>
                                    <td style="background-color: #a1c9ed;"><strong>Order Qty</strong></td>
                                    <td style="background-color: #a1c9ed;"><strong>Order Qty + 1%</strong></td>
                                    <td style="background-color: #a1c9ed;"><strong>Daily Rcvd</strong></td>
                                    <td style="background-color: #a1c9ed;"><strong>Pre Rcvd</strong></td>
                                    <td style="background-color: #a1c9ed;"><strong>Total Rcvd</strong></td>
                                    <td style="background-color: #a1c9ed;"><strong>Daily Iron</strong></td>
                                    <td style="background-color: #a1c9ed;"><strong>Pre Iron</strong></td>
                                    <td style="background-color: #a1c9ed;"><strong>Total Iron</strong></td>
                                    <td style="background-color: #a1c9ed;"><strong>Daily Finish</strong></td>
                                    <td style="background-color: #a1c9ed;"><strong>Pre Finish</strong></td>
                                    <td style="background-color: #a1c9ed;"><strong>Total Finish</strong></td>
                                    <td style="background-color: #a1c9ed;"><strong>Balance Qty</strong></td>
                                    <td style="background-color: #a1c9ed;"><strong>Finish Floor</strong></td>
                                    <td style="background-color: #a1c9ed;"><strong>Sewing Floor</strong></td>
                                    <td style="background-color: #a1c9ed;"><strong>Remarks</strong></td>
                                </tr>
                                </thead>
                                <tbody class="report-div">
                                </tbody>
                            </table>
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
        $(document).ready(function () {
            let setLimit = 50;
            let setOffset = 0;

            function fetchReport(limit = 50, offset = 0) {
                let fromDate = $("#from_date").val();
                let toDate = $("#to_date").val();
                if (!fromDate || !toDate) {
                    alert("Please select from date and to date");
                } else {
                    let form = $("#searchReportForm").serializeArray();
                    $.ajax({
                        url: "/finishing-production-report-v2?limit=" + limit + "&offset=" + offset,
                        type: "post",
                        data: form,
                        dataType: "html",
                        success(response) {
                            $(".report-div").append(response);
                        }
                    });
                }
            }

            $(window).scroll(function () {
                if ($(window).scrollTop() == $(document).height() - $(window).height()) {
                    fetchReport(setLimit = 50, setOffset += 50);
                }
            });


            $(document).on('submit', '#searchReportForm', function (e) {
                e.preventDefault();
                $(".report-div").empty();
                fetchReport();
            })

            $(document).on('click', '.downloadBtn', function () {
                let url = $(this).data('url');
                let fromDate = $("#from_date").val();
                let toDate = $("#to_date").val();
                let buyer_id = $("#buyer_id").val();
                let floor_id = $("#floor_id").val();
                if (!fromDate || !toDate) {
                    alert("Please select from date and to date");
                } else {
                    url += "?from_date=" + fromDate + "&to_date=" + toDate;
                    if (buyer_id) {
                        url += "&buyer_id=" + buyer_id;
                    }
                    if (floor_id) {
                        url += "&floor_id=" + floor_id;
                    }
                    location.assign(url);
                }
            });
        });
    </script>
@endsection
