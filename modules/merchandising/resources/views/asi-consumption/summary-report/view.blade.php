@extends('skeleton::layout')
@section('title','ASI Consumption Summary Report')
@section('content')
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
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

        @page {
            size: landscape;
            /*margin: 5mm;*/
            /*margin-left: 15mm;*/
            /*margin-right: 15mm;*/
        }

        @media print {
            html, body {
                width: 210mm;
                /*height: 293mm;*/
            }

            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        }
    </style>
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>ASI Consumption Summary Report</h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action="">
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Company</label>
                                        <select name="factory_id" class="form-control select2-input" id="factory_id">
                                            <option value="">Select Company</option>
                                            @foreach($factories as $key=>$factory)
                                                <option
                                                    value="{{ $factory->id }}" {{ $factory->id == $factoryId ? 'selected' : null }}>{{ $factory->factory_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Buyer</label>
                                        <select name="buyer_id" class="form-control select2-input" id="buyer_id">
                                            <option value="">Select Buyer</option>
                                            @foreach($buyers as $buyer)
                                                <option
                                                    value="{{ $buyer->id }}" {{ $buyer->id == $buyerId ? 'selected' : null }}>{{ $buyer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Seasons</label>
                                        <select name="season_id" class="form-control select2-input" id="season_id">
                                            <option value="">Select Season</option>
                                            @foreach($seasons as $season)
                                                <option
                                                    value="{{ $season->id }}" {{ $season->id == $seasonId ? 'selected' : null }}>{{ $season->season_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
{{--                                <div class="col-sm-3">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label>Style Name</label>--}}
{{--                                        <input name="style_name" style="height: 40px;" type="text" class="form-control"--}}
{{--                                               value="{{ $request->style_name ?? null }}"--}}
{{--                                               id="style_name">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Style Name</label>
                                        <select name="style_name[]" style="height: 40px;" class="form-control select2-input"
                                                multiple id="style_name">
                                            @foreach($style_name as $key => $style)
                                                <option
                                                    value="{{ $style }}" {{ in_array($style, request('style_name') ?? []) ? 'selected' : null }}>{{ $style }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>From Date</label>
                                    <input
                                        name="from_date" id="from_date"
                                        style="height: 40px;" type="text"
                                        class="form-control datepicker" autocomplete="off"
                                        value="{{ $fromDate ?? null }}"

                                    >
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>To Date</label>
                                    <input
                                        name="to_date" id="to_date"
                                        style="height: 40px;" type="text"
                                        class="form-control datepicker" autocomplete="off"
                                        value="{{ $toDate ?? null }}"
                                    >
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label></label>
                                    <button style="margin-top: 8px;" class="btn btn-info">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @if(($consumptions))
                    <div class="">
                        <div class="header-section" style="padding-bottom: 0px;">
                            <div class="pull-right" style="margin-bottom: -5%;">
                                <a id="order_print" class="btn print" href="javascript:void(0)"><i
                                        class="fa fa-print"></i>
                                </a>
                                <a id="order_pdf" class="btn" href="javascript:void(0)"><i class="fa fa-file-pdf-o"></i></a>
                            </div>
{{--                            @includeIf('merchandising::pdf.header')--}}
                            <br>
                            <hr>
                        </div>
                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                    <span
                                        style="font-size: 12pt; font-weight: bold;">ASI Consumption Summary Report</span>
                                        <br>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </center>
                        <br>
                        <div class="body-section" style="margin-top: 0;">
                            @includeIf('merchandising::asi-consumption.summary-report.table')
                        </div>

{{--                        <div style="margin-top: 16mm">--}}
{{--                            <table class="borderless">--}}
{{--                                <tbody>--}}
{{--                                <tr>--}}
{{--                                    <td class="text-center"><u>Prepared By</u></td>--}}
{{--                                    <td class='text-center'><u>Checked By</u></td>--}}
{{--                                    <td class="text-center"><u>Approved By</u></td>--}}
{{--                                </tr>--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}

                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
@push("script-head")
    <script>
        $(document).ready(function () {

            $('.print').click(function (e) {
                e.preventDefault();

                let factory_id = $('#factory_id').val();
                let buyer_id = $('#buyer_id').val();
                let season_id = $('#season_id').val();
                let from_date = $('#from_date').val();
                let to_date = $('#to_date').val();
                let style_name = $('#style_name').val();

                let link = `{{ url('/asi-consumption/summary-report-print') }}?factory_id=${factory_id}&buyer_id=${buyer_id}&season_id=${season_id}&from_date=${from_date}&to_date=${to_date}&style_name=${style_name}`;

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

            $(document).on('change', '#factory_id', function () {
                let factoryId = $(this).val();
                $('#buyer_id').empty().append(`<option value="">Select Buyer</option>`).val('').trigger('change');
                $.ajax({
                    method: 'GET',
                    url: `/asi-consumption/get-buyers?factoryId=${factoryId}`,
                    success: function (result) {
                        $.each(result, function (key, value) {
                            let element = `<option value="${value.id}">${value.name}</option>`;
                            $('#buyer_id').append(element);
                        })
                    },
                    error: function (error) {
                        console.log(error)
                    }
                })
            });

            $(document).on('change', '#buyer_id', function () {
                let factoryId = $("#factory_id").val();
                let buyerId = $(this).val();
                $('#season_id').empty().append(`<option value="">Select Season</option>`).val('').trigger('change');
                $.ajax({
                    method: 'GET',
                    url: `/asi-consumption/get-seasons?factoryId=${factoryId}&buyerId=${buyerId}`,
                    success: function (result) {
                        $.each(result, function (key, value) {
                            let element = `<option value="${value.id}">${value.season_name}</option>`;
                            $('#season_id').append(element);
                        })
                    },
                    error: function (error) {
                        console.log(error)
                    }
                })

            });

            $(document).on('change', '#season_id', function () {
                let factoryId = $("#factory_id").val();
                let buyerId = $("#buyer_id").val();
                let seasonId = $(this).val();
                $('#style_name').empty().trigger('change');
                if (factoryId && buyerId && seasonId) {
                    $.ajax({
                        method: 'POST',
                        url: `/asi-consumption/get-styles`,
                        data: {
                            factoryId,
                            buyerId,
                            seasonId
                        },
                        success: function (result) {
                            console.log(result);
                            // $("#style_name").val(result.style_name)
                            $.each(result.style_name, function (key,value) {
                                let element = `<option value="${value.style_name}">${value.style_name}</option>`;
                                $('#style_name').append(element);
                            })
                        },
                        error: function (error) {
                            console.log(error)
                        }
                    })
                }


            });


        });

        $(document).on('click', '#order_pdf', function () {
            let factory_id = $('#factory_id').val();
            let buyer_id = $('#buyer_id').val();
            let season_id = $('#season_id').val();
            let from_date = $('#from_date').val();
            let to_date = $('#to_date').val();
            let style_name = $('#style_name').val();

            let link = `{{ url('/asi-consumption/summary-report-pdf') }}?factory_id=${factory_id}&buyer_id=${buyer_id}&season_id=${season_id}&from_date=${from_date}&to_date=${to_date}&style_name=${style_name}`;

            window.open(link, '_blank');
        });
    </script>
@endpush
