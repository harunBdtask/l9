<style>
    .group-name {
        background-color: #5897fb52;
        color: black;
        font-weight: bold;
    }

    .artwork {
        border-radius: 1em;
    }
</style>

<div>
    <table class="reportTable">
        <thead>
        <tr>
            <th scope="col">Merchant Name</th>
            <th scope="col">Buyer Name</th>
            <th scope="col">PO Number</th>
            <th scope="col">PO Qty.</th>
            <th scope="col">Style Name</th>
            <th scope="col">Uniq ID</th>
            <th scope="col">Shipment Date</th>
            <th scope="col">Image</th>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td>{{ $order->dealingMerchant->screen_name }}</td>
            <td>{{ array_get($buyer, 'name') }}</td>
            <td>{{ $order->purchaseOrders->pluck('po_no')->implode(', ') }}</td>
            <td>{{ $order->purchaseOrders->sum('po_quantity') ?? '' }}</td>
            <td>{{ $order->style_name }}</td>
            <td>{{ $order->job_no }}</td>
            <td>{{ $order->shipment_date }}</td>
            <td class="text-center">
                @if($order->images)
                    <img
                            width="100px"
                            class="artwork"
                            alt="{{ $order->job_no }}"
                            src="{{ public_path("storage/$order->images") }}"
                    >
                @endif
            </td>
        </tr>
        </tbody>
    </table>
</div>


<br>
<br>


<div>
    <table class="reportTable">

        <thead>
        <tr>
            <th scope="col">TNA Task Name</th>
            <th scope="col">Plan Start Date</th>
            <th scope="col">Plan Finish Date</th>
            <th scope="col">Actual Start Date</th>
            <th scope="col">Actual Finish Date</th>
            <th scope="col">Delay</th>
            <th scope="col">Early</th>
            <th scope="col">Notice Before</th>
        </tr>
        </thead>


        <tbody>
        @foreach($reports as $groupName => $group)

            <tr>
                <td colspan="8" class="text-center group-name">{{ $groupName }}</td>
            </tr>

            @foreach($group as $report)
                <tr>
                    <td>{{ array_get($report, 'task.task_short_name') }}</td>
                    <td>{{ array_get($report, 'start_date') }}</td>
                    <td>{{ array_get($report, 'finish_date') }}</td>
                    <td>{{ array_get($report, 'actual_start_date') }}</td>
                    <td>{{ array_get($report, 'actual_end_date') }}</td>
                    <td>{{ array_get($report, 'delay') }}</td>
                    <td>{{ array_get($report, 'early') }}</td>
                    <td>{{ array_get($report, 'notice_before') }}</td>
                </tr>
            @endforeach

        @endforeach
        </tbody>

    </table>
</div>