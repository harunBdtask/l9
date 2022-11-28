<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 align="center">Season Wise Order Overview</h4>
    @includeIf('merchandising::order.overview_report.season_wise_order_overview_table')
    <div style="margin-top: 50px;">
        <table class="borderless">
            <tbody>
            <tr>
                <td class="text-center"><u>Prepared By</u></td>
                <td class='text-center'><u>Checked By</u></td>
                <td class="text-center"><u>Approved By</u></td>
            </tr>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>


{{--@extends('merchandising::pdf_layout')--}}

{{--@section('content')--}}
{{--    <div style="width: 100%;">--}}
{{--        @includeIf('merchandising::order.report.table')--}}
{{--    </div>--}}

{{--    <div style="margin-top: 50px">--}}
{{--        <table class="borderless">--}}
{{--            <tbody>--}}
{{--            <tr>--}}
{{--                <td class="text-center"><u>Prepared By</u></td>--}}
{{--                <td class='text-center'><u>Checked By</u></td>--}}
{{--                <td class="text-center"><u>Approved By</u></td>--}}
{{--            </tr>--}}
{{--            </tbody>--}}
{{--        </table>--}}
{{--    </div>--}}
{{--@endsection--}}
