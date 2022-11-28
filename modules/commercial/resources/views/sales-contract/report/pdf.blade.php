<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ASI Consumption Summary Report</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <style type="text/css">
        .v-align-top td, .v-algin-top th {
            vertical-align: top;
        }

        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: white;
            font: 10pt "Tahoma";
        }

        /*.page {*/
            /*width: 350mm;*/
            /*min-height: 297mm;*/
            /*margin: 10mm auto;*/
            /*border-radius: 5px;*/
            /*background: white;*/
        /*}*/

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
            page-break-before: avoid;
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

        table.border {
            border: 1px solid black;
            width: 20%;
            margin-left: auto;
            margin-right: auto;
        }

        .borderless td, .borderless th {
            border: none;
        }

    </style>
</head>

<body style="background: white;">
<div class="page">
    <div class="">
        <div class="header-section" style="padding-bottom: 0px;">
            <div class="col-md-8">
                <table class="borderless">
                    <thead>
                    <tr>
                        <td class="text-center">
                            <img src="{{asset('storage/company/company_logo.png')}}" alt="logo" width="50" height="50">
                        </td>
                        <td><h1>{{ factoryName() }}</h1></td>
                    </tr>
                    </thead>
                </table>
            </div>
            <hr>
        </div>
        <br>

        <div class="body-section" style="margin-top: 0px;">
            <table class="borderless">
                <thead>
                <tr>
                    <td class="text-center">
                        <span
                                style="font-size: 12pt; font-weight: bold;">PURCHASE CONTRACT</span>
                    </td>
                </tr>
                </thead>
            </table>
            <br>
            <div style="margin-top: 15px;">
                @include('commercial::sales-contract.report.view-body')
            </div>
        </div>

    </div>
</div>
</body>
</html>
