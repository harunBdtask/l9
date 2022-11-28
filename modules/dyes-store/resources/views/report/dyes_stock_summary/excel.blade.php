<div>
    <table>
        <tr>
            <td colspan="6" style="background-color: aliceblue;text-align: center;"><strong>{{ factoryName() }}</strong>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: aliceblue;text-align: center;">
                <strong>{{ factoryAddress() }}</strong></td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: aliceblue; text-align: center;"><strong>Dyes Store Stock Summary
                    Report</strong></td>
        </tr>
    </table>
</div>
@include('dyes-store::report.dyes_stock_summary.table')
