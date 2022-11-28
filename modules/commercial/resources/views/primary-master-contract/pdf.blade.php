<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Export Contract</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <style type="text/css">
        .v-align-top td, .v-algin-top th {
            vertical-align: top;
        }

        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding-left: 13px;
            background-color: white;
            font: 10pt "Tahoma";
        }

        .page {
            background: white;
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
            border: 1px solid white;
            padding-top: 0;
            margin: 0;
            vertical-align: top;
        }

        table.borderless {
            border: none;
        }

        table.border {
            border: 1px solid white !important;
            width: 20%;
            margin-left: auto;
            margin-right: auto;
        }

        .borderless td, .borderless th {
            border: none;
        }

        .body-section .borderless td, th {
            text-align: left;
        }

        footer {
            position: fixed;
        }
    </style>
</head>

<body style="background: white;">
<main>
    <div class="page" style="padding-top: 5px">

        <div style="width: 100%" class="header-section">
            @includeIf('commercial::pdf.header', ['name' => ''])
        </div>
        
        @include('commercial::primary-master-contract.view-body')

        <div style="margin-top: 30%;">
           <table>
               <tbody>
                <tr>
                    <td class="text-left"> <u>Authorized Signature</u> </td>
                    <td class="text-right" style="padding-right: 5%;"><u>Authorized Signature</u></td>
                    <td></td>
                </tr>
               </tbody>
           </table>
        </div>

        
    </div>
</main>
</body>
</html>
