@extends('skeleton::layout')
@section("title") BOM Report @endsection
@push('style')
    <style type="text/css">
        .v-align-top td, .v-algin-top th {
            vertical-align: top;
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 10mm auto;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .header-section {
            padding: 10px;
        }

        .body-section {
            padding: 10px;
            padding-top: 0px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        table th {
            height: 35px;
        }

        .head_sec:hover {
            background-color: #d3ecff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 3px;
            padding-bottom: 3px;
        }

        table.borderless {
            border: none;
        }

        .borderless td, .borderless th {
            border: none;
        }

    </style>
@endpush
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>BOM Report Checklist</h2>
                <div class="clearfix"></div>
            </div>

            <div class="box-body">
                {!! Form::open(['url' => 'bom-report', 'id'=>'searchForm']) !!}
                <div class="row m-b">
                    <div class="col-sm-2">
                        <label>Buyer</label>
                        @php
                            $buyers= collect($buyers)->prepend('Select', 0);
                        @endphp
                        {!! Form::select('buyer_id', $buyers, null, ['class' => 'buyer-select form-control form-control-sm select2-input', 'id'=>'buyer_id']) !!}
                    </div>
                    <div class="col-sm-2">
                        <label>Style</label>
                        {!! Form::select('style_id', [], null, ['class' => 'style-select form-control form-control-sm select2-input', 'id'=>'style_id']) !!}
                    </div>
                    <div class="col-sm-2">
                        <label>Unique Id</label>
                        <input type="text" readonly
                               class="form-control form-control-sm" id="unique_id">
                    </div>

                    <div class="col-sm-1">
                        <button class="btn btn-sm btn-info" type="submit" style="margin-top: 29px">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                    <div class="col-sm-5"></div>
                </div>
                {!! Form::close() !!}
                <div id="reportDiv" hidden>
                    <div class="header-section" style="padding-bottom: 0px;">
                        <div class="pull-right" style="margin-bottom: -5%;">
                            <!-- <a id="bom_print" class="btn print"  href="javascript:void(0)"><i class="fa fa-print"></i></a> -->
                            <a id="order_pdf" class="btn" href="javascript:void(0)"><i
                                    class="fa fa-file-pdf-o"></i></a>
                        </div>
                    </div>
                    <center>
                        <table style="border: 1px solid black;width: 20%;">
                            <thead>
                            <tr>
                                <td class="text-center">
                                    <span style="font-size: 12pt; font-weight: bold;">BOM Report</span>
                                    <br>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </center>
                    <br>
                    <div class="body-section" style="margin-top: 0;" id="bom-report"></div>
                    @include('skeleton::reports.downloads.signature')
                </div>
            </div>
        </div>
    </div>
    <div style="text-align: center;">
        <img class="loader" src="{{asset('loader.gif')}}" style="height: 30px;display: none;" alt="loader">
    </div>
@endsection
@section('scripts')
    <script>
        function fetchStyle() {
            let buyer_id = $("#buyer_id").val();
            $('#style_id').empty().append(`<option value="">Select Style</option>`).val('').trigger('change');
            if (buyer_id) {
                $.ajax({
                    method: 'GET',
                    url: `/common-api/buyers-style-name/${buyer_id}`,
                    success: function (result) {
                        $.each(result, function (key, value) {
                            let element = `<option value="${value.budget_id}" data-job="${value.unique_id}" >${value.text}</option>`;
                            $('#style_id').append(element);
                        })
                    },
                    error: function (error) {
                        console.log(error)
                    }
                })
            }
        }

        $(document).on('change', '#buyer_id', () => {
            fetchStyle()
        });

        $(document).on('change', '#style_id', function () {
            let style = $('#searchForm select#style_id option:selected'), uniqueId = style.attr("data-job");
            $("#unique_id").val(uniqueId);
        })

        $("#searchForm").submit(function (e) {
            e.preventDefault();
            let style_id = $("#style_id").val();
            if (!style_id) {
                alert('Please select buyer and style');
                return false;
            }
            let formData = $(this).serializeArray();
            $("#reportDiv").removeAttr("hidden");
            $.ajax({
                url: "/bom-report-fetch-checklist",
                type: "get",
                dataType: "html",
                data: formData,
                beforeSend() {
                    $('html,body').css('cursor', 'wait');
                    $("html").css({'background-color': 'black', 'opacity': '0.5'});
                    $(".loader").show();
                },
                complete() {
                    $('html,body').css('cursor', 'default');
                    $("html").css({'background-color': '', 'opacity': ''});
                    $(".loader").hide();
                },
                success(data) {
                    $("#bom-report").html(data);
                },
                error(errors) {
                    console.log(errors);
                }
            })
        })

        $('#order_pdf').click(function (e) {
            e.preventDefault();
            let style_id = $("#style_id").val();
            window.location.replace('/bom-report-fetch-checklist?type=pdf&style_id=' + style_id);
        })

        $('#bom_print').click(function (e) {
            e.preventDefault();
            let style_id = $("#style_id").val();
            let link = '/bom-report-fetch-checklist?type=print&style_id=' + style_id;
            printPage(link);
        });

        function closePrint() {
            document.body.removeChild(this.__container__);
        }

        function setPrint() {
            this.contentWindow.__container__ = this;
            this.contentWindow.onbeforeunload = closePrint;
            this.contentWindow.onafterprint = closePrint;
            this.contentWindow.focus(); // Required for IE
            this.contentWindow.print();
        }

        function printPage(sURL) {
            var oHiddFrame = document.createElement("iframe");
            oHiddFrame.onload = setPrint;
            oHiddFrame.style.visibility = "hidden";
            oHiddFrame.style.position = "fixed";
            oHiddFrame.style.right = "0";
            oHiddFrame.style.bottom = "0";
            oHiddFrame.src = sURL;
            document.body.appendChild(oHiddFrame);
        }
    </script>
@endsection
