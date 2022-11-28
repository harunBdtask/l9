<!DOCTYPE html>

<html>
<head>
    <title>PO Wise Recap Report</title>
    @include('merchandising::reports.downloads.includes.pdf_style')
    <style>
        table {
            border-collapse: collapse;
        }
    </style>
</head>

<body>
@include('merchandising::download.include.report-header')
@include('merchandising::download.include.report-footer')
<main>
    <h4 align="center">PO Wise Recap Report</h4>
    @if(isset($recap_report) && $recap_report->count() > 0)
        <table class="reportTable table-bordered" id="fixTable">
            <thead>
            <tr>
                <th class="stay-top">Buyer</th>
                <th class="stay-top">Booking No</th>
                <th class="stay-top">Style</th>
                <th class="stay-top">PO / Order No</th>
                <th class="stay-top">Fabrication</th>
                <th class="stay-top">Fab (special)</th>
                <th class="stay-top">GSM</th>
                <th class="stay-top">Item</th>
                <th class="stay-top">T-shirt</th>
                <th class="stay-top">Polo</th>
                <th class="stay-top">Pant</th>
                <th class="stay-top">Intimates</th>
                <th class="stay-top">Others</th>
                <th class="stay-top">O/QTY</th>
                <th class="stay-top">Unit Price</th>
                <th class="stay-top">Total Value</th>
                <th class="stay-top">CM (DZN)</th>
                <th class="stay-top">Shipment Date</th>
                <th class="stay-top">Print</th>
                <th class="stay-top">EMB</th>
                <th class="stay-top">FAC</th>
                <th class="stay-top">P.P</th>
                <th class="stay-top">Fac</th>
                <th class="stay-top">Remarks</th>
            </tr>
            </thead>
            <tbody>
            @php
                $last_buyer_id = '';
                $last_booking_no = '';
                $last_style = '';
                $po_no = '';
                $i = 0;
                $sub_total_tshirt = 0;
                $sub_total_polo = 0;
                $sub_total_pant = 0;
                $sub_total_intimate = 0;
                $sub_total_others = 0;
                $sub_total_po_qty = 0;
                $sub_total_value = 0;

                $grand_total_tshirt = 0;
                $grand_total_polo  = 0;
                $grand_total_pant  = 0;
                $grand_total_intimate  = 0;
                $grand_total_others  = 0;
                $grand_total_po_qty  = 0;
                $grand_total_value  = 0;

            @endphp
            @foreach($recap_report as $key => $value)
                @php
                    $i++;
                    $sub_total_tshirt +=$value->t_shirt;
                    $sub_total_polo +=$value->polo;
                    $sub_total_pant +=$value->pant;
                    $sub_total_intimate +=$value->intimate;
                    $sub_total_others +=$value->others;
                    $sub_total_value +=$value->total_value;

                    $grand_total_tshirt +=$value->t_shirt;
                    $grand_total_polo +=$value->polo;
                    $grand_total_pant +=$value->pant;
                    $grand_total_intimate +=$value->intimate;
                    $grand_total_others +=$value->others;
                    $grand_total_value +=$value->total_value;

                    $buyer_count  = $recap_report->where('buyer',$value->buyer)->count();
                @endphp
                <tr>
                    <td>{{$value->buyers->name}}</td>
                    <td>{{$value->booking_no}}</td>
                    <td>{{$value->order_style_no}}</td>
                    <td>{{$value->po_no}}</td>
                    <td>{{$value->fabrication}}</td>
                    <td></td>
                    <td>{{$value->gsm}}</td>
                    <td>{{$value->item_data->item_name}}</td>
                    <td>{{$value->t_shirt}}</td>
                    <td>{{$value->polo}}</td>
                    <td>{{$value->pant}}</td>
                    <td>{{$value->intimate}}</td>
                    <td>{{$value->others}}</td>
                    @if( $po_no != $value->po_no)
                        @php $sub_total_po_qty += $value->order_qty;$grand_total_po_qty += $value->order_qty @endphp
                    @endif
                    <td>{{$value->order_qty}}</td>
                    <td>{{$value->unit_price}}</td>
                    <td>{{$value->total_value}}</td>
                    <td>{{$value->cm}}</td>
                    <td>{{date('d M Y',strtotime($value->shipment_date))}}</td>
                    <td>{{$value->print ?? "N/A"}}</td>
                    <td>{{$value->emb ?? "N/A"}}</td>
                    <td>{{$value->fac ? 'Yes' : 'No'}}</td>
                    <td>{{$value->pp ? 'Yes' : 'No'}}</td>
                    <td>{{$value->fac}}</td>
                    <td>{{$value->remarks}}</td>
                </tr>
                @if($buyer_count == $i)
                    <tr style="background: #60a7f7;">
                        <td colspan="8" style="font-weight: bold">Sub Total</td>
                        <td><b>{{$sub_total_tshirt}}</b></td>
                        <td><b>{{$sub_total_polo}}</b></td>
                        <td><b>{{$sub_total_pant}}</b></td>
                        <td><b>{{$sub_total_intimate}}</b></td>
                        <td><b>{{$sub_total_others}}</b></td>
                        <td><b>{{$sub_total_po_qty}}</b></td>
                        <td></td>
                        <td><b>{{$sub_total_value}}</b></td>
                        <td colspan="8"></td>
                    </tr>
                    @php
                        $i = 0;
                        $sub_total_tshirt = 0;
                        $sub_total_polo = 0;
                        $sub_total_pant = 0;
                        $sub_total_intimate = 0;
                        $sub_total_others = 0;
                        $sub_total_po_qty = 0;
                        $sub_total_value = 0;
                    @endphp
                @endif
                @php
                    $last_buyer_id = $value->buyer;
                    $last_booking_no = $value->booking_no;
                    $last_style = $value->order_style_no;
                    $po_no = $value->po_no;
                @endphp
            @endforeach
            <tr style="background: yellow;">
                <td colspan="8" style="font-weight: bold">Total</td>
                <td><b>{{$grand_total_tshirt}}</b></td>
                <td><b>{{$grand_total_polo}}</b></td>
                <td><b>{{$grand_total_pant}}</b></td>
                <td><b>{{$grand_total_intimate}}</b></td>
                <td><b>{{$grand_total_others}}</b></td>
                <td><b>{{$grand_total_po_qty}}</b></td>
                <td></td>
                <td><b>{{$grand_total_value}}</b></td>
                <td colspan="8"></td>
            </tr>
            </tbody>
        </table>
    @endif
</main>
</body>
</html>