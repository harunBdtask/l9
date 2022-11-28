<div class="body-section" style="margin-top: 0px;">
    <div>
        <table class="borderless">
            <tbody>
            <tr>
                <th style="width: 50%" class="text-left">Ref. No. <span>{{ $contract_number ?? '' }}</span></th>
                <th style="width: 50%; padding-left: 50px">Date: <span>{{ $contract_date ?? '' }}</span></th>
            </tr>
            <tr>
                <td style="width: 50%; vertical-align: top">CONSIGNEE</td>
                <td style="width: 50%">
                    <span>{{ $consignee ?? '' }}</span> <br>
                    <span>{{ $consignee_address ?? '' }}</span>
                </td>
            </tr>
            <tr>
                <td style="width: 50%">CONSIGNEE'S BANK</td>
                <td style="width: 50%">

                </td>
            </tr>
            <tr>
                <td style="width: 50%;  vertical-align: top">BUYER (1ST BENEFICIARY)</td>
                <td style="width: 50%">
                    <span>{{ $beneficiary ?? '' }}</span> <br>
                    <span>{{ $beneficiary_address ?? '' }}</span>
                </td>
            </tr>
            <tr>
                <td style="width: 50%; vertical-align: top" >SELLER (2ND BENEFICIARY)</td>
                <td style="width: 50%">
                    <span>{{ $second_beneficiary ?? '' }}</span> <br>
                    <span>{{ $second_beneficiary_address ?? '' }}</span>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;  vertical-align: top">SELLER'S BANK</td>
                <td style="width: 50%">
                    <span>{{ $lien_bank ?? '' }}</span> <br>
                    <span>{{ $lien_bank_address ?? '' }}</span>
                </td>
            </tr>
            <tr>
                <td style="width: 50%">CURRENT ACCOUNT NO</td>
                <td style="width: 50%">
                    <b><span>{{ 'A/C NO. ' .$contract_no ?? '' }}</span></b>
                </td>
            </tr>
            <tr>
                <td style="width: 50%">TOTAL AMOUNT IN US$</td>
                <td style="width: 50%">
                    <p style="border: 1px solid black; width: 200px">
                        <b>  {{ ' $'. number_format(collect($details)->sum('total_amount'), 2) }} </b>
                    </p>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div style="margin-top: 10px">
        <table>
            <tr>
                <th class="text-center" >STYLE NUMBER</th>
                <th class="text-center" >PURCHASE ORDER <br> NUMBER</th>
                <th class="text-center" >DESCRIPTION OF GOODS</th>
                <th class="text-center" >QUANTITY IN <br> PCS</th>
                <th class="text-center" >UNIT PRICE <br> (FOB)</th>
                <th class="text-center" >TOTAL AMOUNT <br>US$</th>
                <th class="text-center" >SHIPMENT DATE</th>
            </tr>
            @if(count($details) > 0)
                @foreach(collect($details)->groupBy('description') as $detailsKey => $item )
                    @foreach($item as $index => $data)
                        <tr>
                            <td>{{ $data['style'] ?? '' }}</td>
                            <td>{{ $data['po_no'] ?? '' }}</td>
                            @if($loop->first)
                                <td rowspan="{{ count($item) }}">{{ $data['description'] ?? '' }}</td>
                            @endif
                            <td>{{ $data['po_qty'] ?? 0 }}</td>
                            <td>{{ '$'. $data['rate'] ?? 0 }}</td>
                            <td>{{ '$'. number_format($data['total_amount'], 2) ?? 0 }}</td>
                            <td>{{ $data['shipment_date'] ?? ' ' }}</td>
                        </tr>
                    @endforeach
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td class="text-right">TOTAL</td>
                    <td><b>{{ collect($details)->sum('po_qty') }} </b></td>
                    <td></td>
                    <td><b>{{ ' $'. number_format(collect($details)->sum('total_amount'), 2) }} </b></td>
                    <td></td>
                </tr>
            @endif
        </table>
    </div>
    <div style="margin-top: 10px">
        <table class="borderless">
            <tr>
                <td colspan="2"><span>CONSIGNEE HAS AGREED TO PURCHASE AND SELLER AGREED TO SELL THE READYMADE GARMENTS UNDER BELOW CONDITION</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">1. THIS CONTRACT WILL BE REPLACED BY THE LETTER OF CREDIT ONE MONTH BEFORE OF SHIPMENT
                    DATE
                </td>
            </tr>
            <tr>
                <td style="width: 40%">2. PARTIAL SHIPMENT</td>
                <td style="width: 60%">: ALLOWED</td>
            </tr>
            <tr>
                <td style="width: 40%">3. TRANSHIPMENT</td>
                <td style="width: 60%">: ALLOWED</td>
            </tr>
            <tr>
                <td style="width: 40%">4. SHIPMENT TO</td>
                <td style="width: 60%">: USA</td>
            </tr>
            <tr>
                <td style="width: 40%">5. PAYMENT</td>
                <td style="width: 50%">: 100% AT SIGHT LETTER OF CREDIT /TT</td>
            </tr>

            <tr>
                <td style="width: 40%;vertical-align: top" rowspan="2">6. TOLERANCE</td>
                <td style="width: 60%">: FOR ANY +/- SHIPMENT NEED REVERT TO BUYER AT LEAST
            </tr>
            <tr>
                <td style="width: 60%; padding-left: 15px"> ONE WEEK BEFORE FROM SHIPMENT DATE</td>
            </tr>
            <tr>
                <td style="width: 40%">7. SALES TERMS</td>
                <td style="width: 60%">: FOB BANGLADESH</td>
            </tr>
            <tr>
                <td style="width: 40%">8. NOTIFY PARTY</td>
                <td style="width: 60%">: SAME AS CONSIGNEE</td>
            </tr>
            <tr>
                <td style="width: 40%">9. PORT OF DESTINATION</td>
                <td style="width: 60%">: LOS ANGELES, CA. OR NEW YORK USA</td>
            </tr>
            <tr>
                <td style="width: 40%">10. EXPIRY DATE</td>
                <td style="width: 60%">: AFTER 10DAYS FROM LAST SHIPMENT DATE</td>
            </tr>
        </table>
    </div>
    <div class="row" style="margin-top: 10mm; width: 100%">
        <div class="col-md-12">
            <table class="borderless">
                <tr>
                    <th width="50%" style="border-top: 1px solid black">{{ $beneficiary ?? '' }}</th>
                    <th with="10%"></th>
                    <th width="40%" style="border-top: 1px solid black" >{{ $second_beneficiary ?? '' }}</th>
                </tr>
            </table>
        </div>
{{--        <div class="col-md-6" style="border-top: 1px solid black">--}}
{{--            <span style="padding-left: 50px">{{ $beneficiary ?? '' }}</span>--}}
{{--        </div>--}}
{{--        <div class="col-md-1"></div>--}}
{{--        <div class="col-md-5" style="border-top: 1px solid black"   >--}}
{{--            <span style="padding-left: 50px">{{  'N/A ' }}</span>--}}

{{--        </div>--}}
    </div>
    <div class="row">
        <div class="col-md-12" style="margin-top: 10px; border-top: 1px solid black;">

            <small>"Anukul" House#30 Apt#B-3(3rd Floor), Western Road, Banani DOHS, Dhaka-1206, Bangladesh, Ph:
                880-2-8715826, 8715827, 8715779 </small>
            <br>
            <b> Name: Balaji Voleti </b> <br>
            <b>Title : Managing Director</b> <br>
            <small>E-Mail: balaji@apparelsourcingint.com</small>
        </div>
    </div>

</div>
