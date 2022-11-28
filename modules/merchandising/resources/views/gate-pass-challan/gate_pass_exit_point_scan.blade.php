@extends('skeleton::layout')
@section('title','Gate Pass Exit Point Scan')
@section('content')
    <style type="text/css">
        .v-align-top td, .v-algin-top th {
            vertical-align: top;
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 10mm auto;
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

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 3px;
            padding-bottom: 3px;
        }

        table.borderless {
            border: none;
        }

        .borderless td, .borderless th {
            border: none;
        }

        @page {
            size: landscape;
            /*margin: 5mm;*/
            /*margin-left: 15mm;*/
            /*margin-right: 15mm;*/
        }

        @media print {
            html, body {
                width: 210mm;
                /*height: 293mm;*/
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
    </style>
    <div class="padding">
        <div class="box">
            <div class="box-header" style="text-align: center">
                <div class="col-11">
                    <h2>
                        Gate Pass Exit Point Scan || {{\Carbon\Carbon::parse(now())->format('l jS \of F Y h:i:s A')}}
                    </h2>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-6">
                        <form action="{{ url('/gate-pass-challan/exit-point-scan') }}" method="GET">
                            <div class="input-group" style="width:100%;">
                                <input type="text" class="form-control" name="search"
                                       value="{{$search ?? ""}}" id="search" placeholder="Scan Gate Pass Challan Here">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="body-section">
                    @include('partials.response-message')
                    @if(!empty($data) && !empty($goods))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-right">
                                    <a href="{{ url("/gate-pass-challan/exit-point-scan-update?search=$search") }}"
                                       class="btn btn-sm btn-success">Exit</a>

                                    <a href="{{ url('/gate-pass-challan/exit-point-scan') }}"
                                       class="btn btn-sm btn-danger">Close</a>
                                </div>
                            </div>
                        </div>
                        <div style="background-color: #d1fff1;">
                            <div
                                style="margin-top: 10px; border: 1px solid #1E1E1E; display: flex; justify-content: space-between">
                                <div style="padding-left: 10px;">
                                    <p style="font-weight: 500; font-size: 18px;">CONSIGNEE/FROM: </p>
                                    <span
                                        style="font-size: 24px; font-weight: bold; text-decoration: underline;">{{ $data['factory']['factory_name'] ?? '' }}</span><br>
                                    <span
                                        style="font-size: 16px;">{{ $data['factory']['factory_address'] ?? '' }}</span>
                                </div>
                                <div>
                                    <p style="font-weight: 500; font-size: 18px;">BENEFICIARY/TO: </p>
                                    <span
                                        style="font-size: 24px; font-weight: bold; text-decoration: underline;">{{ $data['party']['name'] ?? '' }}</span><br>
                                    <span>Attn: {{ $data['party']['contact_person'] ?? '' }}</span><br>
                                    <span>Contact: {{ $data['party']['contact_no'] ?? '' }}</span><br>
                                    <span>{{ $data['party']['address_1'] ?? '' }}</span><br>
                                </div>
                                <div style="padding-top: 50px; padding-right:20px; ">
                                    <span><?php echo DNS1D::getBarcodeSVG($data['barcode'] ?? '', "C128A", 1.2, 25, '', false); ?></span>
                                </div>
                            </div>

                            <div style="margin-top: 3rem;">
                                <table>
                                    <tr style="background-color: #d9d9bf; font-weight: bold; font-size: 18px;">
                                        <td colspan="10" class="text-center">CHALLAN
                                            FOR: {{ $goods[$data['good_id']] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Challan No</th>
                                        <th>Challan Date</th>
                                        <th>Vehicle No</th>
                                        <th>Driver Name</th>
                                        <th>Lock No</th>
                                        <th>Bag Quantity</th>
                                        <th>Returnable</th>
                                        <th>Sender</th>
                                        <th>Department</th>
                                        <th>Contact</th>
                                    </tr>
                                    <tr>
                                        <td>{{ $data['challan_no'] }}</td>
                                        <td>{{ $data['challan_date'] }}</td>
                                        <td>{{ $data['vehicle_no'] }}</td>
                                        <td>{{ $data['driver_name'] }}</td>
                                        <td>{{ $data['lock_no'] }}</td>
                                        <td>{{ $data['bag_quantity'] }}</td>
                                        <td>{{ $data['returnable'] == 1 ? 'Yes' : 'No' }}</td>
                                        <td>{{ $data['merchant']['screen_name'] ?? '' }}</td>
                                        <td>{{ $data['department']['product_department'] ?? '' }}</td>
                                        <td>{{ $data['merchant']['phone_no'] ?? '' }}</td>
                                    </tr>
                                </table>
                                <table style="margin-top: 2rem">
                                    <tr>
                                        <th class="text-center">S/N</th>
                                        <th class="text-center">Item</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Buyer</th>
                                        <th class="text-center">Style</th>
                                        <th class="text-center">PO</th>
                                        <th class="text-center">Colour</th>
                                        <th class="text-center">Size</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">UOM</th>
                                        <th class="text-center">Unit Price</th>
                                        <th class="text-center">Total Price</th>
                                        <th class="text-center">Remarks</th>
                                    </tr>
                                    @php
                                        $totalPrice = 0;
                                    @endphp
                                    @foreach($data['goods_details'] as $key => $detail)
                                        @php
                                            $price = isset($detail['avg_rate_pc_set']) ? (double)$detail['avg_rate_pc_set'] * (double)$detail['qty'] : 0;
                                            $totalPrice += $price;
                                        @endphp
                                        <tr style="text-align: center">
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $detail['sample_type'] ?? '' }}</td>
                                            <td>{{ $detail['item_description'] ?? '' }}</td>
                                            <td>{{ $detail['buyer'] ?? '' }}</td>
                                            <td>{{ $detail['style_name'] ?? ''  }}</td>
                                            <td>{{ $detail['po_no'] ?? '' }}</td>
                                            <td>{{ $detail['color'] ?? '' }}</td>
                                            <td>{{ $detail['size'] ?? '' }}</td>
                                            <td>{{ $detail['qty'] ?? '' }}</td>
                                            <td>{{ $detail['uom'] ?? '' }}</td>
                                            <td>{{ $detail['avg_rate_pc_set'] ?? 0 }}</td>
                                            <td>{{ $price }}</td>
                                            <td>{{ $detail['remarks'] ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                    <tr style="text-align: center">
                                        <td><strong>Total</strong></td>
                                        <td colspan="7"></td>
                                        <td>{{ collect($data['goods_details'])->sum('qty') }}</td>
                                        <td colspan="2"></td>
                                        <td>{{ $totalPrice }}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        @php
                                            $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                        @endphp
                                        <td colspan="13" class="text-center">
                                            <strong>Total Amount in
                                                BDT:</strong> {{ ucwords($digit->format($totalPrice)) }}
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div
                                style="margin-top: 2.5rem; border: 1px solid #1E1E1E; display: flex; justify-content: space-between; padding: 70px 20px 20px;">
                                <div class="text-center">
                                    <span
                                        style="border: 1px solid #1E1E1E; padding: 0 40px;"><strong>Prepared By</strong></span>
                                </div>
                                <div class="text-center">
                                    <span
                                        style="border: 1px solid #1E1E1E; padding: 0 40px;"><strong>Checked By</strong></span>
                                </div>
                                <div class="text-center">
                                    <span
                                        style="border: 1px solid #1E1E1E; padding: 0 40px;"><strong>Approved By</strong></span>
                                </div>
                                <div class="text-center">
                                    <span
                                        style="border: 1px solid #1E1E1E; padding: 0 40px;"><strong>Authorized By</strong></span>
                                </div>
                            </div>

                            <div class="m-t-2">
                                <p><strong>Remarks: </strong>{{ $data['remarks'] }}</p>
                            </div>

                            <div style="margin-top: 10px !important;" style="display:flex;">
                                @if ($data->is_approve)
                                    @foreach($signatures as $signature)
                                        @if($signature && File::exists('storage/'.$signature))
                                            <img src="{{asset('storage/'. $signature)}}"
                                                 class="ml-3" width="300px" height="70px" alt="signature">
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="row"></div>
                        {{--                    @include('partials.response-message')--}}
                        <div style="margin-top: 100px;  text-align: center; color: red;">
                            <h2>{{$message ?? 'No Data Found'}}</h2>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
    </div>
@endsection
