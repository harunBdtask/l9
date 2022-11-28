<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{auth()->user()->factory->factory_name}}</title>

    <link href="{{ asset('modules/skeleton/css/print.css') }}" rel="stylesheet" type="text/css"/>

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
            background-color: #FAFAFA;
            font: 10pt "Tahoma";
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
    </style>
</head>
<body>

<div class="row">
    <div class="col-lg-12 text-center">
        <h4>{{ auth()->user()->factory->factory_name }}</h4>
        <strong>{{ auth()->user()->factory->factory_address }}</strong> <br>
        <strong>Requisition No: {{$approval->first()->requisition->requisition_no}}</strong>
    </div>
</div>
<br>
@include('finance::account_approval.view_body')
<br>
<div class="row text-center">
    <table class="borderless" style="width: 100%">
        <tbody>
        <tr>
            <td class="text-center"><u>Prepared By</u></td>
            <td class='text-center'><u>Checked By</u></td>
            <td class='text-center'><u>Audit Department</u></td>
            <td class='text-center'><u>Manager (Account)</u></td>
            <td class="text-center"><u>Authorized By</u></td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
