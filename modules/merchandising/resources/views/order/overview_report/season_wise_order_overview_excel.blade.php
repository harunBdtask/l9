<div>
    <table border="1px solid">
        <thead >
        <tr ><td class="text-center" colspan="3" style="background-color: aliceblue; height: 30px;">{{ factoryName() }}</td></tr>
        <tr ><td class="text-center" colspan="3" style="background-color: aliceblue; height: 20px;"> {{ factoryAddress() }}</td></tr>
        <tr></tr>
        <tr ><td class="text-center" colspan="3" style="background-color: aliceblue; height: 20px;"> Season Wise Order Overview </td></tr>
        </thead>
    </table>
    @includeIf('merchandising::order.overview_report.season_wise_order_overview_table')
</div>
