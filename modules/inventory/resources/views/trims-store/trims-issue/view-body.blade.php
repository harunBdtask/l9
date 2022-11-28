<div class="row p-x-1">
    <div class="col-md-12">

        <table class="reportTable" style="margin-top: 50px;">
            <thead>
            <tr>
                <td style="width: 150px;" class="text-left">
                    <strong>Supplier Name :</strong>
                </td>
                <td style="width: 350px;"
                > {{ $issue->booking->supplier->name ?? '' }} </td>

                <td style="width: 150px;" class="text-left">
                    <strong>ERP Booking No. :</strong>
                </td>
                <td style="width: 350px;"
                > {{ $issue->booking_no }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Address :</strong>
                </td>
                <td style="padding-left: 30px;">{{ $issue->booking->location }}</td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Booking Date :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $issue->booking_date }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Attention :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $issue->booking->attention ?? '' }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Dealing Merchant :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ collect($issue->booking->details)
                                                ->pluck('budget.order.dealingMerchant.screen_name')
                                                ->unique()
                                                ->values()
                                                ->join(', ')
                                                }}
                </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Buyer :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $issue->buyer->name ?? '' }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Booking Qty :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $issue->booking_qty }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Style :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ collect($issue->booking->details)->first()->style_name ?? '' }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Delivery Qty :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $issue->delivery_qty }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>PO :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ collect($issue->booking->details)->pluck('po_no')
                                                ->unique()
                                                ->values()
                                                ->join(', ') }}
                </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Short/Excess Delivery Qty :</strong>
                </td>
                <td style="padding-left: 30px;"
                >
                    {{ $issue->booking_qty - $issue->delivery_qty }}
                </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Season :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ collect($issue->booking->details)
                                                ->pluck('budget.order.season.season_name')
                                                ->unique()
                                                ->values()
                                                ->join(', ')
                                                }}
                </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>PI Number :</strong>
                </td>
                <td style="padding-left: 30px;">{{ $issue->pi_no }}</td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Store :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $issue->store->name }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Challan Number :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $issue->challan_no }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>BIN Numbers :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $issue->unique_id }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Others :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $issue->others }} </td>
            </tr>

            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>T &amp; A Start Date :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $issue->tna_start_date }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>T &amp; A Finish Date :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $issue->tna_end_date }} </td>
            </tr>

            </thead>
        </table>

        <table class="reportTable" style="width: 100%;margin-top: 30px;">
            <thead>
            <tr>
                <th>Serial No</th>
                <th>Date</th>
                <th>Item </th>
                <th>Item Description</th>
                <th>Color</th>
                <th>Size</th>
                <th>Garments Qty</th>
                <th>UOM</th>
                <th>Approval Shade/Code</th>
                <th>Floor</th>
                <th>Room</th>
                <th>Rack</th>
                <th>Shelf</th>
                <th>Bin</th>
                <th>MRR Qty</th>
                <th>Issue Qty</th>
                <th>Issue Date</th>
                <th>Issue TO</th>
                <th>Remarks</th>
            </tr>
            </thead>

            <tbody>
            @foreach($issue->details as $detail)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $detail->current_date }}</td>
                    <td class="text-center">{{ $detail->itemGroup->item_group }}</td>
                    <td class="text-center">{{ $detail->item_description }}</td>
                    <td class="text-center">{{ $detail->color->name ?? '' }}</td>
                    <td class="text-center">{{ $detail->size ?? '' }}</td>
                    <td class="text-center">{{ $detail->planned_garments_qty ?? '' }}</td>
                    <td class="text-center">{{ $detail->uom->unit_of_measurement ?? '' }}</td>
                    <td class="text-center">{{ $detail->approval_shade_code }}</td>
                    <td class="text-center">{{ $detail->floor->name }}</td>
                    <td class="text-center">{{ $detail->room->name }}</td>
                    <td class="text-center">{{ $detail->rack->name }}</td>
                    <td class="text-center">{{ $detail->shelf->name }}</td>
                    <td class="text-center">{{ $detail->bin->name }}</td>
                    <td class="text-center">{{ $detail->trimsBinCardDetail->mrrDetail->total_delivered_qty }}</td>
                    <td class="text-center">{{ $detail->issue_qty }}</td>
                    <td class="text-center">{{ $detail->issue_date }}</td>
                    <td class="text-center">{{ $detail->issue_to }}</td>
                    <td class="text-center">{{ $detail->remarks }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
