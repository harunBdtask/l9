<div class="row">
    <div style="width: 50%">
        <table class="borderless">
            <tbody>
            <tr>
                <td><b>Buyer Name :</b></td>
                <td>{{ $buyerName ?? '' }}</td>
            </tr>
            <tr>
                <td><b>From Date :</b></td>
                <td>{{ request('from_date') }}</td>
            </tr>
            <tr>
                <td><b>To Date :</b></td>
                <td>{{ request('to_date') }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<table class="reportTable m-t-1">
    <thead>
    <tr style="background-color: aliceblue">
        <th>PI Number</th>
        <th>PI Date</th>
        <th>PI Value</th>
        <th>Work Order Number</th>
        <th>Work Order Date</th>
        <th>Style</th>
        <th>Work Order Value</th>
        <th>L/C Number</th>
        <th>L/C Date</th>
    </tr>
    </thead>

    <tbody>
    @forelse($reportData as $data)
        @foreach($data['details'] as $detail)
            <tr>
                @if($loop->first)
                    <td rowspan="{{count($data['details'])}}">{{ $data['pi_no'] }}</td>
                    <td rowspan="{{count($data['details'])}}">{{ $data['pi_created_date'] }}</td>
                    <td rowspan="{{count($data['details'])}}">{{ number_format($data['pi_value'], 2) }}</td>
                @endif
                <td>{{ $detail['wo_no'] }}</td>
                <td>{{ $detail['wo_date'] }}</td>
                <td>{{ $detail['style_name'] }}</td>
                <td>${{ number_format($detail['wo_value'], 2) }}</td>
                @if($loop->first)
                    <td rowspan="{{count($data['details'])}}">{{ $data['lc_no'] }}</td>
                    <td rowspan="{{count($data['details'])}}">{{ $data['lc_date'] }}</td>
                @endif
            </tr>
        @endforeach
    @empty
        <tr style="text-align: center;">
            <td colspan="9">No data found</td>
        </tr>
    @endforelse
    </tbody>
</table>
