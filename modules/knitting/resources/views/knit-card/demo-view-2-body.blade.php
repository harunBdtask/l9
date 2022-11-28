<table class="borderless" style="width: 100%; ">
    @php
        $yarnDetailsCollection = collect($data->yarnDetails);
    @endphp
    <tr style="text-align: left;">
        <td style="width: 20%;">Machine Dia & GG</td>
        <td style="width: 5%; text-align: right;"> :</td>
        <td style="width: 35%;">
            <div style="border-bottom: 1px dashed #b7b7b7; width: 100%; margin-top: 5px;">
                &nbsp;
                {{ $data->program_dia }} &
                {{ $data->program_gg }}
            </div>
        </td>
        <td style="text-align: right; width: 10%;">
            <span style="padding-top:15px;">Finish/Dia :</span>
        </td>
        <td style="width: 30%;">
            <div style="border-bottom: 1px dashed #b7b7b7; width: 100%; margin-top: 5px;">
                &nbsp;
                {{ $data->program->finish_fabric_dia ?? '' }}
            </div>
        </td>
    </tr>
    <tr style="text-align: left;">
        <td>Yarn Count</td>
        <td style="text-align: right;"> :</td>
        <td colspan="3">
            <div style="border-bottom: 1px dashed #b7b7b7; width: 100%; margin-top: 5px;">
                &nbsp;
                {{ $yarnDetailsCollection->pluck('yarn_count.yarn_count')->unique()->values()->join(', ') }}
            </div>
        </td>
    </tr>
    <tr style="text-align: left;">
        <td>Yarn Type</td>
        <td style="text-align: right;"> :</td>
        <td colspan="3">
            <div style="border-bottom: 1px dashed #b7b7b7; width: 100%; margin-top: 5px;">
                &nbsp;
                {{ $yarnDetailsCollection->pluck('yarn_type.name')->unique()->values()->join(', ') }}
            </div>
        </td>
    </tr>
    <tr style="text-align: left;">
        <td>Brand/Lot No.</td>
        <td style="text-align: right;"> :</td>
        <td colspan="3">
            <div style="border-bottom: 1px dashed #b7b7b7; width: 100%; margin-top: 5px;">
                &nbsp;
                {{ $yarnDetailsCollection->pluck('yarn_brand')->unique()->values()->join(', ') }} /
                {{ $yarnDetailsCollection->pluck('yarn_lot')->unique()->values()->join(', ') }}
            </div>
        </td>
    </tr>
    <tr style="text-align: left;">
        <td>Grey GSM/S</td>
        <td style="text-align: right;"> :</td>
        <td colspan="3">
            <div style="border-bottom: 1px dashed #b7b7b7; width: 100%; margin-top: 5px;">
                &nbsp;
            </div>
        </td>
    </tr>
    <tr style="text-align: left;">
        <td>Finish GSM</td>
        <td style="text-align: right;"> :</td>
        <td colspan="3">
            <div style="border-bottom: 1px dashed #b7b7b7; width: 100%; margin-top: 5px;">
                &nbsp;
                {{ $data->gsm }}
            </div>
        </td>
    </tr>
    <tr style="text-align: left;">
        <td>Buyer</td>
        <td style="text-align: right;"> :</td>
        <td colspan="3">
            <div style="border-bottom: 1px dashed #b7b7b7; width: 100%; margin-top: 5px;">
                &nbsp;
                {{ $data->buyer->name ?? '' }}
            </div>
        </td>
    </tr>
    <tr style="text-align: left;">
        <td>Order No</td>
        <td style="text-align: right;"> :</td>
        <td colspan="3">
            <div style="border-bottom: 1px dashed #b7b7b7; width: 100%; margin-top: 5px;">
                &nbsp;
                {{ $data->program->booking_no ?? '' }}
            </div>
        </td>
    </tr>
    <tr style="text-align: left;">
        <td>Color</td>
        <td style="text-align: right;"> :</td>
        <td colspan="3">
            <div style="border-bottom: 1px dashed #b7b7b7; width: 100%; margin-top: 5px;">
                &nbsp;
                {{ $data->color }}
            </div>
        </td>
    </tr>
    <tr style="text-align: left;">
        <td>Fabric Type</td>
        <td style="text-align: right;"> :</td>
        <td colspan="3">
            <div style="border-bottom: 1px dashed #b7b7b7; width: 100%; margin-top: 5px;">
                &nbsp;
                {{ $data->fabric_type }}
            </div>
        </td>
    </tr>
    <tr style="text-align: left;">
        <td>P. Qty</td>
        <td style="text-align: right;"> :</td>
        <td colspan="3">
            <div style="border-bottom: 1px dashed #b7b7b7; width: 100%; margin-top: 5px;">
                &nbsp;
                {{ $data->assign_qty }}
            </div>
        </td>
    </tr>
    <tr style="text-align: left;">
        <td>Balance</td>
        <td style="text-align: right;"> :</td>
        <td colspan="3">
            <div style="border-bottom: 1px dashed #b7b7b7; width: 100%; margin-top: 5px;">
                &nbsp;
                {{ (double)($data->program->program_qty ?? 0) - (double)$data->assign_qty }}
            </div>
        </td>
    </tr>
</table>
