<div>
    <table>
        <tr>
            <td colspan="17" style="background-color: aliceblue;"> {{sessionFactoryName() }}</td>
        </tr>
        <tr>
            <td colspan="17" style="background-color: aliceblue;">{{sessionFactoryAddress()}}</td>
        </tr>
        <tr>
            <td colspan="17" style="background-color: aliceblue; text-align: center;">{{"Buyer-PO List"}}</td>
        </tr>
    </table>
</div>
@includeIf('merchandising::reports.includes.buyer_season_order_report_table')
