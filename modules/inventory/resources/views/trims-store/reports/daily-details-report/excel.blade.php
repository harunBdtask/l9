<div>
    <table style="border:1px solid; text-align: center;">
        <thead>
        <tr>
            <td colspan="17" style="height: 30px; text-align: center;">{{ factoryName() }}</td>
        </tr>
        <tr>
            <td colspan="17" style="height: 20px; text-align: center;">
                <b>Daily Details Report {{ date('d-m-Y', strtotime(request('from_date'))) }} - {{ date('d-m-Y', strtotime(request('to_date'))) }}</b>
            </td>
        </tr>
        </thead>
    </table>
    @include('inventory::trims-store.reports.daily-details-report.table')
</div>
