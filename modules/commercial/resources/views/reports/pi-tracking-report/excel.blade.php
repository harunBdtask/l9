<div>
    <table style="border:1px solid; text-align: center;">
        <thead>
        <tr>
            <td colspan="17" style="height: 30px; text-align: center;">{{ factoryName() }}</td>
        </tr>
        <tr>
            <td colspan="17" style="height: 20px; text-align: center;">PI Tracking Report</td>
        </tr>
        </thead>
    </table>
    @includeIf('commercial::reports.pi-tracking-report.table')
</div>
