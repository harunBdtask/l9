<table>
    <thead>
    <tr>
        <th>Buyer</th>
        <th>Style/Order</th>
        <th>Order/Style</th>
        <th>PO</th>
        <th>Order Qty</th>
        <th>Cutt. Production</th>
        <th>WIP In Cutt./Pt./Embr.</th>
        <th>Today's Input</th>
        <th>Total Input</th>
        <th>Today's Output</th>
        <th>Total Output</th>
        <th>Sewing Rejection</th>
        <th>Total Rejection</th>
        <th>In_line WIP</th>
        <th>Cut 2 Sewing Ratio (%)</th>
    </tr>
    </thead>
    <tbody>
    @include('sewingdroplets::reports.includes.buyer_wise_report_inc_download')
    </tbody>
</table>
