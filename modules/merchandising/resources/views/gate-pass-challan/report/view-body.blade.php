<div class="body-section">
    <div style="display: flex; justify-content: center; align-items: center;">
        <div>
            @if(factoryImage())
                <img class="factory-image"
                    src="/storage/factory_image/{{ factoryImage() }}"
                    style="height: auto; object-fit: contain; width: 200px; padding-bottom: 10px;"
                    alt="..">
            @endif
        </div>
    </div>
    <div style="margin-top: 10px; border: 1px solid #1E1E1E; display: flex; justify-content: space-between">
        <div style="padding-left: 10px;">
            <p style="font-weight: 500; font-size: 18px;">CONSIGNEE/FROM: </p>
            <span style="font-size: 24px; font-weight: bold; text-decoration: underline;">{{ $data['factory']['factory_name'] ?? '' }}</span><br>
            <span style="font-size: 16px;">{{ $data['factory']['factory_address'] ?? '' }}</span>
        </div>
        <div>
            <p style="font-weight: 500; font-size: 18px;">BENEFICIARY/TO: </p>
            <span style="font-size: 24px; font-weight: bold; text-decoration: underline;">{{ $data['party']['name'] ?? '' }}</span><br>
            <span>Attn: {{ $data['party_attn'] ?? '' }}</span><br>
            <span>Contact: {{ $data['party_contact_no'] ?? '' }}</span><br>
            <span>{{ $data['supplier_address'] ?? '' }}</span><br>
        </div>
        <div style="padding-top: 50px; padding-right:20px; ">
            <span><?php echo DNS1D::getBarcodeSVG($data['barcode'] ?? '', "C128A", 1.9, 40, '', false); ?></span>
        </div>
    </div>

    @include('merchandising::gate-pass-challan.report.table')

    <div class="m-t-2">
        <p><strong>Remarks: </strong>{{ $data['remarks'] }}</p>
    </div>

    @include('skeleton::reports.downloads.signature')

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
