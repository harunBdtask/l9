<div>
    <table>
        <tr>
            <td colspan="15" style="background-color: aliceblue; text-align: center;"><b>{{ factoryName() }}</b></td>
        </tr>
        <tr>
            <td colspan="15" style="background-color: aliceblue; text-align: center;"><b>{{ factoryAddress() }}</b></td>
        </tr>
        <tr>
            <td colspan="15" style="background-color: aliceblue; text-align: center;">DHU Report</td>
        </tr>
    </table>
</div>
@include('tqm::reports.dhu-report.pdf-table')
