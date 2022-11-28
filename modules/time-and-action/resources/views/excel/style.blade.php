@foreach(collect($data)->groupBy('order_id') as $orderGroup)
    @php $groupWiseTask = collect($orderGroup)->groupBy('task.group_id')  @endphp
    <table class="reportTable text-nowrap">
        <thead style="text-align: center">
        <tr>
            <th colspan="8">Style Wise</th>
            @foreach($groupWiseTask as $groupGroup)
                <th colspan="{{ count($groupGroup) * 2 }}">{{ collect($groupGroup)->first()->task->group->name }}</th>
            @endforeach
        </tr>
        <tr>
            <th rowspan="2">Merchant Name</th>
            <th rowspan="2">Buyer Name</th>
            <th rowspan="2">PO Number</th>
            <th rowspan="2">PO Qty.</th>
            <th rowspan="2">Style Name</th>
            <th rowspan="2">Uniq ID</th>
            <th rowspan="2">Shipment Date</th>
            <th rowspan="2">Status</th>
            @foreach($groupWiseTask as $groupTask)
                @foreach($groupTask as $task)
                    <th colspan="2">{{ $task->task->task_short_name }}</th>
                @endforeach
            @endforeach
        </tr>
        <tr>
            @foreach($groupWiseTask as $groupTask)
                @foreach($groupTask as $task)
                    <th>Start Date</th>
                    <th>Finish Date</th>
                @endforeach
            @endforeach
        </tr>
        </thead>
        <tbody>
        <tr>
            <td rowspan="5">
                {{ collect($orderGroup)->first()->order->dealingMerchant->full_name ?? '' }}
            </td>
            <td rowspan="5">
                {{ collect($orderGroup)->first()->order->buyer->name ?? '' }}
            </td>
            <td rowspan="5">
                {{ collect($orderGroup)->first()->order->po_no ?? '' }}
            </td>
            <td rowspan="5">
                {{ collect($orderGroup)->first()->order->pq_qty_sum ?? '' }}
            </td>
            <td rowspan="5">
                {{ collect($orderGroup)->first()->order->style_name ?? '' }}
            </td>
            <td rowspan="5">
                {{ collect($orderGroup)->first()->order->job_no ?? '' }}
            </td>
            <td rowspan="5">
                {{ collect($orderGroup)->first()->order->shipment_date ?? '' }}
            </td>
            <th>Plan Date</th>
            @foreach($groupWiseTask as $groupTask)
                @foreach($groupTask as $task)
                    <td>{{ date_format(date_create($task['start_date']), 'd-M-Y') }}</td>
                    <td>{{ date_format(date_create($task['finish_date']), 'd-M-Y') }}</td>
                @endforeach
            @endforeach
        </tr>
        <tr>
            <th>Actual Date</th>
            @foreach($groupWiseTask as $groupTask)
                @foreach($groupTask as $task)
                    <td>{{ date_format(date_create($task['actual_start_date']), 'd-M-Y') }}</td>
                    <td>{{ date_format(date_create($task['actual_finish_date']), 'd-M-Y') }}</td>
                @endforeach
            @endforeach
        </tr>
        <tr>
            <th>Early</th>
            @foreach($groupWiseTask as $groupTask)
                @foreach($groupTask as $task)
                    <td>{{ $task['early_start'] }}</td>
                    <td>{{ $task['early_finish'] }}</td>
                @endforeach
            @endforeach
        </tr>
        <tr>
            <th>Delay</th>
            @foreach($groupWiseTask as $groupTask)
                @foreach($groupTask as $task)
                    <td>{{ $task['delay_start'] }}</td>
                    <td>{{ $task['delay_finish'] }}</td>
                @endforeach
            @endforeach
        </tr>
        <tr>
            <th>Comments</th>
            @foreach($groupWiseTask as $groupTask)
                @foreach($groupTask as $task)
                    <td>{{ $task['comment_start'] }}</td>
                    <td>{{ $task['comment_finish'] }}</td>
                @endforeach
            @endforeach
        </tr>
        </tbody>
    </table>
@endforeach
