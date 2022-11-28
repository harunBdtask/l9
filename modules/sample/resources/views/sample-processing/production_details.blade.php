<div class="row">
    <div id="sampleProductionTable" class="col-sm-10 col-sm-offset-1 m-t">
        <table>
            <thead>
                <tr>
                    <th colspan="10" class="text-center">PRODUCTION</th>
                </tr>
                <tr>
                    <th>Production Date</th>
                    <th>Total Man Power</th>
                    <th>Present Man Power</th>
                    <th>Total Sample Man</th>
                    <th>Present Sample Man</th>
                    <th>Per Person Avg</th>
                    <th>Sample Supervisor Name</th>
                    <th>Sample Handover to Merchant Date</th>
                    <th>Merchant Name</th>
                    <th>Common Remarks</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $production = $sampleProcessing->productions->first();
                @endphp
                <tr>
                    <td>
                        {{ $production->production_date ? \Carbon\Carbon::make($production->production_date)->toFormattedDateString() : null  }}
                    </td>
                    <td>{{ $production->details['total_man_power'] ?? null }}</td>
                    <td>{{ $production->details['present_man_power'] ?? null }}</td>
                    <td>{{ $production->details['total_sample_man'] ?? null }}</td>
                    <td>{{ $production->details['present_sample_man'] ?? null }}</td>
                    <td>{{ $production->details['per_person_avg'] ?? null }}</td>
                    <td>{{ $production->details['sample_supervisor_name'] ?? null }}</td>
                    <td>
                        {{ isset($production->details['sample_handover_date']) ? \Carbon\Carbon::make($production->details['sample_handover_date'])->toFormattedDateString() : null  }}
                    </td>
                    <td>{{ $production->dealingMerchant->screen_name ?? null }}</td>
                    <td>{{ $production->details['remarks'] ?? null }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="sampleProductionDetailsTable" class="col-sm-12 m-t">
        <table>
            <thead>
            <tr>
                <th colspan="15" class="text-center">PRODUCTION DETAILS</th>
            </tr>
            <tr>
                <th>Pattern Ready Date</th>
                <th>Cutting Finishing Date</th>
                <th>Print Date</th>
                <th>Embroidery Date</th>
                <th>Print/Embroidery Submission Date</th>
                <th>Sample Sewing Start Date</th>
                <th>Sample Sewing Line</th>
                <th>GMTS Color</th>
                <th>GMTS SIZE</th>
                <th>Sample QTY</th>
                <th>Sample Sewing Output Date</th>
                <th>Line Eff. %</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
                @if ($sampleProcessing->sampleProductionDetails)
                @foreach ($sampleProcessing->sampleProductionDetails as $value)
                <tr>
                    <td>
                        {{ isset($value->details['pattern_ready_date']) ? \Carbon\Carbon::make($value->details['pattern_ready_date'])->toFormattedDateString() : null  }}
                    </td>
                    <td>
                        {{ isset($value->details['cutting_finishing_date']) ? \Carbon\Carbon::make($value->details['cutting_finishing_date'])->toFormattedDateString() : null  }}
                    </td>
                    <td>
                        {{ isset($value->details['print_date']) ? \Carbon\Carbon::make($value->details['print_date'])->toFormattedDateString() : null  }}
                    </td>
                    <td>
                        {{ isset($value->details['embroidery_date']) ? \Carbon\Carbon::make($value->details['embroidery_date'])->toFormattedDateString() : null  }}
                    </td>
                    <td>
                        {{ isset($value->details['embroidery_submission_date']) ? \Carbon\Carbon::make($value->details['embroidery_submission_date'])->toFormattedDateString() : null  }}
                    </td>
                    <td>
                        {{ isset($value->details['sewing_start_date']) ? \Carbon\Carbon::make($value->details['sewing_start_date'])->toFormattedDateString() : null  }}
                    </td>
                    <td>{{ $value->sampleLine->line_no ?? null }}</td>
                    <td>{{ $value->color->name ?? null }}</td>
                    <td>{{ $value->size->name ?? null }}</td>
                    <td>{{ $value->details['sample_qty'] ?? null }}</td>
                    <td>
                        {{ isset($value->details['sample_sewing_output_date']) ? \Carbon\Carbon::make($value->details['sample_sewing_output_date'])->toFormattedDateString() : null  }}
                    </td>
                    <td>{{ $value->details['line_eff'] ?? null }}</td>
                    <td>{{ $value->details['remarks'] ?? null }}</td>
                </tr>
                @endforeach
                <tr>
                    <th colspan="9" class="text-right">Total</th>
                    @php
                       $mainData = collect($sampleProcessing->productions)->first();
                    @endphp
                    <td>{{ $mainData->total_calculation['in_total_sample_qty'] ? number_format($mainData->total_calculation['in_total_sample_qty'], 2) : null }}</td>
                </tr>
                @else
                <tr>
                    <td colspan="20" class="text-center">No Data Found</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
