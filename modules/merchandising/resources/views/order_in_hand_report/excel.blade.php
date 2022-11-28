<div>
    <table>
        <tr>
            <td colspan="12" style="background-color: aliceblue;text-align: center;"><b>{{ factoryName() }}</b></td>
        </tr>
        <tr>
            <td colspan="12" style="background-color: aliceblue;text-align: center;"><b>{{ factoryAddress() }}</b></td>
        </tr>
        <tr>
            <td colspan="12" style="background-color: aliceblue; text-align: center;"><b>Order In Hand</b></td>
        </tr>
    </table>
</div>
@includeIf('merchandising::order_in_hand_report.table')
