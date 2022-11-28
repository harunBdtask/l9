<table class="reportTable">

    <thead>
    <tr>
        <th><b>Date</b></th>
        @foreach ($receiveFloors as $receiveFloor)
            <th><b>{{ $receiveFloor->floor_no }}</b></th>
        @endforeach
        <th><b>Total Received</b></th>
        @foreach ($finishingFloors as $finishingFloor)
            <th><b>{{ $finishingFloor->name }}</b></th>
        @endforeach
        <th><b>Total Finishing</b></th>
        <th><b>Balance</b></th>
        <th><b>Remarks</b></th>
    </tr>
    </thead>

    <tbody>
    @foreach ($reportData as $key => $data)
        <tr>
            <td>{{ Carbon\Carbon::make($key)->toFormattedDateString() }}</td>
            @foreach ($receiveFloors as $receiveFloor)
                <td class="text-right"> {{ $data[$receiveFloor->floor_no] }} </td>
            @endforeach
            <td class="text-right">{{ $data['total_sewing_output'] }}</td>
            @foreach ($finishingFloors as $finishingFloor)
                <td class="text-right"> {{ $data[$finishingFloor->name] }} </td>
            @endforeach
            <td class="text-right">{{ $data['total_finishing'] }}</td>
            <td class="text-right">{{ $data['total_sewing_output'] - $data['total_finishing'] }}</td>
            <td></td>
        </tr>
    @endforeach
    <tr style="background-color: gainsboro;">
        <td><b>Total</b></td>
        @foreach ($receiveFloors as $receiveFloor)
            <td class="text-right">
                <b>{{ isset($receiveFloor['floor_no']) ? collect($reportData)->pluck($receiveFloor['floor_no'])->sum() : 0 }}</b>
            </td>
        @endforeach
        <td class="text-right"><b>{{ collect($reportData)->sum('total_sewing_output') }}</b></td>
        @foreach ($finishingFloors as $finishingFloor)
            <td class="text-right">
                <b>{{ isset($finishingFloor['name']) ? collect($reportData)->pluck($finishingFloor['name'])->sum() : 0 }}</b>
            </td>
        @endforeach
        <td class="text-right"><b>{{ collect($reportData)->sum('total_finishing') }}</b></td>
        <td class="text-right">
            <b>{{ collect($reportData)->sum('total_sewing_output') - collect($reportData)->sum('total_finishing') }}</b>
        </td>
        <td></td>
    </tr>
    </tbody>


</table>
