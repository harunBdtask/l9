<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Yarn Issue Challan</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <style>

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font: 10pt "Tahoma";
            background-color: white;
        }
        @page{
            margin: 0;
            padding: 0;
            width: 210mm;
            background: white;
        }
        table {
            width: 100%;
            page-break-before: avoid;
            border-collapse: collapse;
        }

        th, td {
            padding: 3px;
        }
    </style>
</head>
<body style="background: white;">
<div class="page">
    <header style="text-align: right; margin-bottom: 15px">
        <small>
            R.Gen-{{date('d-m-Y H:i')}}/{{auth()->user()->first_name}} {{auth()->user()->last_name}}
        </small>
    </header>
    @include('inventory::yarns.yarn-issue.yarn-challan.table')
{{--    <footer style="--}}
{{--        left: 0;--}}
{{--        right: 0;--}}
{{--        height: 60px;--}}
{{--        bottom: -80px;--}}
{{--        position: fixed;--}}
{{--        text-align: center;">--}}
{{--        © Copyright - gRMG ERP. Produced by Skylark Soft Limited.--}}
{{--    </footer>--}}
</div>
</body>
</html>

