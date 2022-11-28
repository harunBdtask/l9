<div class="row">
    <div id="mainTable" class="col-sm-10 col-sm-offset-1 m-t">
        <table>
            <thead>
                <tr>
                    <th>Sample Process ID</th>
                    <th>Requisition ID</th>
                    <th>Buyer Name</th>
                    <th>Style No./Article</th>
                    <th>Booking NO</th>
                    <th>Control / Ref. NO</th>
                    <th>Company Name</th>
                    <th>Est. Ship Date</th>
                    <th>Comments</th>
                    <th>Ready to Approve</th>
                    <th>Total Order QTY</th>
                    <th>Sample Processing Date</th>
                    <th>Sample Req. Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $sampleProcessing->process_id ?? null }}</td>
                    <td>{{ $sampleProcessing->requisition_id ?? null }}</td>
                    <td>{{ $sampleProcessing->buyer->name }}</td>
                    <td>{{ $sampleProcessing->style_name ?? null }}</td>
                    <td>{{ $sampleProcessing->details['booking_no'] ?? null }}</td>
                    <td>{{ $sampleProcessing->details['control_ref_no'] ?? null }}</td>
                    <td>{{ $sampleProcessing->factory->factory_name ?? null }}</td>
                    <td>
                        {{ isset($sampleProcessing->details['est_ship_date']) ? \Carbon\Carbon::make($sampleProcessing->details['est_ship_date'])->toFormattedDateString() : null  }}
                    </td>
                    <td>{{ $sampleProcessing->details['remarks'] ?? null }}</td>
                    <td>{{ $sampleProcessing->ready_for_approve ?? null }}</td>
                    <td>{{ $sampleProcessing->order_qty ?? null }}</td>
                    <td>
                        {{ isset($sampleProcessing->details['processing_date']) ? \Carbon\Carbon::make($sampleProcessing->details['processing_date'])->toFormattedDateString() : null  }}
                    </td>
                    <td>
                        {{ isset($sampleProcessing->details['req_date']) ? \Carbon\Carbon::make($sampleProcessing->details['req_date'])->toFormattedDateString() : null  }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="detailsTable" class="col-sm-12 m-t">
        <table>
            <thead>
            <tr>
                <th colspan="20" class="text-center">DETAILS</th>
            </tr>
            <tr>
                <th>Sample Type</th>
                <th>Garment Item</th>
                <th>SMV</th>
                <th>Gmts. Color</th>
                <th>Pantone NO</th>
                <th>Combo / Contrast Color Name</th>
                <th>Body Part</th>
                <th>Fabric Description</th>
                <th>Gmts. Size</th>
                <th>Plan / Req Qty</th>
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
            <tbody>
            @if ($sampleProcessing->processingDetails)
            @foreach ($sampleProcessing->processingDetails as $value)
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
                $fabricComposition = $value->newFabricComposition ?? null;
                $composition = '';
                if ($fabricComposition) {
                    $last_key = $fabricComposition->newFabricCompositionDetails->keys()->last();
                    $fabricComposition->newFabricCompositionDetails()->each(function($item, $key) use (&$composition, $last_key) {
                        $composition .= $item->percentage.'% '.$item->yarnComposition->yarn_composition.' '.$item->yarnCount->yarn_count.' '.$item->compositionType->name;
                        $composition .= ($key != $last_key) ? ', ' : '';
                    });
                }
            @endphp
            <tr>
                <td>{{ $value->sample->name ?? null }}</td>
                <td>{{ $value->gmtsItem->name ?? null }}</td>
                <td>{{ $value->details['smv'] ?? null }}</td>
                <td>{{ $value->color->name ?? null }}</td>
                <td>{{ $value->details['pantone_no'] ?? null }}</td>
                <td>{{ $value->fabric_details['combo_contrast_color'] ?? null }}</td>
                <td>{{ $value->bodyPart->name ?? null }}</td>
                <td>{{ $composition ?? null }}</td>
                <td>{{ $value->size->name ?? null }}</td>
                <td>{{ $value->calculations['required_qty'] ?? null }}</td>
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
                    @if ($sampleProcessing->viewType != 'excel')
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
                <th colspan="9" class="text-right">Total</th>
                <td>{{ $sampleProcessing->total_calculation['required_qty_total'] ? number_format($sampleProcessing->total_calculation['required_qty_total'], 2) : null }}</td>
                <td>{{ $sampleProcessing->total_calculation['in_total'] ? number_format($sampleProcessing->total_calculation['in_total'], 2) : null }}</td>
                <td>{{ $sampleProcessing->processingDetails[0]->details['uom']?(($sampleProcessing->processingDetails[0]->details['uom']==1)?"Pcs":"Set"):null; }}</td>
                <th colspan="8"></th>
            </tr>
            @else
            <tr>
                <td colspan="20" class="text-center">No Data Found</td>
            </tr>
            @endif
            </tbody>
        </table>
    </div>
