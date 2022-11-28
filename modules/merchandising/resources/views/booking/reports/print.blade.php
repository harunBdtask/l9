<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Budget</title>


    <link rel="stylesheet" href="{{ asset('modules/skeleton/css/print.css') }}" type="text/css" />

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />

    <style type="text/css">
        .v-align-top td,
        .v-algin-top th {
            vertical-align: top;
        }

        body {
            width: 100%;
            height: 100%;
            background-color: #FAFAFA;
            font: 9pt "Tahoma";
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .page {
            /*width: 210mm;*/
            min-height: 297mm;
            margin: 5mm auto;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .header-section {
            padding: 10px;
        }

        .body-section {
            padding: 10px;
            padding-top: 0px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 3px;
            padding-bottom: 3px;
        }

        table.borderless {
            border: none;
        }

        .borderless td,
        .borderless th {
            border: none;
        }

        @page {
            size: A4 landscape;
            margin: 5mm;
            margin-left: 15mm;
            margin-right: 15mm;
        }

        @media print {

            html,
            body {
                size: A4 landscape !important;
                /*width: 210mm;*/
                /*height: 293mm;*/
            }

            table,
            pre,
            blockquote {
                page-break-inside: avoid;
            }


            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        }

        .footer {
            position: fixed;
            bottom: 10px;
            padding-left: 30%;
        }
    </style>
</head>

<body>
    <main>
        <div class="page">
            <div class="view-body">
                @include('merchandising::booking.reports.view-body')
            </div>
            @include('skeleton::reports.downloads.footer')
            {{-- <div class="">--}}
            {{-- <div class="header-section" style="padding-bottom: 0px;">--}}
            {{-- </div>--}}
            {{-- <center>--}}
            {{-- <table style="border: 1px solid black;width: 20%;">--}}
            {{-- <thead>--}}
            {{-- <tr>--}}
            {{-- <td class="text-center">--}}
            {{-- <span--}}
            {{-- style="font-size: 12pt; font-weight: bold;">{{ $type == 'short' ? 'Short Trims Bookings' : 'Trims Bookings' }}</span>--}}
            {{-- <br>--}}
            {{-- </td>--}}
            {{-- </tr>--}}
            {{-- </thead>--}}
            {{-- </table>--}}
            {{-- </center>--}}
            {{-- <br>--}}

            {{-- <div class="body-section" style="margin-top: 0px;">--}}

            {{-- <table class="borderless">--}}
            {{-- <thead>--}}
            {{-- <tr>--}}
            {{-- <td class="text-left">--}}
            {{-- <span style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span><br>--}}
            {{-- {{ factoryAddress() }}--}}
            {{-- <br>--}}
            {{-- </td>--}}
            {{-- <td class="text-right">--}}
            {{-- Booking No: <b> {{ $trimsBookings->unique_id ?? '' }}</b><br>--}}
            {{-- Booking Date: <b> {{ $trimsBookings->booking_date ?? ''}}</b><br>--}}
            {{-- </td>--}}
            {{-- </tr>--}}
            {{-- </thead>--}}
            {{-- </table>--}}
            {{-- <hr>--}}

            {{-- <table class="borderless">--}}
            {{-- <td><span><b>TO</b></span></td>--}}
            {{-- <tr>--}}
            {{-- <th>Supplier Name</th>--}}
            {{-- <td>{{ $trimsBookings ? optional($trimsBookings->supplier)->name : ''  }}</td>--}}
            {{-- <th >Booking Amount:</th>--}}
            {{-- <td >{{ $totalAmount ?? '' }} {{ $trimsBookings->currency ?? '' }}</td>--}}
            {{-- </tr>--}}

            {{-- <tr>--}}
            {{-- <th >Address:</th>--}}
            {{-- <td >{{ $trimsBookings->address ?? '' }} </td>--}}
            {{-- <th >Season:</th>--}}
            {{-- <td >{{ $trimsBookings->season ?? ''}}</td>--}}
            {{-- </tr>--}}

            {{-- <tr>--}}
            {{-- <th >Attention:</th>--}}
            {{-- <td >{{ $trimsBookings->attention ?? ''}}</td>--}}
            {{-- <th >Delivery Date:</th>--}}
            {{-- <td >{{  $trimsBookings->delivery_date ?? '' }}</td>--}}
            {{-- </tr>--}}

            {{-- <tr>--}}
            {{-- <th >Dealing Merchant:</th>--}}
            {{-- <td >{{ $trimsBookings->dealing_merchant ?? ''}}</td>--}}
            {{-- <th >Remarks:</th>--}}
            {{-- <td >{{ $trimsBookings->remarks ?? ''}}</td>--}}
            {{-- </tr>--}}

            {{-- </table>--}}

            {{-- --}}{{-- no sensitivity--}}

            {{-- @if( count($trimsBookingsDetailsNoSensitivity) >= 1 )--}}
            {{-- <div style="margin-top: 15px;">--}}
            {{-- <table>--}}
            {{-- @foreach((collect($trimsBookingsDetailsNoSensitivity)->flatten(1))->groupBy('style_name') as $style => $trimsDetails)--}}
            {{-- <tr style="border:none">--}}
            {{-- <td colspan="7">--}}
            {{-- No Sensitive (Unique Id):--}}
            {{-- {{ ($trimsDetails->unique('budget_unique_id')->pluck('budget_unique_id'))->join(', ')  }}--}}
            {{-- Style:{{ ($trimsDetails->unique('style_name')->pluck('style_name'))->join(', ')  }}--}}
            {{-- Po--}}
            {{-- Qty:{{ collect($trimsDetails->unique('total_qty'))->pluck('total_qty')->sum() }}--}}
            {{-- </td>--}}
            {{-- <td colspan="8">--}}
            {{-- Po No:--}}
            {{-- {{ ($trimsDetails->unique('po_no')->pluck('po_no'))->join(', ') }}--}}
            {{-- </td>--}}
            {{-- </tr>--}}

            {{-- <tr>--}}
            {{-- <th>SL</th>--}}
            {{-- <th>Item Group</th>--}}
            {{-- <th>Item Description</th>--}}
            {{-- <th>Brand/Supplier Ref.</th>--}}
            {{-- <th>Gmts. Color</th>--}}
            {{-- <th>Gmts. Qty</th>--}}
            {{-- <th>Item Color</th>--}}
            {{-- <th>Actual Cons</th>--}}
            {{-- <th>Booking <br> Percentage(%)</th>--}}
            {{-- <th>Total Cons.</th>--}}
            {{-- <th>Qty</th>--}}
            {{-- <th>UOM</th>--}}
            {{-- <th>Rate</th>--}}
            {{-- <th>Amount</th>--}}
            {{-- <th>Remarks</th>--}}
            {{-- </tr>--}}
            {{-- @php--}}
            {{-- $index = 0;--}}
            {{-- $total = 0;--}}
            {{-- $totalQty = 0;--}}
            {{-- @endphp--}}
            {{-- @foreach($trimsDetails->groupBy('item_name') as $key => $bookingDetails)--}}
            {{-- @php--}}
            {{-- $index++;--}}
            {{-- $subTotal = 0;--}}
            {{-- $subQty = 0;--}}
            {{-- @endphp--}}
            {{-- @foreach($bookingDetails->pluck('details') as $item)--}}
            {{-- @if($item)--}}
            {{-- <tr>--}}
            {{-- @if($loop->first)--}}
            {{-- <td rowspan="{{ $bookingDetails->pluck('details')->count() }}">{{ $index }}</td>--}}
            {{-- <td rowspan="{{ $bookingDetails->pluck('details')->count() }}">{{ $key }}</td>--}}
            {{-- @endif--}}
            {{-- <td>{{ $item['item_description'] ?? '' }}</td>--}}
            {{-- <td>{{ $item['nominated_supplier'] ?? '' }}</td>--}}
            {{-- <td>{{ $item['color'] ?? '' }}</td>--}}
            {{-- <td>{{ $item['wo_qty'] ?? '' }}</td>--}}
            {{-- <td>{{ $item['item_color'] ?? '' }}</td>--}}
            {{-- <td>{{  $item['actual_cons'] ?? 0 }}</td>--}}
            {{-- <td>{{  $item['process_loss'] ?? 0 }}</td>--}}
            {{-- <td>{{  $item['total_cons'] ?? 0 }}</td>--}}
            {{-- <td>{{ $item['wo_total_qty'] ? round($item['wo_total_qty']) : 0}}</td>--}}
            {{-- <td>{{ $item['uom'] ?? ''}}</td>--}}
            {{-- <td>{{ $item['rate'] ?? '' }}</td>--}}
            {{-- @php--}}
            {{-- $total += $item['amount'] ?? 0;--}}
            {{-- $subTotal += $item['amount'] ?? 0;--}}
            {{-- $subQty +=  $item['wo_total_qty'] ?? 0;--}}
            {{-- $totalQty += $item['wo_total_qty'] ?? 0;--}}
            {{-- @endphp--}}
            {{-- <td>{{ $item['amount'] ?? '' }}</td>--}}
            {{-- <td>{{ '' }}</td>--}}
            {{-- </tr>--}}
            {{-- @else--}}
            {{-- <tr>--}}
            {{-- </tr>--}}
            {{-- @endif--}}
            {{-- @endforeach--}}
            {{-- <tr>--}}
            {{-- <th colspan="10" style="text-align: right">Sub Total</th>--}}
            {{-- <td>{{ round($totalQty) }}</td>--}}
            {{-- <td></td>--}}
            {{-- <td></td>--}}
            {{-- <td>{{ $total }}</td>--}}
            {{-- <td></td>--}}
            {{-- </tr>--}}
            {{-- @endforeach--}}
            {{-- <tr>--}}
            {{-- <th colspan="10" style="text-align: right">Grand Total</th>--}}
            {{-- <td>{{ round($totalQty) }}</td>--}}
            {{-- <td></td>--}}
            {{-- <td></td>--}}
            {{-- <td>{{ $total }}</td>--}}
            {{-- <td></td>--}}
            {{-- </tr>--}}
            {{-- @endforeach--}}
            {{-- </table>--}}

            {{-- </div>--}}
            {{-- @endif--}}
            {{-- --}}{{-- end no sensitivity--}}

            {{-- --}}{{-- start contrast color sensitivity--}}
            {{-- @if( count($trimsBookingsDetailsContrastColorSensitivity) >= 1 )--}}
            {{-- <div style="margin-top: 15px;">--}}
            {{-- <table>--}}
            {{-- @foreach(collect($trimsBookingsDetailsContrastColorSensitivity)->flatten(1)->groupBy('style_name') as $style => $trimsDetails)--}}
            {{-- <tr style="border:none">--}}
            {{-- <td colspan="7">--}}
            {{-- Contrast Color (Unique Id):--}}
            {{-- {{ ($trimsDetails->unique('budget_unique_id')->pluck('budget_unique_id'))->join(', ')  }}--}}
            {{-- Style:--}}
            {{-- {{ ($trimsDetails->unique('style_name')->pluck('style_name'))->join(', ')  }}--}}
            {{-- Po Qty:--}}
            {{-- {{ collect($trimsDetails->unique('total_qty'))->pluck('total_qty')->sum()}}--}}
            {{-- </td>--}}

            {{-- <td colspan="8">--}}
            {{-- Po No:--}}
            {{-- {{ ($trimsDetails->unique('po_no')->pluck('po_no'))->join(', ') }}--}}
            {{-- </td>--}}
            {{-- </tr>--}}

            {{-- <tr>--}}
            {{-- <th>SL</th>--}}
            {{-- <th>Item Group</th>--}}
            {{-- <th>Item Description</th>--}}
            {{-- <th>Brand/Supplier Ref.</th>--}}
            {{-- <th>Gmts. Color</th>--}}
            {{-- <th>Gmts. Qty</th>--}}
            {{-- <th>Item Color</th>--}}
            {{-- <th>Actual Cons.</th>--}}
            {{-- <th>Booking <br> Percentage(%)</th>--}}
            {{-- <th>Total Cons.</th>--}}
            {{-- <th>Qty</th>--}}
            {{-- <th>UOM</th>--}}
            {{-- <th>Rate</th>--}}
            {{-- <th>Amount</th>--}}
            {{-- <th>Remarks</th>--}}
            {{-- </tr>--}}
            {{-- @php--}}
            {{-- $index = 0;--}}
            {{-- $total = 0;--}}
            {{-- $totalQty = 0;--}}
            {{-- @endphp--}}

            {{-- @foreach($trimsDetails->groupBy('item_name') as $key => $bookingDetails)--}}
            {{-- @php--}}
            {{-- $index++;--}}
            {{-- $subTotal = 0;--}}
            {{-- $subQty = 0;--}}
            {{-- @endphp--}}
            {{-- @foreach($bookingDetails->pluck('details') as $item)--}}
            {{-- @if($item)--}}
            {{-- <tr>--}}
            {{-- @if($loop->first)--}}
            {{-- <td rowspan="{{ $bookingDetails->pluck('details')->count() }}">{{ $index }}</td>--}}
            {{-- <td rowspan="{{ $bookingDetails->pluck('details')->count() }}">{{ $key }}</td>--}}
            {{-- @endif--}}
            {{-- <td>{{ $item['item_description'] ?? '' }}</td>--}}
            {{-- <td>{{ $item['nominated_supplier'] ?? '' }}</td>--}}
            {{-- <td>{{ $item['color'] ?? '' }}</td>--}}
            {{-- <td>{{ $item['wo_qty'] ?? '' }}</td>--}}
            {{-- <td>{{ $item['item_color'] ?? '' }}</td>--}}
            {{-- <td>{{ $item['actual_cons'] }}</td>--}}
            {{-- <td>{{ $item['process_loss'] }}</td>--}}
            {{-- <td>{{ $item['total_cons'] }}</td>--}}
            {{-- <td>{{ $item['wo_total_qty'] ? round($item['wo_total_qty']) : 0}}</td>--}}
            {{-- <td>{{ $item['uom'] ?? ''}}</td>--}}
            {{-- <td>{{ $item['rate'] ?? '' }}</td>--}}
            {{-- @php--}}
            {{-- $total += $item['amount'] ?? 0;--}}
            {{-- $subTotal += $item['amount'] ?? 0;--}}
            {{-- $subQty +=  $item['wo_total_qty'] ?? 0;--}}
            {{-- $totalQty += $item['wo_total_qty'] ?? 0;--}}
            {{-- @endphp--}}
            {{-- <td>{{ $item['amount'] ?? '' }}</td>--}}
            {{-- <td>{{ '' }}</td>--}}
            {{-- </tr>--}}
            {{-- @else--}}
            {{-- <tr></tr>--}}
            {{-- @endif--}}
            {{-- @endforeach--}}
            {{-- <tr>--}}
            {{-- <th colspan="10" style="text-align: right">Sub Total</th>--}}
            {{-- <td>{{ round($subQty) }}</td>--}}
            {{-- <td/>--}}
            {{-- <td/>--}}
            {{-- <td>{{ $subTotal }}</td>--}}
            {{-- <td/>--}}
            {{-- </tr>--}}
            {{-- @endforeach--}}
            {{-- <tr>--}}
            {{-- <th colspan="10" style="text-align: right">Grand Total</th>--}}
            {{-- <td>{{ round($totalQty) }}</td>--}}
            {{-- <td/>--}}
            {{-- <td/>--}}
            {{-- <td>{{ $total }}</td>--}}
            {{-- <td/>--}}
            {{-- </tr>--}}
            {{-- @endforeach--}}

            {{-- </table>--}}
            {{-- </div>--}}
            {{-- @endif--}}
            {{-- --}}{{-- end contrast color sensitivity--}}

            {{-- --}}{{-- size sensitivity--}}
            {{-- @if(count($trimsBookingsDetailsSizeSensitivity) >= 1)--}}
            {{-- <div style="margin-top: 15px;">--}}
            {{-- <table>--}}
            {{-- @foreach(collect($trimsBookingsDetailsSizeSensitivity->flatten(1))->groupBy('style_name') as $style => $trimsDetails)--}}
            {{-- <tr style="border:none">--}}
            {{-- <td colspan="8">--}}
            {{-- Size Sensitivity(Unique Id):--}}
            {{-- {{ ($trimsDetails->unique('budget_unique_id')->pluck('budget_unique_id'))->join(', ')  }}--}}
            {{-- Style:--}}
            {{-- {{ ($trimsDetails->unique('style_name')->pluck('style_name'))->join(', ')  }}--}}
            {{-- Po Qty:--}}
            {{-- {{  collect($trimsDetails->unique('total_qty'))->pluck('total_qty')->sum() }}--}}
            {{-- </td>--}}

            {{-- <td colspan="9">--}}
            {{-- Po No:--}}
            {{-- {{ ($trimsDetails->unique('po_no')->pluck('po_no'))->join(', ') }}--}}
            {{-- </td>--}}
            {{-- </tr>--}}
            {{-- <tr>--}}
            {{-- <th>SL</th>--}}
            {{-- <th>Item Group</th>--}}
            {{-- <th>Item Description</th>--}}
            {{-- <th>Brand/Supplier Ref.</th>--}}
            {{-- <th>Gmts. Color</th>--}}
            {{-- <th>Gmts. Qty</th>--}}
            {{-- <th>Item Color</th>--}}
            {{-- <th>Item Sizes</th>--}}
            {{-- <th>Gmts Sizes</th>--}}
            {{-- <th>Actual Cons.</th>--}}
            {{-- <th>Booking <br> Percentage(%)</th>--}}
            {{-- <th>Total Cons.</th>--}}
            {{-- <th>WO Qty</th>--}}
            {{-- <th>UoM</th>--}}
            {{-- <th>Rate</th>--}}
            {{-- <th>Amount</th>--}}
            {{-- <th>Remarks</th>--}}
            {{-- </tr>--}}
            {{-- @php--}}
            {{-- $index = 0;--}}
            {{-- $total = 0;--}}
            {{-- $totalQty = 0;--}}
            {{-- @endphp--}}
            {{-- @foreach($trimsDetails->groupBy('item_name') as $key => $bookingDetails)--}}
            {{-- @php--}}
            {{-- $index++;--}}
            {{-- $subTotal = 0;--}}
            {{-- $subQty = 0;--}}
            {{-- @endphp--}}
            {{-- @foreach($bookingDetails->pluck('details') as $item)--}}
            {{-- @if($item)--}}
            {{-- <tr>--}}
            {{-- @if($loop->first)--}}
            {{-- <td rowspan="{{ $bookingDetails->pluck('details')->count() }}">{{ $index }}</td>--}}
            {{-- <td rowspan="{{ $bookingDetails->pluck('details')->count() }}">{{ $key }}</td>--}}
            {{-- @endif--}}
            {{-- <td>{{ $item['item_description'] ?? '' }}</td>--}}
            {{-- <td>{{ $item['nominated_supplier'] ?? '' }}</td>--}}
            {{-- <td>{{ $item['color'] ?? '' }}</td>--}}
            {{-- <td>{{ $item['wo_qty'] ?? '' }}</td>--}}
            {{-- <td>{{ $item['item_color'] ?? '' }}</td>--}}
            {{-- <td>{{ $item['item_size'] ?? '' }}</td>--}}
            {{-- <td>{{ $item['size'] ?? '' }}</td>--}}
            {{-- <td>{{  $item['actual_cons'] }}</td>--}}
            {{-- <td>{{  $item['process_loss'] }}</td>--}}
            {{-- <td>{{  $item['total_cons'] }}</td>--}}
            {{-- <td>{{ $item['wo_total_qty'] ? round($item['wo_total_qty']) : 0}}</td>--}}
            {{-- <td>{{ $item['uom'] ?? ''}}</td>--}}
            {{-- <td>{{ $item['rate'] ?? '' }}</td>--}}
            {{-- @php--}}
            {{-- $total += $item['amount'] ?? 0;--}}
            {{-- $subTotal += $item['amount'] ?? 0;--}}
            {{-- $subQty +=  $item['wo_total_qty'] ?? 0;--}}
            {{-- $totalQty += $item['wo_total_qty'] ?? 0;--}}
            {{-- @endphp--}}
            {{-- <td>{{ $item['amount'] ?? '' }}</td>--}}
            {{-- <td>{{ '' }}</td>--}}
            {{-- </tr>--}}
            {{-- @endif--}}
            {{-- @endforeach--}}
            {{-- <tr>--}}
            {{-- <th colspan="12" style="text-align: right">Sub Total</th>--}}
            {{-- <td>{{ round($subQty) }}</td>--}}
            {{-- <td/>--}}
            {{-- <td/>--}}
            {{-- <td>{{ $subTotal }}</td>--}}
            {{-- <td/>--}}
            {{-- </tr>--}}
            {{-- @endforeach--}}
            {{-- <tr>--}}
            {{-- <th colspan="12" style="text-align: right">Grand Total</th>--}}
            {{-- <td>{{ round($totalQty) }}</td>--}}
            {{-- <td/>--}}
            {{-- <td/>--}}
            {{-- <td>{{ $total }}</td>--}}
            {{-- <td/>--}}
            {{-- </tr>--}}
            {{-- @endforeach--}}

            {{-- </table>--}}
            {{-- </div>--}}
            {{-- @endif--}}
            {{-- --}}{{-- end size sensitivity--}}

            {{-- --}}{{-- color and size sensitivity--}}
            {{-- @if(count($trimsBookingsDetailsColorAndSizeSensitivity) >= 1)--}}
            {{-- <div style="margin-top: 15px;">--}}
            {{-- <table>--}}
            {{-- @foreach(collect($trimsBookingsDetailsColorAndSizeSensitivity)->flatten(1)->groupBy('style_name') as $style => $trimsDetails)--}}
            {{-- <tr style="border:none">--}}
            {{-- <td colspan="8">--}}
            {{-- Color & Size Sensitivity (Unique Id):--}}
            {{-- {{ ($trimsDetails->unique('budget_unique_id')->pluck('budget_unique_id'))->join(', ')  }}--}}
            {{-- Style:{{ ($trimsDetails->unique('style_name')->pluck('style_name'))->join(', ')  }}--}}
            {{-- Po Qty:{{ collect($trimsDetails->unique('total_qty'))->pluck('total_qty')->sum()  }}--}}
            {{-- </td>--}}
            {{-- <td colspan="9">--}}
            {{-- Po No:--}}
            {{-- {{ ($trimsDetails->unique('po_no')->pluck('po_no'))->join(', ') }}--}}
            {{-- </td>--}}
            {{-- </tr>--}}
            {{-- <tr>--}}
            {{-- <th>SL</th>--}}
            {{-- <th>Item Group</th>--}}
            {{-- <th>Item Description</th>--}}
            {{-- <th>Brand/Supplier Ref.</th>--}}
            {{-- <th>Gmts. Color</th>--}}
            {{-- <th>Gmts. Qty</th>--}}
            {{-- <th>Item Color</th>--}}
            {{-- <th>Item Sizes</th>--}}
            {{-- <th>Gmts Sizes</th>--}}
            {{-- <th>Actual Cons.</th>--}}
            {{-- <th>Booking <br> Percentage(%)</th>--}}
            {{-- <th>Total Cons.</th>--}}
            {{-- <th>WO Qty</th>--}}
            {{-- <th>UoM</th>--}}
            {{-- <th>Rate</th>--}}
            {{-- <th>Amount</th>--}}
            {{-- <th>Remarks</th>--}}
            {{-- </tr>--}}
            {{-- @php--}}
            {{-- $index = 0;--}}
            {{-- $total = 0;--}}
            {{-- $totalQty = 0;--}}
            {{-- @endphp--}}
            {{-- @foreach($trimsDetails->groupBy('item_name') as $key => $bookingDetails)--}}
            {{-- @php--}}
            {{-- $index++;--}}
            {{-- $subTotal = 0;--}}
            {{-- $subQty = 0;--}}
            {{-- $uom = $bookingDetails->unique('cons_uom_value')->pluck('cons_uom_value')->implode(',');--}}
            {{-- @endphp--}}
            {{-- @foreach($bookingDetails->pluck('details') as $item)--}}
            {{-- @if($item)--}}
            {{-- <tr>--}}
            {{-- @if($loop->first)--}}
            {{-- <td rowspan="{{ $bookingDetails->pluck('details')->count() }}">{{ $index }}</td>--}}
            {{-- <td rowspan="{{ $bookingDetails->pluck('details')->count() }}">{{ $key }}</td>--}}
            {{-- @endif--}}
            {{-- <td>{{ $item['item_description'] ?? '' }}</td>--}}
            {{-- <td>{{ $item['nominated_supplier'] ?? '' }}</td>--}}
            {{-- <td>{{ $item['color'] ?? '' }}</td>--}}
            {{-- <td>{{ $item['wo_qty'] ?? '' }}</td>--}}
            {{-- <td>{{ $item['item_color'] ?? '' }}</td>--}}
            {{-- <td>{{ $item['item_size'] ?? '' }}</td>--}}
            {{-- <td>{{ $item['size'] ?? ''}}</td>--}}
            {{-- <td>{{  $item['actual_cons']}}</td>--}}
            {{-- <td>{{  $item['process_loss']}}</td>--}}
            {{-- <td>{{  $item['total_cons']}}</td>--}}
            {{-- <td>{{ $item['wo_total_qty'] ? round($item['wo_total_qty']) : 0}}</td>--}}
            {{-- <td>{{ $item['uom'] ?? ''}}</td>--}}
            {{-- <td>{{ $item['rate'] ?? '' }}</td>--}}
            {{-- @php--}}
            {{-- $total += $item['amount'] ?? 0;--}}
            {{-- $subTotal += $item['amount'] ?? 0;--}}
            {{-- $subQty +=  $item['wo_total_qty'] ?? 0;--}}
            {{-- $totalQty += $item['wo_total_qty'] ?? 0;--}}
            {{-- @endphp--}}
            {{-- <td>{{ $item['amount'] ?? '' }}</td>--}}
            {{-- <td>{{ '' }}</td>--}}
            {{-- </tr>--}}
            {{-- @else--}}
            {{-- <tr></tr>--}}
            {{-- @endif--}}
            {{-- @endforeach--}}
            {{-- <tr>--}}
            {{-- <th colspan="12" style="text-align: right">Sub Total</th>--}}
            {{-- <td>{{ round($subQty) }}</td>--}}
            {{-- <td/>--}}
            {{-- <td/>--}}
            {{-- <td>{{ $subTotal }}</td>--}}
            {{-- <td/>--}}
            {{-- </tr>--}}
            {{-- @endforeach--}}
            {{-- <tr>--}}
            {{-- <th colspan="12" style="text-align: right">Grand Total</th>--}}
            {{-- <td>{{ round($totalQty) }}</td>--}}
            {{-- <td/>--}}
            {{-- <td/>--}}
            {{-- <td>{{ $total }}</td>--}}
            {{-- <td/>--}}
            {{-- </tr>--}}
            {{-- @endforeach--}}

            {{-- </table>--}}
            {{-- </div>--}}
            {{-- @endif--}}
            {{-- --}}{{-- end color and size sensitivity--}}

            {{-- <div style="margin-top: 15px;">--}}
            {{-- <table class="borderless">--}}
            {{-- <thead>--}}
            {{-- <tr>--}}
            {{-- <td class="text-left">--}}
            {{-- <span style="font-size: 12pt; font-weight: bold;"> Total Booking Amount</span> :--}}
            {{-- {{ $totalAmount ?? '' }} {{$trimsBookings->currency ?? ''}}--}}
            {{-- <br>--}}
            {{-- </td>--}}
            {{-- </tr>--}}
            {{-- <tr>--}}
            {{-- <td class="text-left">--}}
            {{-- <span style="font-size: 12pt; font-weight: bold;">Total Booking Amount (in word)</span>: {{ $amountInWord ?? '' }} {{$trimsBookings->currency ?? ''}}--}}
            {{-- </td>--}}
            {{-- </tr>--}}
            {{-- </thead>--}}
            {{-- </table>--}}
            {{-- </div>--}}
            {{-- </div>--}}

            {{-- <div>--}}
            {{-- <table class="borderless">--}}
            {{-- <tbody>--}}
            {{-- <tr>--}}
            {{-- <th class="text-left"><b><u>Terms and Conditions:</u></b></th>--}}
            {{-- </tr>--}}
            {{-- @if(isset($trimsBookings))--}}
            {{-- @foreach(collect($trimsBookings->terms_condition) as $index => $item)--}}
            {{-- <tr>--}}
            {{-- <td style="font-size: 12px">{{ ++$index }}.{{ $item['term'] }}</td>--}}
            {{-- </tr>--}}
            {{-- @endforeach--}}
            {{-- @endif--}}
            {{-- </tbody>--}}
            {{-- </table>--}}
            {{-- </div>--}}
            {{-- <div style="margin-top: 16mm">--}}
            {{-- <table class="borderless">--}}
            {{-- <tbody>--}}
            {{-- <tr>--}}
            {{-- <td class="text-center"><u>Prepared By</u></td>--}}
            {{-- <td class='text-center'><u>Checked By</u></td>--}}
            {{-- <td class="text-center"><u>Approved By</u></td>--}}
            {{-- </tr>--}}
            {{-- </tbody>--}}
            {{-- </table>--}}
            {{-- </div>--}}
            {{-- @include('skeleton::reports.downloads.footer')--}}
            {{-- </div>--}}
        </div>
    </main>
</body>

</html>