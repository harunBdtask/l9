<div class="row m-t">
    <div class="col-sm-12">
        <table class="reportTable">
            <thead>
            <tr>
                <th colspan="9">Section-1: PO Wise</th>
            </tr>
            <tr>
                <th>SL</th>
                <th>Factory Name</th>
                <th>Buyer</th>
                <th>Style/Order No</th>
                <th>PO</th>
                <th>Print Send Qty.</th>
                <th>Print Rec. Qty.</th>
                <th>Emb. Send Qty.</th>
                <th>Emb. Rec. Qty.</th>
            </tr>
            </thead>
            <tbody>
            @forelse(collect($data)->groupBy('purchase_order_id')->values() as $key => $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ collect($item)->first()['factory']['factory_name'] ?? 'N/A' }}</td>
                    <td>{{ collect($item)->first()['buyer']['name'] ?? 'N/A' }}</td>
                    <td>{{ collect($item)->first()['order']['style_name'] ?? 'N/A' }}</td>
                    <td>{{ collect($item)->first()['purchaseOrder']['po_no'] ?? 'N/A' }}</td>
                    <td>{{ collect($item)->sum('print_sent_qty') }}</td>
                    <td>{{ collect($item)->sum('print_receive_qty') }}</td>
                    <td>{{ collect($item)->sum('embroidery_sent_qty') }}</td>
                    <td>{{ collect($item)->sum('embroidery_receive_qty') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">No Data Found</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="row m-t">
    <div class="col-sm-12">
        <table class="reportTable">
            <thead>
            <tr>
                <th colspan="10">Section-2: Color Wise</th>
            </tr>
            <tr>
                <th>SL</th>
                <th>Factory Name</th>
                <th>Buyer</th>
                <th>Style/Order No</th>
                <th>PO</th>
                <th>Color</th>
                <th>Print Send Qty.</th>
                <th>Print Rec. Qty.</th>
                <th>Emb. Send Qty.</th>
                <th>Emb. Rec. Qty.</th>
            </tr>
            </thead>
            <tbody>
            @forelse(collect($data)->groupBy(['purchase_order_id', 'color_id'])->values() as $itemAsPO)
                @foreach($itemAsPO as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ collect($item)->first()['factory']['factory_name'] ?? 'N/A' }}</td>
                        <td>{{ collect($item)->first()['buyer']['name'] ?? 'N/A' }}</td>
                        <td>{{ collect($item)->first()['order']['style_name'] ?? 'N/A' }}</td>
                        <td>{{ collect($item)->first()['purchaseOrder']['po_no'] ?? 'N/A' }}</td>
                        <td>{{ collect($item)->first()['color']['name'] ?? 'N/A' }}</td>
                        <td>{{ collect($item)->sum('print_sent_qty') }}</td>
                        <td>{{ collect($item)->sum('print_receive_qty') }}</td>
                        <td>{{ collect($item)->sum('embroidery_sent_qty') }}</td>
                        <td>{{ collect($item)->sum('embroidery_receive_qty') }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="10">No Data Found</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="row m-t">
    <div class="col-sm-12">
        <table class="reportTable">
            <thead>
            <tr>
                <th colspan="6">Section-3: Factory Wise</th>
            </tr>
            <tr>
                <th>Factory Name</th>
                <th>Factory Address</th>
                <th>Print Send Qty.</th>
                <th>Print Rec. Qty.</th>
                <th>Emb. Send Qty.</th>
                <th>Emb. Rec. Qty.</th>
            </tr>
            </thead>
            <tbody>
            @if(count($data))
                <tr>
                    <td>{{ collect($data)->first()['factory']['factory_name'] ?? 'N/A' }}</td>
                    <td>{{ collect($data)->first()['factory']['factory_address'] ?? 'N/A' }}</td>
                    <td>{{ collect($data)->sum('print_sent_qty') }}</td>
                    <td>{{ collect($data)->sum('print_receive_qty') }}</td>
                    <td>{{ collect($data)->sum('embroidery_sent_qty') }}</td>
                    <td>{{ collect($data)->sum('embroidery_receive_qty') }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="6">No Data Found</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
