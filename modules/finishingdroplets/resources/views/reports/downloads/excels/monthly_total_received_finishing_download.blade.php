<table>
    <thead>
    <tr>
        <td colspan="{{ 5 + collect($receiveFloors)->count() + collect($finishingFloors)->count() }}"
            style="text-align: center; font-weight: bold; font-size: 20px; height: 35px">{{ factoryName() }}</td>
    </tr>
    <tr>
        <td colspan="{{ 5 + collect($receiveFloors)->count() + collect($finishingFloors)->count() }}"
            style="text-align: center;height: 35px">
            <b>Monthly Total Received Finishing</b>
        </td>
    </tr>
    </thead>
</table>
@include('finishingdroplets::reports.tables.monthly_total_received_finishing_report_table')
