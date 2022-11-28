
<table class="reportTable">
    <thead>
    <tr>
        <td style="text-align: center;height: 50px;font-size: 15px;" colspan="17"> <b>Daily Finish Fabric Delivery Status Report</b> </td>
    </tr>
    <tr>
        <th style="background-color: aliceblue;">SL No</th>
        <th style="background-color: aliceblue;">Delivery Unique ID</th>
        <th style="background-color: aliceblue;">DLV Challan No</th>
        <th style="background-color: aliceblue;">DLV Date</th>
        <th style="background-color: aliceblue;">Party / Supplier Name</th>
        <th style="background-color: aliceblue;">Store Name</th>
        <th style="background-color: aliceblue;">Buyer</th>
        <th style="background-color: aliceblue;">Style/Order No</th>
        <th style="background-color: aliceblue;">PO NO</th>
        <th style="background-color: aliceblue;">Batch No</th>
        <th style="background-color: aliceblue;">F/Type</th>
        <th style="background-color: aliceblue;">Color</th>
        <th style="background-color: aliceblue;">DIA</th>
        <th style="background-color: aliceblue;">GSM</th>
        <th style="background-color: aliceblue;">No Of Roll</th>
        <th style="background-color: aliceblue;">DLV FIN QTY(KG)</th>
        <th style="background-color: aliceblue;">Remarks</th>
    </tr>
    </thead>
    <tbody>
    @foreach($fabricIssues as $issue)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $issue['delivery_unique_id'] }}</td>
            <td>{{ $issue['challan_no'] }}</td>
            <td>{{ $issue['issue_date'] }}</td>
            <td>{{ $issue['supplier_name'] }}</td>
            <td>{{ $issue['store_name'] }}</td>
            <td>{{ $issue['buyer_name'] }}</td>
            <td>{{ $issue['style_no'] }}</td>
            <td style="word-break:break-word; width: 15%">{{ $issue['po_no'] }}</td>
            <td>{{ $issue['batch_no'] }}</td>
            <td>{{ $issue['feb_type'] }}</td>
            <td>{{ $issue['color'] }}</td>
            <td>{{ $issue['dia'] }}</td>
            <td>{{ $issue['gsm'] }}</td>
            <td>{{ $issue['no_of_roll'] }}</td>
            <td>{{ $issue['dlv_fin_qty'] }}</td>
            <td>{{ $issue['remarks'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
