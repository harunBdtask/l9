<!DOCTYPE html>

<html>
<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
<h4 align="center">Date Wise Print Send Receive Report 
    <small class="text-muted text-center">(From {{ date("jS F, Y", strtotime($from_date)) }} to {{ date("jS F, Y", strtotime($to_date)) }})</small>
</h4>
    @include('printembrdroplets::reports.tables.date_wise_report_modify_table_for_email')
    {{-- OLD CODE
    <table class="reportTable">
        <thead>
        <tr>
            <th colspan="4">Section-1: PO Wise</th>
        </tr>
        <tr>
            <th>Buyer</th>
            <th>Style</th>
            <th>PO</th>
            <th>Send Quantity</th>
        </tr>
        </thead>
        <tbody class="color-wise-report">
        @if(!empty($po_wise_print_summary))
            @php $tsend_qty_order = 0; @endphp
            @foreach($po_wise_print_summary['print_details'] as $order_wise)
                @php
                    $tsend_qty_order += $order_wise['send_qty'];
                @endphp
                <tr>
                    <td>{{ $order_wise['buyer_name'] }}</td>
                    <td>{{ $order_wise['style_name'] }}</td>
                    <td>{{ $order_wise['order_no'] }}</td>
                    <td>{{ $order_wise['send_qty'] }}</td>
                </tr>
            @endforeach
            <tr style="font-weight: bold">
                <td colspan="3">Total</td>
                <td>{{ $tsend_qty_order }}</td>
            </tr>
        @else
            <tr>
                <td colspan="4" class="text-danger text-center">Not found<td>
            </tr>
        @endif
        </tbody>
    </table>

    <table class="reportTable">
        <thead>
        <tr>
            <th colspan="5">Section-2: Color Wise</th>
        </tr>
        <tr>
            <th>Buyer</th>
            <th>Style</th>
            <th>PO</th>
            <th>Colour Name</th>
            <th>Send Quantity</th>
        </tr>
        </thead>
        <tbody class="color-wise-report">
        @if(!empty($color_wise_print_summary))
            @php $tsend_qty_color = 0; @endphp
            @foreach($color_wise_print_summary['print_details'] as $report)
                @php
                    $tsend_qty_color += $report['send_qty'];
                @endphp
                <tr>
                    <td>{{ $report['buyer_name'] }}</td>
                    <td>{{ $report['style_name'] }}</td>
                    <td>{{ $report['order_no'] }}</td>
                    <td>{{ $report['color'] }}</td>
                    <td>{{ $report['send_qty'] }}</td>
                </tr>
            @endforeach
            <tr style="font-weight: bold">
                <td colspan="4">Total</td>
                <td>{{ $tsend_qty_color }}</td>
            </tr>
        @else
            <tr>
                <td colspan="5" class="text-danger text-center">Not found<td>
            </tr>
        @endif
        </tbody>
    </table>

    <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
    <thead>
    <tr>
        <th colspan="5" style="text-align: center">Section-3: Challan Wise</th>
    </tr>
    <tr style="text-align: center">
        <th>Challan</th>
        <th>Factory Name</th>
        <th>Part</th>
        <th>Bag (S)</th>
        <th>Quantity</th>
    </tr>
    </thead>
    <tbody >
    @if(count($challan_wise) > 0)
        @php $tsend_qtyy = 0; @endphp
        @foreach($challan_wise as $challan)
            @php
                $send_qtyy = 0;
                foreach($challan->print_inventory as $print_inv){
                   $send_qtyy += $print_inv->bundle_card->quantity;
                }
                $tsend_qtyy += $send_qtyy;
            @endphp
            <tr style="text-align: center">
                <td>{{ $challan->challan_no }}</td>
                <td>{{ $challan->factory->factory_name }}</td>
                <td>{{ $challan->part->name ?? '' }}</td>
                <td>{{ $challan->bag }}</td>
                <td>{{ $send_qtyy }}</td>
            </tr>
        @endforeach
        <tr style="font-weight: bold;">
            <td colspan="4" style="text-align: center">Total</td>
            <td style="text-align: center">{{ $tsend_qtyy }}</td>
        </tr>
    @else
        <tr>
            <td colspan="5" style="text-align: center;font-weight: bold;">Not found<td>
        </tr>
    @endif
    </tbody>
</table>

    <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
    <thead>
    <tr>
        <th colspan="3" style="text-align: center">Section-3: Factory Wise</th>
    </tr>
    <tr style="text-align: center">
        <th>Factory Name</th>
        <th>Factory Address</th>
        <th>Quantity</th>
    </tr>
    </thead>
    <tbody>
    @if(!empty($factory_wise_report))
        @php $tsend_qty_factory = 0; @endphp
        @foreach($factory_wise_report as $factory)
            @php $tsend_qty_factory += $factory['send_qty_factory']; @endphp
            <tr style="text-align: center">
                <td>{{ $factory['factory_name'] }}</td>
                <td>{{ $factory['factory_address'] }}</td>
                <td>{{ $factory['send_qty_factory'] }}</td>
            </tr>
        @endforeach
        <tr style="font-weight: bold;">
            <td colspan="2" style="text-align: center">Total</td>
            <td style="text-align: center">{{ $tsend_qty_factory }}</td>
        </tr>
    @else
        <tr>
            <td colspan="3" style="text-align: center;font-weight: bold;">Not found<td>
        </tr>
    @endif
    </tbody>
</table>
    --}}

</main>
@include('reports.downloads.includes.pdf-footer')
</body>
</html>