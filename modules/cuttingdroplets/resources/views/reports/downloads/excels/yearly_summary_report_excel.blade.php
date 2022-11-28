<table>
    <thead>
    <tr>
        <td colspan="{{ collect($cuttingFloors)->count() + collect($inputFloors)->count() + 8 }}"
            style="text-align: center; font-weight: bold; font-size: 20px; height: 35px">{{ factoryName() }}</td>
    </tr>
    <tr>
        <td colspan="{{ collect($cuttingFloors)->count() + collect($inputFloors)->count() + 8 }}"
            style="text-align: center;height: 35px">
            <b>Yearly Summary Report
                <br> {{ \Carbon\Carbon::make(collect($report)->keys()->first())->format('F') }}</b>
        </td>
    </tr>
    </thead>
</table>
@include('cuttingdroplets::reports.tables.yearly_summary_report_table')
