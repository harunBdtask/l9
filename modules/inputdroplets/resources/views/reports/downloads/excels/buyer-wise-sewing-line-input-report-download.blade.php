<table>
    <thead>
    <tr>
        <th>Buyer</th>
        <th>Booking No</th>
        <th>Style</th>
        <th>Order</th>
        <th>Order Quantity</th>
        <th>Cutting Production</th>
        <th>Cutting WIP</th>
        <th>Print Send</th>
        <th>Print Recieve</th>
        <th>Total Print Reject</th>
        <th>Print/Embr.WIP</th>
        <th>Current Cutting Inventory</th>
        <th>Today's Input Qty</th>
        <th>Total Input Qty</th>
    </tr>
    </thead>
    <tbody>
    @include('inputdroplets::reports.includes.buyer_wise_report_inc_download')
    </tbody>
</table>