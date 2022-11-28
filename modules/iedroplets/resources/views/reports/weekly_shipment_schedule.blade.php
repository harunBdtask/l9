@extends('iedroplets::layout')
@section('title', 'Weekly Shipment Schedule')
@section('content')
    <style>
        .custom-hover-color:hover {
            background-color: honeydew;
        }

        td {
            padding: 2px 5px 2px 5px;
        }
    </style>
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Weekly Shipment Schedule || {{ date("jS F, Y") }}
                            <span class="pull-right">
                                <i data-url="/weekly-shipment-schedule/pdf"
                                   style="color: #DC0A0B; cursor: pointer"
                                   class="text-danger fa fa-file-pdf-o downloadBtn"
                                ></i>|
                                <i data-url="/weekly-shipment-schedule/excel"
                                   style="cursor: pointer"
                                   class="text-success downloadBtn fa fa-file-excel-o"
                                ></i>
                            </span>
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <form action="{{ url('/weekly-shipment-schedule') }}" method="post" id="searchReportForm">
                            <div class="form-group">
                                <div class="row m-b">
                                    <div class="col-sm-3">
                                        <input type="date" class="form-control form-control-sm" name="date" id="date">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control form-control-sm" name="week" id="week"
                                               readonly>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="form-control form-control-sm select2-input"
                                                name="buyer_id" id="buyer_id">
                                            <option disabled selected hidden>Select Buyer</option>
                                            @foreach($buyers as $buyer)
                                                <option value="{{ $buyer->id }}">{{ $buyer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="submit" class="btn btn-sm btn-info form-control form-control-sm">
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
@endsection

@section('scripts')
    <script>
        $(document).on('change', '#date', function () {
            let date = $(this).val();
            $.ajax({
                url: "/weekly-shipment-schedule/get-week-of-the-year",
                type: "get",
                data: {
                    date: date,
                },
                success(response) {
                    $('#week').val(response);
                }
            })
        });

        $(document).on('submit', '#searchReportForm', function (e) {
            e.preventDefault();
            let form = $(this).serializeArray();
            $.ajax({
                url: "/weekly-shipment-schedule",
                type: "post",
                data: form,
                dataType: "html",
                success(response) {
                    $(".report-div").html(response);
                }
            })
        });

        $(document).on('click', '.downloadBtn', function () {
            let url = $(this).data('url');
            let date = $("#date").val();
            let buyer_id = $("#buyer_id").val();
            url += (`?date=${date}&buyer_id=${buyer_id}`);
            location.assign(url);
        });
    </script>
@endsection
