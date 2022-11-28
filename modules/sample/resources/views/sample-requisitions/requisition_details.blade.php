<div class="col-sm-10 col-sm-offset-1">
    <table>
        <tbody>
        <tr>
            <th>Requisition ID</th>
            <td>{{ $sampleOrderRequisition->requisition_id ?? null }}</td>
            <th>Company Name</th>
            <td>{{ $sampleOrderRequisition->factory->factory_name ?? null }}</td>
            <th>Company Location</th>
            <td>{{ $sampleOrderRequisition->factory->factory_address ?? null }}</td>
            <th>Sample Stage</th>
            <td>{{ $sampleOrderRequisition->stage ?? null }}</td>
            <th>Req. Date</th>
            <td>
                {{ $sampleOrderRequisition->req_date ? \Carbon\Carbon::make($sampleOrderRequisition->req_date)->toFormattedDateString() : null  }}
            </td>
        </tr>
        <tr>
            <th>Buyer</th>
            <td>{{ $sampleOrderRequisition->buyer->name ?? null }}</td>
            <th>Style No</th>
            <td>{{ $sampleOrderRequisition->style_name ?? null }}</td>
            <th>Repeat Style No</th>
            <td>{{ $sampleOrderRequisition->repeat_style_name ?? null }}</td>
            <th>Booking NO</th>
            <td>{{ $sampleOrderRequisition->booking_no ?? null }}</td>
            <th>Ref. NO</th>
            <td>{{ $sampleOrderRequisition->ref_no ?? null }}</td>
        </tr>
        <tr>
            <th>Control / Ref. NO</th>
            <td>{{ $sampleOrderRequisition->control_ref_no ?? null }}</td>
            <th>Season</th>
            <td>{{ $sampleOrderRequisition->season->season_name ?? null }}</td>
            <th>Product Dept.</th>
            <td>{{ $sampleOrderRequisition->department->product_department ?? null }}</td>
            <th>Team Leader</th>
            <td>{{ $sampleOrderRequisition->teamLeader->screen_name ?? null }}</td>
            <th>Dealing Merchant</th>
            <td>{{ $sampleOrderRequisition->dealingMerchant->screen_name ?? null }}</td>
        </tr>
        <tr>
            <th>BH Agent Name</th>
            <td>{{ $sampleOrderRequisition->BuyingAgentName->buying_agent_name ?? null }}</td>
            <th>Lab Test</th>
            <td>{{ $sampleOrderRequisition->lab_test_text ?? null }}</td>
            <th>Currency</th>
            <td>{{ $sampleOrderRequisition->currencyName->currency_name ?? null }}</td>
            <th>Est. Ship Date</th>
            <td>
                {{ $sampleOrderRequisition->est_ship_date ? \Carbon\Carbon::make($sampleOrderRequisition->est_ship_date)->toFormattedDateString() : null  }}
            </td>
            <th>Delivery Date</th>
            <td>
                {{ $sampleOrderRequisition->delivery_date ? \Carbon\Carbon::make($sampleOrderRequisition->delivery_date)->toFormattedDateString() : null  }}
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="col-sm-12 m-t">
    <table>
        <thead>
        <tr>
            <th colspan="20" class="text-center text-uppercase">SAMPLE DETAILS</th>
        </tr>
        <tr>
            <th>Sample Type</th>
            <th>Garment Item</th>
            <th>SMV</th>
            <th>Gmts. Color</th>
            <th>Pantone NO</th>
            <th>Gmts. Size</th>
            <th>Plan / Req Qty</th>
            <th>Sample Req Qty</th>
            <th>Self Qty</th>
            <th>BH Qty</th>
            <th>Dyeing Qty</th>
            <th>Test Qty</th>
            <th>Total Qty</th>
            <th>UOM</th>
            <th>Print</th>
            <th>Embroidery</th>
            <th>Submission Date</th>
            <th>Delivery Date</th>
            <th>Image</th>
            <th>Remarks</th>
        </tr>
        </thead>
        @if ($sampleOrderRequisition->details)
        @foreach ($sampleOrderRequisition->details as $value)
        @php
            $uom = $value->details['uom']?(($value->details['uom']==1)?"Pcs":"Set"):null;
            $print = $value->details['print']?(($value->details['print']==1)?"Yes":"No"):null;
            $embroidery = $value->details['embroidery']?(($value->details['embroidery']==1)?"Yes":"No"):null;
            $base64 = '';
            if ($value->details['image_path'] && File::exists('storage/'.$value->details['image_path'])) {
                $path = 'storage/'. $value->details['image_path'];
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        @endphp
        <tr>
            <td>{{ $value->sample->name ?? null }}</td>
            <td>{{ $value->gmtsItem->name ?? null }}</td>
            <td>{{ $value->details['smv'] ?? null }}</td>
            <td>{{ $value->color->name ?? null }}</td>
            <td>{{ $value->details['pantone_no'] ?? null }}</td>
            <td>{{ $value->size->name ?? null }}</td>
            <td>{{ $value->calculations['required_qty'] ?? null }}</td>
            <td>{{ $value->calculations['sm_req_qty'] ?? null }}</td>
            <td>{{ $value->calculations['self_qty'] ?? null }}</td>
            <td>{{ $value->calculations['bh_qty'] ?? null }}</td>
            <td>{{ $value->calculations['dyeing_qty'] ?? null }}</td>
            <td>{{ $value->calculations['test_qty'] ?? null }}</td>
            <td>{{ $value->calculations['total_qty'] ?? null }}</td>
            <td>{{ $uom ?? null }}</td>
            <td>{{ $print ?? null }}</td>
            <td>{{ $embroidery ?? null }}</td>
            <td>
                {{ $value->details['submission_date'] ? \Carbon\Carbon::make($value->details['submission_date'])->toFormattedDateString() : null  }}
            </td>
            <td>
                {{ $value->details['delivery_date'] ? \Carbon\Carbon::make($value->details['delivery_date'])->toFormattedDateString() : null  }}
            </td>
            <td class="text-center">
                @if ($sampleOrderRequisition->viewType != 'excel')
                    @if($base64)
                        <img
                            src="{{ $base64 }}"
                            alt="style image"
                            width="250">
                    @else
                        <img src="{{ asset('images/no_image.jpg') }}" height="50" width="50" alt="no image">
                    @endif
                    <img src="" alt="">
                @endif
            </td>
            <td>{{ $value->details['remarks'] ?? null }}</td>
        </tr>
        @endforeach
        <tr>
            <th colspan="6" class="text-right">Total</th>
            <td>{{ $sampleOrderRequisition->requis_details_cal['required_qty_total'] ? number_format($sampleOrderRequisition->requis_details_cal['required_qty_total'], 2) : null }}</td>
            <td>{{ $sampleOrderRequisition->requis_details_cal['sm_req_qty_total'] ? number_format($sampleOrderRequisition->requis_details_cal['sm_req_qty_total'], 2) : null }}</td>
            <td>{{ $sampleOrderRequisition->requis_details_cal['self_qty_total'] ? number_format($sampleOrderRequisition->requis_details_cal['self_qty_total'], 2) : null }}</td>
            <td>{{ $sampleOrderRequisition->requis_details_cal['bh_qty_total'] ? number_format($sampleOrderRequisition->requis_details_cal['bh_qty_total'], 2) : null }}</td>
            <td>{{ $sampleOrderRequisition->requis_details_cal['dyeing_qty_total'] ? number_format($sampleOrderRequisition->requis_details_cal['dyeing_qty_total'], 2) : null }}</td>
            <td>{{ $sampleOrderRequisition->requis_details_cal['test_qty_total'] ? number_format($sampleOrderRequisition->requis_details_cal['test_qty_total'], 2) : null }}</td>
            <td>{{ $sampleOrderRequisition->requis_details_cal['in_total'] ? number_format($sampleOrderRequisition->requis_details_cal['in_total'], 2) : null }}</td>
            <td>{{ $sampleOrderRequisition->details[0]->details['uom']?(($sampleOrderRequisition->details[0]->details['uom']==1)?"Pcs":"Set"):null; }}</td>
            <td colspan="6"></td>
        </tr>
        @else
        <tr>
            <td colspan="20" class="text-center">No Data Found</td>
        </tr>
        @endif
        <tbody>
        </tbody>
    </table>
</div>

