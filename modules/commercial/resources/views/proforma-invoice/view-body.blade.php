<div>

    <div class="body-section" style="margin-top: 0px;">
        <div>
            <table>
                <tr>
                    <td rowspan="6" style="width: 35%;   vertical-align: top;">
                        Supplier: <br>
                        <b>{{ $invoice->supplier->name ?? '' }}</b> <br>
                        <span> {{ $invoice->supplier->address_1 ?? '' }} </span>
                    </td>
                    <th style="width: 25%">Proforma Invoice</th>
                    <td>{{ $invoice->pi_no ?? '' }}</td>
                </tr>
                <tr>
                    <th style="width: 25%">Date</th>
                    <td>{{ $invoice->pi_receive_date ?? '' }}</td>
                </tr>
                <tr>
                    <th style="width: 25%">Currency</th>
                    <td>{{ $invoice->currency ?? '' }}</td>
                </tr>
                <tr>
                    <th style="width: 25%">HS Code</th>
                    <td>{{ $invoice->hs_code ?? '' }}</td>
                </tr>
                <tr>
                    <th style="width: 25%">LC Group No</th>
                    <td>{{ $invoice->lc_group_no ?? '' }}</td>
                </tr>
                <tr>
                    <th style="width: 25%">Internal File No</th>
                    <td>{{ $invoice->internal_file_no ?? '' }}</td>
                </tr>

            </table>
        </div>

        <div>
            <table>
                <tr>
                    <th style="width: 1%">SL#</th>
                    <th style="text-align:center">Description</th>
                    <th style="width: 8%">QTY</th>
                    <th style="width: 4%">Rate</th>
                    <th style="width: 10%">Total Amount(USD)</th>
                </tr>
                @if( count(collect($invoice->details)) > 0)
                    @foreach( collect($invoice->details->details)->toArray() as $index =>  $item)
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>
                                {{-- Chemical --}}
                                @if($invoice->item_category == 5)  
                                    @php
                                    $dyes_item = collect($ds_items)->where('id', $item->dyes_store_item)->first();
                                    @endphp
                                    {{ $dyes_item->name ?? ''}}
                                @else
                                    {!! isset($item->count) ? ($item->count ?? '') . ',' : '' !!}
                                    {!! isset($item->composition_percent) ?  $item->composition_percent . '%,' : '' !!}
                                    {!! isset($item->composition) ?  $item->composition . ',' : '' !!}
                                    {!! isset($item->type_value) ?  $item->type_value . ',' : '' !!}
                                    {!! isset($item->color) ?  $item->color . ',' : '' !!}
                                    {!! isset($item->buyer_name) ?  ' <b>Buyer:</b> ' . $item->buyer_name: '' !!}
                                    {!! isset($item->style_name) ? '<b>Style Name:</b> ' . $item->style_name  : ''  !!}
                                    {!! isset($item->item_group) ?  ' <b>Item:</b> ' . $item->item_group: '' !!}
                                    {!! isset($item->gmts_color) ?  ' <b>Color:</b> ' . $item->gmts_color: '' !!}
                                    {!! isset($item->construction) ?  ' <b>Construction:</b> ' . $item->construction: '' !!}
                                    {!! isset($item->gsm) ?  ' <b>GSM:</b> ' . $item->gsm: '' !!}
                                    {!! isset($item->dia) ?  ' <b>Dia:</b> ' . $item->dia: '' !!}
                                    {!! isset($item->dia_type_value) ?  ' <b>Dia Type:</b> ' . $item->dia_type_value: '' !!}
                                    {!! isset($item->gmts_size) ?  ' <b>Size:</b> ' . $item->gmts_size: '' !!}
                                    {!! isset($item->uom_value) ?  ' <b>UOM:</b> ' . $item->uom_value: '' !!}

                                @endif

                            </td>
                            <td style="text-align: right">
                                {{ number_format($item->quantity, 2) ?? 0 }} {{  $item->uom_value ?? $item->uom ?? '' }}
                            </td>
                            <td style="text-align: right">
                                @php $rate = !empty($item->rate) ? $item->rate: (!empty($item->rate_taka) ? $item->rate_taka: 0)   @endphp
                                {{ number_format($rate, 2) }}
                            </td>
                            <td style="text-align: right">
                                {{ number_format($item->amount, 2) ?? 0 }}
                            </td>
                        </tr>
                    @endforeach
                    @php
                        $numberFormatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
                        //dump($numberFormatter->format(50));
                        $total = $invoice->details->total ?? 0;
                        $carryingCost = $invoice->details->carrying_cost ?? 0;
                        $upCharge = $invoice->details->up_charge ?? 0;
                        $discount = $invoice->details->discount ?? 0;
                        $netTotal = $invoice->details->net_total ?? 0;

                        $totalAmount = sprintf("%01.2f", $total);
                        $inword = ucwords($numberFormatter->format($totalAmount));
                        $carryingCostInWord = ucwords($numberFormatter->format($carryingCost));
                        $upChargeInWord = ucwords($numberFormatter->format($upCharge));
                        $discountInWord = ucwords($numberFormatter->format($discount));
                        $netTotalAmount = sprintf("%01.2f", $netTotal);
                        $netTotalInWord = ucwords($numberFormatter->format($netTotalAmount));
                    @endphp
                    <tr>
                        <th style="text-align: left" colspan="4">
                            Total Price : {{  $inword }} {{ $invoice->currency ?? '' }}
                        </th>
                        <th style="text-align: right">{{ number_format($totalAmount,2) }}</th>
                    </tr>
                    <tr>
                        <th style="text-align: left" colspan="4">
                            Carrying Cost :
                        </th>
                        <th style="text-align: right"> {{ number_format($carryingCost,2) }} </th>
                    </tr>
                    <tr>
                        <th style="text-align: left" colspan="4">
                            UpCharge :
                        </th>
                        <th style="text-align: right"> {{ number_format($upCharge, 2) }} </th>
                    </tr>
                    <tr>
                        <th style="text-align: left" colspan="4">
                            Discount :
                        </th>
                        <th style="text-align: right"> {{ number_format($discount, 2) }} </th>
                    </tr>
                    <tr>
                        <th style="text-align: left" colspan="4">
                            Net Total : {{ $netTotalInWord }} {{ $invoice->currency ?? '' }}
                        </th>
                        <th style="text-align: right"> {{ number_format((($totalAmount + $carryingCost + $upCharge) - $discount), 2) }} </th>
                    </tr>
                @else
                    <tr>
                        <th style="text-align: center" colspan="5"> No Data Found!</th>
                    </tr>
                @endif
            </table>
        </div>

        <div style="margin-top: 16mm">
            <table class="borderless">
                <tbody>
                <tr>
                    <th class="text-left"><b><u>Terms and Conditions:</u></b></th>
                </tr>

                @if(count($terms))
                    @php $index = 0;@endphp
                    @foreach($terms as $item)
                        @if(isset($item->terms_name))
                            <tr>
                                <td><small>{{ ++$index }}. &nbsp; {{ $item->terms_name }}</small></td>
                            </tr>
                        @endif
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>

    </div>

    <div style="margin-top: 16mm">

    </div>
</div>
