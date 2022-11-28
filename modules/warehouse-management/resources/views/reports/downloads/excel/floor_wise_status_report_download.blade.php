<table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
    <tr>
        <td colspan="7">{{ groupName() }}</td>
    </tr>
    <tr>
        <td colspan="7">{{ factoryName() }}</td>
    </tr>
    <tr>
        <td colspan="7">Floor {{ $warehouse_floor ?? '' }}</td>
    </tr>
    @include('warehouse-management::reports.includes.floor_wise_status_report_table')
</table>