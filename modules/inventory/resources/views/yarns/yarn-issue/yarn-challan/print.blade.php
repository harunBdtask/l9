<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Yarn Issue Challan</title>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/css/print.css') }}" type="text/css"/>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <style>
        body {
            width: 100%;
            height: 100%;
            font: 10pt "Tahoma";
            background-color: #FAFAFA;
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .page {
            width: 210mm;
            padding: 30px;
            min-height: 297mm;
            margin: 10mm auto;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 3px 5px;
        }

        table.borderless {
            border: none;
        }

        .borderless td, .borderless th {
            border: none;
        }

        @page {
            size: A4;
            margin: 5mm 5mm;
        }

        @media print {
            html, body {
                width: 210mm;
                /*height: 293mm;*/
            }

            table {
                page-break-inside: avoid;
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
</head>
<body>
<main>
    <div class="page">
        <header style="text-align: right; margin-bottom: 15px">
            <small>
                R.Gen-{{date('d-m-Y H:i')}}/{{auth()->user()->first_name}} {{auth()->user()->last_name}}
            </small>
        </header>
        @include('inventory::yarns.yarn-issue.yarn-challan.table')
{{--        <footer style="text-align: center; margin-top:20px">--}}
{{--            © Copyright - goRMG ERP. Produced by Skylark Soft Limited.--}}
{{--        </footer>--}}
    </div>
</main>
</body>
</html>
