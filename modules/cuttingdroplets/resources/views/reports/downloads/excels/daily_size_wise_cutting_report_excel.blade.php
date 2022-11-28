<table>
    <thead>
    <tr>
        <td colspan="{{10 + count($sizes)}}"
            style="text-align: center; font-weight: bold; font-size: 20px; height: 35px">{{ factoryName() }}</td>
    </tr>
    <tr>
        <td colspan="{{10 + count($sizes)}}" style="text-align: center"><b>Daily Size Wise Cutting Report</b></td>
    </tr>
    </thead>
</table>
@include('cuttingdroplets::reports.includes.daily_size_wise_cutting_report_include')
