<table>
    <thead>
    <tr>
        <td colspan="{{4 + count($dates)}}"
            style="text-align: center; font-weight: bold; font-size: 20px; height: 35px">{{ factoryName() }}</td>
    </tr>
    <tr>
        <td colspan="{{4 + count($dates)}}" style="text-align: center;height: 35px">
            <b>Buyer Style Wise Cutting Report
                <br> {{ \Carbon\Carbon::make(collect($dates)->first())->format('F-Y') }}</b>
        </td>
    </tr>
    </thead>
</table>
@include('cuttingdroplets::reports.includes.buyer_style_wise_cutting_report_include')
