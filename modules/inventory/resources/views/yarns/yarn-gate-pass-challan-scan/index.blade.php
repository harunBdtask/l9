@extends('skeleton::layout')
@section('title','Gate Pass Challan Point Scan')
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
            <div class="box-header" style="text-align: center">
                <div class="col-11">
                    <h2>
                        Gate Pass Challan Point Scan || {{\Carbon\Carbon::parse(now())->format('l jS \of F Y h:i:s A')}}
                    </h2>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-6">

                        <div class="input-group" style="width:100%;">
                            <input type="text" class="form-control" name="search"
                                   id="search" placeholder="Scan Gate Pass Challan Here">
                        </div>

                    </div>
                </div>
                <div class="body-section">

                    <div id="yarnGatePassTable">

                    </div>

                </div>
            </div>
        </div>

    </div>
    </div>
@endsection
@push("script-head")
    <script>
        $(document).on('change', '#search', function (event) {
            event.preventDefault();
            let search = $('#search').val();

            $.ajax({
                method: 'GET',
                url: `/inventory/yarn-gate-pass-challan-scan/search`,
                data: {
                    search
                },
                success: function (result) {
                    $('#yarnGatePassTable').html(result);
                },
                error: function (error) {
                    toastr.warning(error.responseJSON.message);
                    $('#yarnGatePassTable').html('');
                    console.log(error)
                }
            })

        });
    </script>
@endpush
