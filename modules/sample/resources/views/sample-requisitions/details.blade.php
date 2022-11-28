<div class="row">
    <center>
        <table style="border: 1px solid black;width: 25%;">
            <thead>
            <tr>
                <td class="text-center">
                    <span style="font-size: 12pt; font-weight: bold;" class="text-center text-uppercase">Sample View</span>
                    <br>
                </td>
            </tr>
            </thead>
        </table>
    </center>
    <br>
    @includeIf('sample::sample-requisitions.requisition_details')
    @if ($sampleOrderRequisition->fabricMain && collect($sampleOrderRequisition->fabricDetails)->isNotEmpty())
    <br>
    @includeIf('sample::sample-requisitions.fabric_details')
    @endif
    @if (collect($sampleOrderRequisition->accessories)->isNotEmpty() && $sampleOrderRequisition->viewType !="withoutAccessories")
    <br>
    @includeIf('sample::sample-requisitions.accessories_details')
    @endif
</div>
<br>
<br>
@include('skeleton::reports.downloads.signature')
