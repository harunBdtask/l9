<div class="row">
    <div style="width: 50%">
        <table class="borderless">
            <tbody>
            <tr>
                <td><b>Item Name :</b></td>
                <td>{{ $itemName ?? '' }}</td>
            </tr>
            <tr>
                <td><b>Highest Price :</b></td>
                <td>${{ $highestPrice ?? '' }}</td>
            </tr>
            <tr>
                <td><b>Lowest Price :</b></td>
                <td>${{ $lowestPrice ?? '' }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<table class="reportTable m-t-1">
    <thead>
    <tr style="background-color: aliceblue">
        <th>Buyer</th>
        <th>Style</th>
        <th>Supplier</th>
        <th>Work Order Number</th>
        <th>Order Quantity</th>
        <th>Unit Price</th>
        <th>Price Increased %</th>
        <th>We Can Save</th>
    </tr>
    </thead>

    <tbody>
    @forelse($data as $value)
        <tr>
            <td>{{ $value['buyer_name'] }}</td>
            <td>{{ $value['style_name'] }}</td>
            <td>{{ $value['supplier_name'] }}</td>
            <td>{{ $value['work_order_no'] }}</td>
            <td>{{ $value['total_order_qty'] }}</td>
            <td>${{ $value['unit_price'] }}</td>
            <td>{{ $loop->first ? 'lowest price' : $value['price_increased'].'%' }}</td>
            <td>{{ $loop->first ? 'lowest price' : '$'.number_format($value['we_can_save'], 2) }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="8">No data found</td>
        </tr>
    @endforelse
    <tr>
        <td colspan="7" style="text-align: right;">
            <b>Total: </b>
        </td>
        <td>
            <b>${{ number_format($totalSave, 2) ?? 0 }}</b>
        </td>
    </tr>
    </tbody>
</table>
