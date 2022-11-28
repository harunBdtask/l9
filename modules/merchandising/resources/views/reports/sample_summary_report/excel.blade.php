<table>
    <thead>
    <tr>
        <td colspan="18"
            style="text-align: center; font-weight: bold; font-size: 20px; height: 35px">{{ factoryName() }}</td>
    </tr>
    <tr>
        <td colspan="18"
            style="text-align: center;height: 35px">
            <strong>Sample Summary Report</strong>
        </td>
    </tr>
    </thead>
</table>
<br>
@includeIf("merchandising::reports.sample_summary_report.table")
