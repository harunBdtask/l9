<center>
    <table style="border: 1px solid black;width: 20%;">
        <thead>
        <tr>
            <td class="text-center">
                <span style="font-size: 10pt; font-weight: bold;">Daily Finish Fabric Delivery Status Report</span>
                <br>
            </td>
        </tr>
        </thead>
    </table>
</center>
<br>
<div class="row p-x-1">
    <div class="col-md-12" id="finishFabricIssueTable">
        @include('inventory::reports.finish_fabric_issue_report.table');
    </div>
</div>