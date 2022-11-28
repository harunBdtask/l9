@extends('merchandising::pdf_layout')

@section('content')
    <div style="width: 100%" class="header-section">
        @includeIf('merchandising::pdf.header', ['name' => 'Fabric Booking Details Report'])
    </div>
    <div style="width: 100%">
        @include('merchandising::reports.fabric_bookings.summery_report.includes.table')
    </div>

    <div style="margin-top: 50px">
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
@endsection
