<table>
    <thead>
    <tr>
        <th>Buyer</th>
        <th>Booking No</th>
        <th>Style/Order No</th>
        <th>PO</th>
        <th>Order Quantity</th>
        <th>Cutting Production</th>
        <th>Cutting WIP</th>
        <th>Total Send</th>
        <th>Total Recieved</th>
        <th>Fabric Rejection</th>
        <th>Print Rejection</th>
        <th>Print WIP/Short</th>
    </tr>
    </thead>
    <tbody>
    @include('printembrdroplets::reports.includes.buyer_wise_report_inc_download')
    </tbody>
</table>