<table>
    <thead>
    <tr>
        <td colspan="8"
            style="text-align: center; font-weight: bold; font-size: 20px; height: 35px">{{ factoryName() }}</td>
    </tr>
    <tr>
        <td colspan="8"
            style="text-align: center;height: 35px">
            <strong>Dyeing Batch Costing Report</strong>
        </td>
    </tr>
    </thead>
</table>
<br>
@includeIf('subcontract::report.batch-costing.body')
