<div class="row p-x-1">
    <div class="col-md-12">

        <table class="reportTable" style="margin-top: 50px;">
            <thead>
            <tr>
                <td style="width: 150px;" class="text-left">
                    <strong>Supplier Name :</strong>
                </td>
                <td style="width: 350px;"
                > {{ $inventory->booking->supplier->name ?? '' }} </td>

                <td style="width: 150px;" class="text-left">
                    <strong>ERP Booking No. :</strong>
                </td>
                <td style="width: 350px;"
                > {{ $inventory->booking_no }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Address :</strong>
                </td>
                <td style="padding-left: 30px;">{{ $inventory->booking->location }}</td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Booking Date :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $inventory->booking_date }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Attention :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $inventory->booking->attention ?? '' }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Challan Date :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $inventory->challan_date }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Dealing Merchant :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ collect($inventory->booking->details)
                                                ->pluck('budget.order.dealingMerchant.screen_name')
                                                ->unique()
                                                ->values()
                                                ->join(', ')
                                                }}
                </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>QC Date :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $inventory->qc_date }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Buyer :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $inventory->buyer->name ?? '' }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Booking Qty :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $inventory->booking_qty }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Style :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ collect($inventory->booking->details)->first()->style_name ?? '' }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Delivery Qty :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $inventory->delivery_qty }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>PO :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ collect($inventory->booking->details)->pluck('po_no')
                                                ->unique()
                                                ->values()
                                                ->join(', ') }}
                </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Short/Excess Delivery Qty :</strong>
                </td>
                <td style="padding-left: 30px;"
                >
                    {{ $inventory->booking_qty - $inventory->delivery_qty }}
                </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Season :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ collect($inventory->booking->details)
                                                ->pluck('budget.order.season.season_name')
                                                ->unique()
                                                ->values()
                                                ->join(', ')
                                                }}
                </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>PI Number :</strong>
                </td>
                <td style="padding-left: 30px;">{{ $inventory->pi_no }}</td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Store :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $inventory->store->name }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Challan Number :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $inventory->challan_no }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Inventory No :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $inventory->bin_no }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Others :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $inventory->others }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>T &amp; A Start Date :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $inventory->tna_start_date }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>T &amp; A Finish Date :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $inventory->tna_end_date }} </td>
            </tr>
            </thead>
        </table>

        <table class="reportTable" style="width: 100%;margin-top: 30px;">
            <thead>
            <tr>
                <th>Serial No</th>
                <th>Date</th>
                <th>Item</th>
                <th>Item Description</th>
                <th>Color</th>
                <th>Size</th>
                <th>UOM</th>
                <th>Approval Shade/Code</th>
                <th>Delivery Swatch</th>
                <th>Color(OK/NOT OK)</th>
                <th>Booking Qty</th>
                <th>Received Qty</th>
                <th>Short/Excess Qty</th>
                <th>Rejected Qty</th>
                <th>Qty(OK/NOT OK)</th>
                <th>Quality(OK/NOT OK)</th>
                <th>Dimensions(OK/NOT OK)</th>
                <th>CF To Wash(OK/NOT OK)</th>
                <th>Inventory By</th>
                <th>Remarks</th>
            </tr>
            </thead>

            <tbody>
            @foreach($inventory->details as $detail)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $detail->receive_date }}</td>
                    <td class="text-center">{{ $detail->itemGroup->item_group }}</td>
                    <td class="text-center">{{ $detail->item_description }}</td>
                    <td class="text-center">{{ $detail->color->name ?? '' }}</td>
                    <td class="text-center"></td>
                    <td class="text-center">{{ $detail->uom->unit_of_measurement ?? '' }}</td>
                    <td class="text-center">{{ $detail->approval_shade_code }}</td>
                    <td class="text-center">{{ $detail->delivery_swatch }}</td>
                    <td class="text-center">
                        @if($detail->is_color == 1)
                            <span>OK</span>
                        @elseif($detail->is_color == 2)
                            <span>Not OK</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $detail->booking_qty }}</td>
                    <td class="text-center">{{ $detail->receive_qty }}</td>
                    <td class="text-center">{{ $detail->excess_qty }}</td>
                    <td class="text-center">{{ $detail->reject_qty }}</td>
                    <td class="text-center">
                        @if($detail->is_qty == 1)
                            <span>OK</span>
                        @elseif($detail->is_qty == 2)
                            <span>Not OK</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($detail->quality == 1)
                            <span>OK</span>
                        @elseif($detail->quality == 2)
                            <span>Not OK</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($detail->dimensions == 1)
                            <span>OK</span>
                        @elseif($detail->dimensions == 2)
                            <span>Not OK</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($detail->cf_to_wah == 1)
                            <span>OK</span>
                        @elseif($detail->cf_to_wah == 2)
                            <span>Not OK</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $detail->inventory_by }}</td>
                    <td class="text-center">{{ $detail->remarks }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>
