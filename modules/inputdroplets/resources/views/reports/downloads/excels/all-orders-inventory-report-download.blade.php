<table>
    <thead>
    <tr>
        <th>Buyer</th>
        <th>Style</th>
        <th>Order No.</th>
        <th>Order Ouantity</th>
        <th>Days to Go Shipment</th>
        <th>Cutting Production</th>
        <th>Cutting/ Printing WIP</th>
        <th>Current Cutting Inventory</th>
        <th>Todays Input</th>
        <th>Total Sewing Input</th>
    </tr>
    </thead>
    <tbody>
    @include('inputdroplets::reports.tables.order_wise_cutting_inventory_summary_table')
    </tbody>
</table>