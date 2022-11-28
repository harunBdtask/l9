<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sample Trims Issue PDF</title>
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
            font: 8pt "Tahoma";
        }
        .body-section {
            padding-top: 0px;
        }
        .text-uppercase {
            text-transform: uppercase;
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
        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
        }
        .list-item{
            border: 1px solid #555;
            margin: 0px 5px;
            border-bottom: 0px;
            padding: 10px;
        }
        .list-item:first-child{
            margin-top: 5px;
        }
        .list-item:last-child{
            border-bottom: 1px  solid #555;
            margin-bottom: 5px;
        }
    </style>
</head>

<body style="background: white;">
<main>
    <div class="page">
        <div class="header-section" style="padding-bottom: 0px;">
            <table class="borderless">
                <thead>
                <tr>
                    <td class="text-center">
                        <span style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span><br>
                        <b>{{ factoryAddress() }}</b><br>
                        <br>
                    </td>
                </tr>
                </thead>
            </table>
            <hr>
        </div>
        <br>
        @includeIf('sample::trims-issue.details')
    </div>
</main>

</body>
</html>
