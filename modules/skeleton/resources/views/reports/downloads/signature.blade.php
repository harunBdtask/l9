<style>
    .signature {
        left: 0;
        bottom: 0;
        height: 30px;
        width: 100%;
    }
</style>
<div style="height: 60px"></div>
<div class="signature">
    @if(isset($signature['signature_type']) && $signature['signature_type'] == 'approval')
        @include('skeleton::reports.downloads.approval_signature')
    @elseif(isset($signature['signature_type']) && $signature['signature_type'] == 'gatepass-approval')
        @include('skeleton::reports.downloads.gatepass_approval_signature')
    @else
        @include('skeleton::reports.downloads.report_signature')
    @endif
</div>
