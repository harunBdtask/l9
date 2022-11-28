@extends('merchandising::pdf_layout')

@section('content')
    @includeIf('merchandising::pdf.header', ['name' => 'Order Recap Report'])
    <div style="width: 100%">
        @includeIf('merchandising::order.recap_report.table')
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
