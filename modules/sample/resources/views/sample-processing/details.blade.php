<div class="row">
    <center>
        <table style="border: 1px solid black;width: 25%;">
            <thead>
            <tr>
                <td class="text-center">
                    <span style="font-size: 12pt; font-weight: bold;" class="text-center text-uppercase">Sample Processing</span>
                    <br>
                </td>
            </tr>
            </thead>
        </table>
    </center>
</div>
<br>
@includeIf('sample::sample-processing.details_info')
@if ($sampleProcessing->productions && collect($sampleProcessing->sampleProductionDetails)->isNotEmpty())
<br>
@includeIf('sample::sample-processing.production_details')
@endif
</div>
<br>
<br>
@include('skeleton::reports.downloads.signature')
