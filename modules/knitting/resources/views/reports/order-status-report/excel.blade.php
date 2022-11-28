<div>
    <table style="border:1px solid; text-align: center;">
        <thead>
        <tr>
            <td colspan="17" style="height: 30px; text-align: center;">{{ factoryName() }}</td>
        </tr>
        <tr>
            <td colspan="17" style="height: 20px; text-align: center;">Order Status Report</td>
        </tr>
        </thead>
    </table>
    @includeIf('knitting::reports.order-status-report.view-body')
</div>