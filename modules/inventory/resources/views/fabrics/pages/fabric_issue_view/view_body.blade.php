<style>
    .table-border th,
    .table-border td {
        padding: 3px;
        text-align: left;
        border: 1px solid black;
    }

    .table-border th.center,
    .table-border td.center {
        text-align: center;
    }

    .table-border th.right,
    .table-border td.right {
        text-align: right;
    }

    .table-border th.no-border-top,
    .table-border td.no-border-top {
        border-bottom-color: transparent !important;
    }

    .table-border th.no-borer-right,
    .table-border td.no-borer-right {
        border-right-color: transparent !important;
    }

    .bottom-info {
        min-width: 100px;
        display: inline-block;
    }

    #factoryName {
        display: none;
    }

    #logo {
        height: 60px;
        margin: 25px auto 0;
    }

    #factoryAddress {
        margin: 0;
        display: block;
    }
</style>
<div class="body-section" style="margin-top: 0px;">
    <table class="no-border">
        <tr>
            <th>Challan No:</th>
            <td>{{ $issue->challan_no }}</td>
            <th>Date:</th>
            @if(!$type == 'excel')
            <td>{{ $issue->issue_date ? \Carbon\Carbon::make($issue->issue_date)->format('d-m-Y') : null }}</td>
            <td style="width: 30%;border-top: 1px solid white;border-bottom: 1px solid white;border-right: 1px solid white;" rowspan="4">
                <center>
                    <p>{{ $issue->issue_no }}</p>
                    {!! DNS1D::getBarcodeSVG(($issue->issue_no), "C128A", 1, 16, '', false) !!}
                </center>
            </td>
            @endif
        </tr>
        <tr>
            <th>Party Name:</th>
            <td> {{ $issue->serviceCompany->name }} </td>
            <th>Vehicle</th>
            <td>{{ $issue->vehicle }}</td>
        </tr>
        <tr>
            <th>Address:</th>
            <td>{{ $issue->serviceCompany->address }}</td>
            <th>Lock No:</th>
            <td>{{ $issue->lock_no }}</td>
        </tr>
        <tr>
            <th></th>
            <td></td>
            <th>Driver Name</th>
            <td>{{ $issue->driver_name }}</td>
        </tr>
    </table>
    <table class="reportTable" style="margin-top:12px;">
        <thead>
        <tr>
            <th>Sl No.</th>
            <th>Store</th>
            <th>Buyer</th>
            <th>Style No/Order No</th>
            <th>PO NO</th>
            <th>Item Name</th>
            <th>Batch No.</th>
            <th>Description</th>
            <th>F/Type</th>
            <th>Color</th>
            <th>DIA</th>
            <th>GSM</th>
            <th>No Of Roll</th>
            <th>Finish/Qty</th>
            <th>UOM</th>
            <th>Remarks</th>
        </tr>
        </thead>
        <tbody>
        @if($issue->details->count())
            @foreach($issue->details as $key => $detail)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $detail->store->name }}</td>
                    @if($detail->issue->service_source == 'in_house')
                        <td>{{ $detail->issue->buyer->name }}</td>
                    @elseif($detail->issue->service_source == 'out_bound')
                        <td>{{ $detail->issue->outbound_buyer_name }}</td>
                    @else
                        <td>{{ $detail->issue->buyer->name }}</td>
                    @endif
                    <td>{{ $detail->style_name }}</td>
                    <td style="word-break: break-word;width: 25%;">{{ $detail->po_no }}</td>
                    <td>{{ $detail->gmtsItem->name }}</td>
                    <td>{{ $detail->batch_no }}</td>
                    <td>{{ $detail->fabric_composition_value }}</td>
                    <td>{{ $detail->construction }}</td>
                    <td>{{ $detail->color->name }}</td>
                    <td>{{ $detail->ac_dia }}</td>
                    <td>{{ $detail->ac_gsm }}</td>
                    <td class="text-right">{{ $detail->no_of_roll }}</td>
                    <td>{{ number_format($detail->issue_qty, 2) }}</td>
                    @if($detail->receive->receive_basis === 'independent')
                        <td>{{ optional($detail->uom)->unit_of_measurement  }}</td>
                    @else
                        <td>{{ $uomService[$detail->uom_id] ?? 'kg'  }}</td>
                    @endif
                    <td>{{ $detail->remarks }}</td>
                </tr>
            @endforeach
            <tr>
                @php
                    $totalAmount = $issue->details
                    ->pluck('amount')
                    ->map(function($amount){
                        return (float)str_replace(",", "", $amount);
                        })->sum()
                @endphp
                <td colspan="11"><b>Total</b></td>
                <td></td>
                <td class="text-right"><b>{{ number_format($issue->details->sum('no_of_roll'), 2) }}</b></td>
                <td class="text-right"><b>{{ number_format($issue->details->sum('issue_qty'), 2) }}</b></td>
                {{-- <td></td>
                <td class="text-right"><b>{{ number_format($totalAmount,2) }}</b></td>
                <td></td>
                <td></td> --}}
                <td></td>
                <td></td>
            </tr>
        @else
            <tr>
                <td colspan="16">No Data Available</td>
            </tr>
        @endif
        </tbody>

    </table>
</div>

<div class="signature">
    <table class="borderless" style="margin-top: 4%;">
        <tbody>
        <tr>
            <td colspan="4" class="text-center"><u> <b>Prepared By</b> </u></td>
            <td colspan="3" class='text-center'><u> <b>Received Sign</b> </u></td>
            <td colspan="4" class="text-center"><u> <b>Store Officer</b> </u></td>
            <td colspan="4" class="text-center"><u> <b>Authorized Sign</b> </u></td>
        </tr>
        </tbody>
    </table>
</div>
