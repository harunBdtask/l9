@extends('skeleton::layout')
@section('title','Buyer Style Report')
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
            <h2>Buyer Style Report</h2>
            <div class="clearfix"></div>
        </div>

        <div class="box-body table-responsive b-t">
            <div class="row">
                <form action="">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="col-sm-3" >
                                    <div class="form-group">
                                        <label style="margin-bottom: -2.5rem;">Buyer</label>
                                        <select name="" id="buyer_id" class="form-control form-control-sm select2-input">
                                            @foreach ($buyers as $key => $buyer)
                                            <option value="{{ $key }}">{{ $buyer }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3" >
                                    <div class="form-group">
                                        <label style="margin-bottom: -2.5rem;">Style</label>
                                        <select name="" id="style_id" class="form-control form-control-sm select2-input">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="text-center">
                                        <button style="margin-top: 19px;" id="buyerStyleReport" class="btn btn-sm btn-info"
                                                name="type" title="Details">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <br>

            <div class="">
                <div class="header-section" style="padding-bottom: 0px;">
                    <div class="pull-right" style="margin-bottom: -5%;">
                        <a id="buyer_style_pdf" data-value="" class="btn" href="dyeing-production-daily-report-pdf"><i
                                class="fa fa-file-pdf-o"></i></a>
                        <a id="buyer_style_excel" data-value="" class="btn" href="dyeing-production-daily-report-excel">
                            <i class="fa fa-file-excel-o"></i>
                        </a>
                    </div>
                </div>
                <center>
                    <table style="border: 1px solid black;width: 20%;">
                        <thead>
                        <tr>
                            <td class="text-center">
                                <span style="font-size: 12pt; font-weight: bold;">Buyer Style Report</span>
                                <br>
                            </td>
                        </tr>
                        </thead>
                    </table>
                </center>
                <br>
                <div class="row p-x-1">
                    <div class="col-md-12" id="buyerStyleTable"></div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
@push("script-head")
    <script>
        $(document).on('click','#buyerStyleReport', function(event) {
            event.preventDefault();
            let style_id = $('#style_id').val();
            if(!style_id)
            {
                alert('Please Select A Style')
            }
            else
            {
                $.ajax({
                    method : 'GET',
                    url : `{{ url('knitting/buyer-style-report/get-report') }}`,
                    data : {
                        style_id,
                    },
                    success: function(result){
                        let pdfQueryString = `/knitting/buyer-style-report/buyer-style-pdf?style_id=${style_id}`
                        let excelQueryString = `/knitting/buyer-style-report/buyer-style-excel?style_id=${style_id}`
                        $('#buyerStyleTable').html(result);
                        $("#buyer_style_pdf").attr('href',pdfQueryString)
                        $("#buyer_style_excel").attr('href',excelQueryString)
                    },
                    error: function(error){
                        console.log(error)
                    }
                })
            }

        });

        $(document).on('change','#buyer_id', function() {
        let buyer = $('#buyer_id').val();

        $.ajax({
            method : 'GET',
            url : `{{ url('knitting/buyer-style-report/buyer-wise-style') }}`,
            data : {
                buyer
            },
            success: function(result){
                $('#style_id').empty().select2();
                $('#style_id').append(`
                    <option value="">Select</option>
                    `)
                $.each(result,function(index,data){
                    $('#style_id').append(`
                    <option value="${data.style_name}">${data.style_name}</option>
                    `)
                })
                console.log(result)
            },
            error: function(error){
                console.log(error)
            }
        });
    });
    </script>
@endpush
