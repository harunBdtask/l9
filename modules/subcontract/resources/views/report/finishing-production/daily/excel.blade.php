<table>
    <thead>
    <tr>
        <td colspan="13"
            style="text-align: center; font-weight: bold; font-size: 20px; height: 35px">{{ factoryName() }}</td>
    </tr>
    <tr>
        <td colspan="13"
            style="text-align: center;height: 35px">
            <strong>Daily Finishing Production Report</strong>
        </td>
    </tr>
    </thead>
</table>
<br>
@includeIf('subcontract::report.finishing-production.daily.table')
