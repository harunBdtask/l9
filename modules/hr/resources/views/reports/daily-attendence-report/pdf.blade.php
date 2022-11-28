<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Daily Knitting Report {{date('d-m-Y', strtotime(request('date')))}}</title>
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

        .page {
            width: 190mm;
            min-height: 297mm;
            margin: 10mm auto;
            border-radius: 5px;
            background: white;
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
        .border-color
        {
            border: 1px solid #201c1c !important;
            font-size: 17px;
            font-weight: 700;
        }

    </style>
</head>

<body style="background: white;">
<main>
    <div>
        <div style="width: 100%;" class="header-section">
            @php $title = "Daily Attendence Report-".date('d-m-Y', strtotime(request('date'))); @endphp
            @includeIf('hr::pdf.header', ['name' => $title])
        </div>
        <div class="body-section" style="margin-top: 0px;">
            @if($employees)
                @includeIf('hr::reports.daily-attendence-report.view-body')
            @endif
        </div>
        @include('skeleton::reports.downloads.signature')
    </div>
</main>
</body>
</html>