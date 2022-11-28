<table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
    <tr>
        <td colspan="5">{{ groupName() }}</td>
    </tr>
    <tr>
        <td colspan="5">{{ factoryName() }}</td>
    </tr>
    @include('warehouse-management::reports.includes.daily_out_report_table')
</table>