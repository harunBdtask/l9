@extends('skeleton::layout')
@section('title','Order View')
@section('content')
    <style type="text/css">
        #pdfGenerateInfo{
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
                <h2>Color & Size Merchandise Wise Report</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action="">
                        <div class="col-sm-7">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Company</label>
                                        <select name="factory_id" class="form-control form-control-sm select2-input" id="factory_id">
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
                                        <label>Dealing Merchant</label>
                                        <select name="dealing_merchant_id" class="form-control form-control-sm select2-input" id="dealing_merchant_id" required>
                                            <option value="">Select Dealing Merchant</option>
                                            @foreach($dealingMerchants as $dealingMerchant)
                                                <option
                                                    value="{{ $dealingMerchant->member->id }}" {{ $dealingMerchant->member->id == $dealingMerchantId ? 'selected' : null }}>{{ optional($dealingMerchant->member)->screen_name  ?? ''}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
{{--                                <div class="col-sm-2">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label>Buyer</label>--}}
{{--                                        <select name="buyer_id" class="form-control form-control-sm select2-input" id="buyer_id">--}}
{{--                                            <option value="">Select Buyer</option>--}}
{{--                                            @foreach($buyers as $buyer)--}}
{{--                                                <option--}}
{{--                                                    value="{{ $buyer->id }}" {{ $buyer->id == $buyerId ? 'selected' : null }}>{{ $buyer->name }}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Job No</label>
                                        <select name="job_no" class="form-control form-control-sm select2-input" id="job_no">
                                            <option value="">Select Job No</option>
                                            @foreach($jobs as $job)
                                                <option
                                                    value="{{ $job }}" {{ $job == $jobNo ? 'selected' : null }}>{{ $job }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
{{--                                <div class="col-sm-2">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label>Style Name</label>--}}
{{--                                        <input name="style_name" style="height: 40px;" type="text" class="form-control form-control-sm"--}}
{{--                                               value="{{ $request->style_name ?? null }}"--}}
{{--                                               id="style_name">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Po No</label>
                                        <select name="po_no[]" style="height: 40px;" class="form-control form-control-sm select2-input"
                                                multiple id="po_no">
                                            @foreach($poNos as $key => $poNo)
                                                @if($key == 0)
                                                    <option
                                                        value="All" {{ in_array('All', $request->po_no ?? []) ? 'selected' : null }}>
                                                        All Po
                                                    </option>
                                                @endif
                                                <option
                                                    value="{{ $poNo }}" {{ in_array($poNo, $request->po_no ?? []) ? 'selected' : null }}>{{ $poNo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Type</label>
                                    <select name="search_type" class="form-control form-control-sm select2-input" id="search_type">
                                        <option value="">Search Type</option>
                                        <option value="shipment_date" {{ request()->search_type == "shipment_date" ? 'selected' : null }}>Shipment Date</option>
                                        <option value="po_receive_date" {{ request()->search_type == "po_receive_date" ? 'selected' : null }}>PO Receive Date</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>From Date</label>
                                    <input name="from_date" id="from_date" style="height: 40px;" type="text"
                                           value="{{ $fromDate }}" class="form-control form-control-sm datepicker" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>To Date</label>
                                    <input name="to_date" id="to_date" style="height: 40px;" type="text"
                                           value="{{ $toDate }}" class="form-control form-control-sm datepicker" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label></label>
                                    <button style="margin-top: 10px;" class="btn btn-sm btn-info">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @if( count($pos) > 0)
                    <div class="">
                        <div class="header-section" style="padding-bottom: 0px;">
                            <div class="pull-right" style="margin-bottom: -5%;">
                                <a id="order_pdf" class="btn m-t-2" href="javascript:void(0)"><i class="fa fa-file-pdf-o"></i></a>
                            </div>
                            @includeIf('merchandising::pdf.header', ['name' => 'Order Report'])
                            <hr>
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
                            <div class="col-md-4">
                                {!! $summery !!}
                            </div>
                        </div>
                        <div class="body-section" style="margin-top: 0;">
                            @includeIf('merchandising::order.report.table')
                        </div>

                        @include('skeleton::reports.downloads.signature')

                    </div>
                @endif
            </div>
        </div>
        @endsection
        @push("script-head")
            <script>
                $(document).ready(function () {

                    $('.print').click(function (e) {
                        e.preventDefault();

                        let factory_id = $('#factory_id').val();
                        let dealing_merchant_id = $('#dealing_merchant_id').val();
                        let job = $('#job_no').val();
                        let job_no = job ? job.split('#')[0] + '**' + job.split('#')[1] : null;
                        let po_no = $('#po_no').val();
                        let from_date = $('#from_date').val();
                        let to_date = $('#to_date').val();
                        let style_name = null;
                        let search_type = $('#search_type').val();

                        let link = `{{ url('/orders/print') }}?factory_id=${factory_id}&dealing_merchant_id=${dealing_merchant_id}&job_no=${job_no}&po_no=${po_no}&from_date=${from_date}&to_date=${to_date}&style_name=${style_name}&search_type=${search_type}`;

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
                        let factoryId = $("#factory_id").val();
                        $('#dealing_merchant_id').empty().append(`<option value="">Select Dealing Merchant</option>`).val('').trigger('change');
                        $('#po_no').empty().trigger('change');
                        $.ajax({
                            method: 'GET',
                            url: `/orders/get-dealing-merchant?factoryId=${factoryId}`,
                            success: function (result) {
                                $.each(result, function (key, value) {
                                    let element = `<option value="${value.member.id}">${value.member.screen_name}</option>`;
                                    $('#dealing_merchant_id').append(element);
                                })
                            },
                            error: function (error) {
                                console.log(error)
                            }
                        })
                    });

                    $(document).on('change', '#dealing_merchant_id', function () {
                        let factoryId = $("#factory_id").val();
                        let dealingMerchantId = $(this).val();
                        $('#job_no').empty().append(`<option value="">Select Job No</option>`).val('').trigger('change');
                        $('#po_no').empty().trigger('change');
                        $.ajax({
                            method: 'GET',
                            url: `/orders/get-jobs?factoryId=${factoryId}&dealingMerchantId=${dealingMerchantId}`,
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
                        let dealingMerchantId = $("#dealing_merchant_id").val();
                        let jobNo = $(this).val();
                        $('#po_no').empty().trigger('change');
                        if (factoryId && dealingMerchantId && jobNo) {
                            $.ajax({
                                method: 'POST',
                                url: `/orders/get-job-wise-po`,
                                data: {
                                    factoryId,
                                    dealingMerchantId,
                                    jobNo
                                },
                                success: function (result) {
                                    console.log(result);
                                    // $("#style_name").val(result.style_name)
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
                    let dealing_merchant_id = $('#dealing_merchant_id').val();
                    let job = $('#job_no').val();
                    // let job_no = job ?null;
                    let po_no = $('#po_no').val();
                    let from_date = $('#from_date').val();
                    let to_date = $('#to_date').val();
                    let style_name = null;

                    let link = `{{ url('/orders/pdf') }}?factory_id=${factory_id}&dealing_merchant_id=${dealing_merchant_id}&job_no=${job}&po_no=${po_no}&from_date=${from_date}&to_date=${to_date}&style_name=${style_name}`;

                    window.open(link, '_blank');
                });
            </script>
    @endpush
