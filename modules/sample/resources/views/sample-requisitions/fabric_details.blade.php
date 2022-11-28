<div class="col-sm-10 col-sm-offset-1 m-t">
    <table>
        <tbody>
        <tr>
            <th colspan="10" class="text-center text-uppercase">FABRIC Booking</th>
        </tr>
        <tr>
            <th>FABRIC NATURE</th>
            <th>FABRIC SOURCE</th>
            <th>N. Supplier</th>
            <th>Delivery TO</th>
            <th>Delivery Date</th>
        </tr>
        @php
            $fabricSource = [];
            if ($sampleOrderRequisition->fabricMain->fabric_source_id) {
                $fabricSource = collect($sampleOrderRequisition->fabricSources)->where('id', $sampleOrderRequisition->fabricMain->fabric_source_id)->first();
            }
        @endphp
        <tr>
            <td>{{ $sampleOrderRequisition->fabricMain->fabricNature->name ?? null }}</td>
            <td>{{ $fabricSource['name'] ?? null }}</td>
            <td>{{ $sampleOrderRequisition->fabricMain->supplier->name ?? null }}</td>
            <td>{{ $sampleOrderRequisition->fabricMain->factory->factory_name ?? null }}</td>
            <td>
                {{ $sampleOrderRequisition->fabricMain->delivery_date ? \Carbon\Carbon::make($sampleOrderRequisition->fabricMain->delivery_date)->toFormattedDateString() : null  }}
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="col-sm-12 m-t">
    <table>
        <thead>
        <tr>
            <th colspan="20" class="text-center text-uppercase">FABRIC DETAILS</th>
        </tr>
        <tr>
            <th>Body Part</th>
            <th>GMTS COLORS</th>
            <th>Combo / Contrast Color Name</th>
            <th>Pantone NO</th>
            <th>Labdip NO</th>
            <th>COLOR TYPE</th>
            <th>Construction Name</th>
            <th>Fabric Description</th>
            <th>Fin. Fabric Store Available</th>
            <th>DIA TYPE</th>
            <th>FINISH DIA</th>
            <th>GSM</th>
            <th>Finish QTY</th>
            <th>Process Loss</th>
            <th style="display: none">GREY QTY</th>
            <th>TOTAL REQ. QTY</th>
            <th>UOM</th>
            <th>Rate</th>
            <th>TOTAL AMOUNT</th>
            <th>Remarks</th>
        </tr>
        </thead>
        @if ($sampleOrderRequisition->fabricDetails)
        @foreach ($sampleOrderRequisition->fabricDetails as $value)
        @php
            $fabricComposition = $value->newFabricComposition ?? null;
            $composition = '';
            if ($fabricComposition) {
                $last_key = $fabricComposition->newFabricCompositionDetails->keys()->last();
                $fabricComposition->newFabricCompositionDetails()->each(function($item, $key) use (&$composition, $last_key) {
                    $composition .= $item->percentage.'% '.$item->yarnComposition->yarn_composition.' '.$item->yarnCount->yarn_count.' '.$item->compositionType->name;
                    $composition .= ($key != $last_key) ? ', ' : '';
                });
            }
            $diaType = [];
            if ($value->details['dia_type']) {
                $diaType = collect($sampleOrderRequisition->dia_types)->where('id', $value->details['dia_type'])->first();
            }
            $fabricUom = [];
            if ($value->details['uom_id']) {
                $fabricUom = collect($sampleOrderRequisition->fabricUoms)->where('id', $value->details['uom_id'])->first();
            }
        @endphp
        <tr>
            <td>{{ $value->bodyPart->name ?? null }}</td>
            <td>{{ $value->color->name ?? null }}</td>
            <td>{{ $value->details['combo_contrast_color'] ?? null }}</td>
            <td>{{ $value->details['pantone_no'] ?? null }}</td>
            <td>{{ $value->details['labdip'] ?? null }}</td>
            <td>{{ $value->colorType->color_types ?? null }}</td>
            <td>{{ $value->fabricConstructionEntry->construction_name ?? null }}</td>
            <td>{{ $composition ?? null }}</td>
            <td>{{ $value->calculations['store_available_qty'] ?? null }}</td>
            <td>{{ $diaType['name'] ?? null }}</td>
            <td>{{ $value->calculations['finish_dia'] ?? null }}</td>
            <td>{{ $value->calculations['gsm'] ?? null }}</td>
            <td>{{ number_format($value->calculations['finish_qty'], 2) ?? null }}</td>
            <td>{{ $value->calculations['process_loss'] ?? null }}</td>
            <td style="display: none">{{ $value->calculations['grey_qty'] ?? null }}</td>
            <td>{{ number_format($value->calculations['total_req_qty'], 2) ?? null }}</td>
            <td>{{ $fabricUom['name'] ?? null }}</td>
            <td>{{ number_format($value->calculations['rate'], 2) ?? null }}</td>
            <td>{{ number_format($value->calculations['total_amount'], 2) ?? null }}</td>
            <td>{{ $value->details['remarks'] ?? null }}</td>
        </tr>
        @endforeach
        <tr>
            <th colspan="12" class="text-right">Total</th>
            <td>{{ $sampleOrderRequisition->fabric_details_cal['total_finish_qty'] ? number_format($sampleOrderRequisition->fabric_details_cal['total_finish_qty'], 2) : null }}</td>
            <th></th>
            <td style="display: none">{{ $sampleOrderRequisition->fabric_details_cal['total_grey_qty'] ? number_format($sampleOrderRequisition->fabric_details_cal['total_grey_qty'], 2) : null }}</td>
            <td>{{ $sampleOrderRequisition->fabric_details_cal['in_total_req_qty'] ? number_format($sampleOrderRequisition->fabric_details_cal['in_total_req_qty'], 2) : null }}</td>
            <th></th>
            <th></th>
            <td>{{ $sampleOrderRequisition->fabric_details_cal['in_total'] ? number_format($sampleOrderRequisition->fabric_details_cal['in_total'], 2) : null }}</td>
            <td></td>
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
