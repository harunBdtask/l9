@extends('skeleton::layout')
@section('title','Order Details')
@section('content')
    <style type="text/css">
        #pdfGenerateInfo {
            display: none;
        }

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

        .select2-container .select2-selection--single {
            height: 32px !important;
        }

        .select2-container--default .select2-selection--multiple {
            min-height: 35px !important;
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
                <h2>Order Details</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form class="col-md-12" action="">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Company</label>
                                    <select name="factory_id" class="form-control form-control-sm select2-input"
                                            id="factory_id">
                                        <option value="">Select Company</option>
                                        @foreach($factories as $key=>$factory)
                                            <option
                                                value="{{ $factory->id }}" {{  in_array($factory->id,[$factoryId,factoryId()]) ? 'selected' : null }}>{{ $factory->factory_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>Buyer</label>
                                    <select name="buyer_id" class="form-control form-control-sm select2-input"
                                            id="buyer_id">
                                        <option value="">Select Buyer</option>
                                        @foreach($buyers as $buyer)
                                            <option value="{{ $buyer->id }}" {{ $buyer->id == ($buyerId) ? 'selected' : null }}>{{ $buyer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Unique ID </label>
                                    <select name="job_no" class="form-control form-control-sm select2-input"
                                            id="job_no">
                                        <option value="">Select Unique ID</option>
                                        @foreach($jobs as $job)
                                            <option
                                                value="{{ $job }}" {{ $job == $jobNo ? 'selected' : null }}>{{ $job }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Style Name</label>
                                    <input name="style_name" style="height: 32px;" type="text"
                                           class="form-control form-control-sm"
                                           value="{{ $request->style_name ?? null }}"
                                           id="style_name">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>From Date</label>
                                    <input name="from_date" style="height: 32px;" type="date"
                                           class="form-control form-control-sm"
                                           value="{{ $request->from_date ?? null }}"
                                           id="from_date">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>To Date</label>
                                    <input name="to_date" style="height: 32px;" type="date"
                                           class="form-control form-control-sm"
                                           value="{{ $request->to_date ?? null }}"
                                           id="to_date">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="text-center">
                                    <button style="margin-top: 29px;" class="btn btn-sm btn-info"
                                        name="type" value="order_details" title="Details">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <br>
                @if( count($pos) > 0)
                    <div class="">
                        <div class="header-section" style="padding-bottom: 0px;">
                            <div class="pull-right" style="margin-bottom: -5%;">
                                {{--                                <a id="order_print" data-value="{{ $type }}" class="btn print"--}}
                                {{--                                   href="javascript:void(0)"><i--}}
                                {{--                                        class="fa fa-print"></i>--}}
                                {{--                                </a>--}}
                                <a id="order_pdf" data-value="{{ $type }}" class="btn" href="javascript:void(0)"><i
                                        class="fa fa-file-pdf-o"></i></a>
                                @if($type === 'color_size_breakdown')
                                    <a id="order_excel" data-value="{{ $type }}" class="btn" href="javascript:void(0)">
                                        <i class="fa fa-file-excel-o"></i>
                                    </a>
                                @endif

                            </div>
                        </div>
                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span style="font-size: 12pt; font-weight: bold;">Order Report</span>
                                        <br>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </center>
                        <br>
                        <div class="row p-x-1">
                            <div class="col-md-12">
                                {!! $summery !!}
                            </div>
                        </div>
                        <div class="body-section" style="margin-top: 0;">

                            @includeIf('merchandising::order.report.details.table')
                        </div>

                    </div>
                @endif
            </div>
        </div>
        @endsection
        @push("script-head")
            <script>
                $(document).ready(function () {
                     factoryWiseBuyer();
                    $('.print').click(function (e) {
                        e.preventDefault();

                        let factory_id = $('#factory_id').val();
                        let buyer_id = $('#buyer_id').val();
                        let job = $('#job_no').val();
                        // let job_no = job ? job.split('#')[0] + '**' + job.split('#')[1] : null;
                        let po_no = $('#po_no').val();
                        let from_date = $('#from_date').val();
                        let to_date = $('#to_date').val();
                        let style_name = $('#style_name').val();
                        let search_type = $('#search_type').val();
                        let type = $(this).data("value")


                        let link = `{{ url('/orders/print') }}?factory_id=${factory_id}&buyer_id=${buyer_id}&job_no=${job}&po_no=${po_no}&from_date=${from_date}&to_date=${to_date}&style_name=${style_name}&search_type=${search_type}&type=${type}`;

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
                        factoryWiseBuyer();
                    });

                    $(document).on('change', '#buyer_id', function () {
                        let factoryId = $("#factory_id").val();
                        let buyerId = $(this).val();
                        $('#job_no').empty().append(`<option value="">Select Unique ID</option>`).val('').trigger('change');
                        $('#po_no').empty().trigger('change');
                        $.ajax({
                            method: 'GET',
                            url: `/orders/get-jobs?factoryId=${factoryId}&buyerId=${buyerId}`,
                            success: function (result) {
                                $.each(result, function (key, value) {
                                    let element = `<option value="${key}">${value}</option>`;
                                    $('#job_no').append(element);
                                })
                            },
                            error: function (error) {
                                console.log(error)
                            }
                        })

                    });

                    $(document).on('change', '#job_no', function () {
                        let factoryId = $("#factory_id").val();
                        let buyerId = $("#buyer_id").val();
                        let jobNo = $(this).val();
                        $('#po_no').empty().trigger('change');
                        if (factoryId && buyerId && jobNo) {
                            $.ajax({
                                method: 'POST',
                                url: `/orders/get-job-wise-po`,
                                data: {
                                    factoryId,
                                    buyerId,
                                    jobNo
                                },
                                success: function (result) {
                                    console.log(result);
                                    $("#style_name").val(result.style_name)
                                    $.each(result.po, function (key, value) {
                                        let element = `<option value="${key}">${value}</option>`;
                                        $('#po_no').append(element);
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
                    let job = $('#job_no').val();
                    // let job_no = job ? j : null;
                    let po_no = $('#po_no').val();
                    let from_date = $('#from_date').val();
                    let to_date = $('#to_date').val();
                    let style_name = $('#style_name').val();
                    let type = $(this).data("value")

                    let link = `{{ url('/orders/pdf') }}?factory_id=${factory_id}&buyer_id=${buyer_id}&job_no=${job}&po_no=${po_no}&from_date=${from_date}&to_date=${to_date}&style_name=${style_name}&type=${type}`;

                    window.open(link, '_blank');
                });
                $(document).on('click', '#order_excel', function () {
                    let factory_id = $('#factory_id').val();
                    let buyer_id = $('#buyer_id').val();
                    let job = $('#job_no').val();
                    // let job_no = job ? j : null;
                    let po_no = $('#po_no').val();
                    let from_date = $('#from_date').val();
                    let to_date = $('#to_date').val();
                    let style_name = $('#style_name').val();
                    let type = $(this).data("value")

                    let link = `{{ url('/orders/excel') }}?factory_id=${factory_id}&buyer_id=${buyer_id}&job_no=${job}&po_no=${po_no}&from_date=${from_date}&to_date=${to_date}&style_name=${style_name}&type=${type}`;

                    window.open(link, '_blank');
                });

                function factoryWiseBuyer() {
                    let factoryId = $("#factory_id").val();
                    $('#buyer_id').empty().append(`<option value="">Select Buyer</option>`).val('').trigger('change');
                    $('#po_no').empty().trigger('change');
                    $.ajax({
                        method: 'GET',
                        url: `/orders/get-buyers?factoryId=${factoryId}`,
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
                }
            </script>
    @endpush
