@extends('skeleton::layout')
@section('title', 'Style Audit Report')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Style Audit Report || {{ date("jS F, Y") }}
                            @if ($type ?? null)
                            <span class="pull-right"><i data-url="/style-audit-report/get-report-value/pdf"
                                style="color: #DC0A0B; cursor: pointer"
                                class="text-danger fa fa-file-pdf-o downloadBtn"></i> | <i
                                    data-url="/style-audit-report/value/xls"
                                    style="cursor: pointer"
                                    class="text-success downloadBtn fa fa-file-excel-o"></i>
                            </span>
                            @else
                            <span class="pull-right"><i data-url="/style-audit-report/get-report/pdf"
                                style="color: #DC0A0B; cursor: pointer"
                                class="text-danger fa fa-file-pdf-o downloadBtn"></i> | <i
                                    data-url="/style-audit-report/xls"
                                    style="cursor: pointer"
                                    class="text-success downloadBtn fa fa-file-excel-o"></i>
                            </span>
                            @endif
                            
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <form action="{{ url('/style-audit-report') }}" method="post" id="searchReportForm">
                            <div class="form-group">
                                <div class="row m-b">
                                    <div class="col-sm-3">
                                        <select class="form-control form-control-sm select2-input"
                                                name="buyer_id" id="buyer_id">
                                            <option disabled selected hidden>Select Buyer</option>
                                            @foreach($buyers as $buyer)
                                                <option value="{{ $buyer->id }}">{{ $buyer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="form-control form-control-sm select2-input"
                                                name="style_id" id="style_id">
                                            <option disabled selected hidden>Select Style</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="submit"
                                            class="btn btn-sm btn-info form-control-sm">
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
        $(document).on('submit', '#searchReportForm', function (e) {
            e.preventDefault();
            let style_id = $("#style_id").val();
            if (!style_id) {
                return false;
            }
            let form = $(this).serializeArray();
            let url = "/style-audit-report/get-report";
            let type = "{{ $type ?? null }}";
            if (type) {
                url += "/value";
            }

            $.ajax({
                url: url,
                type: "get",
                data: form,
                dataType: "html",
                success(response) {
                    $(".report-div").html(response);
                }
            })
        });

        $(document).on('change', '#buyer_id', function () {
            let buyerId = $(this).val();
            $.ajax({
                url: "/common-api/buyers-styles/" + buyerId,
                type: "get",
                success(response) {
                    let options = "<option disabled selected hidden>Select Style</option>";
                    response.forEach(el => {
                        options += `<option value="${el.id}">${el.text}</option>`;
                    });
                    $("#style_id").html(options);
                }
            });
        });

        $(document).on('click', '.downloadBtn', function () {
            let style_id = $("#style_id").val();
            if (!style_id) {
                return false;
            }
            let url = $(this).data('url');
            url += ("?style_id=" + style_id);
            location.assign(url);
        });
    </script>
@endsection
