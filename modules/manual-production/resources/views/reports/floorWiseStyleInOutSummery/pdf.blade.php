@extends('manual-production::reports.layout')
@section('title', 'Floor Wise In Out Summery')
@section('content')
    <div>
        @php $totalInput = collect($data)->sum('input_qty'); $totalOutput= collect($data)->sum('sewing_output_qty'); @endphp
        <table class="reportTable">
            <thead>
            <tr>
                <th>Factory</th>
                <th>Buyer</th>
                <th>Style</th>
                <th>Color</th>
                <th>First Input Date</th>
                <th>Last Input Date</th>
                <th>Unit</th>
                <th>Input Qty.</th>
                <th>Output Qty.</th>
                <th>Balance Qty.</th>
            </tr>
            </thead>
            <tbody>
            @forelse(collect($data)->groupBy('color_id') as $colorWiseData)
                @forelse(collect($colorWiseData)->groupBy('floor_id') as $item)
                    <tr>
                        @php $input = collect($item)->sum('input_qty'); $output = collect($item)->sum('sewing_output_qty') @endphp
                        <td>{{ collect($item)->first()['factory']->factory_name }}</td>
                        <td>{{ collect($item)->first()['buyer']->name }}</td>
                        <td>{{ collect($item)->first()['order']->style_name }}</td>
                        <td>{{ collect($item)->first()['color']->name }}</td>
                        <td>{{ collect($item)->sortBy('production_date')->first()['production_date'] }}</td>
                        <td>{{ collect($item)->sortByDesc('production_date')->first()['production_date'] }}</td>
                        <td>{{ collect($item)->first()['floor']['floor_no'] ?? 'N/A' }}</td>
                        <td>{{ $input }}</td>
                        <td>{{ $output }}</td>
                        <td>{{ $input - $output }}</td>
                    </tr>
                @empty
                    <tr>
                        <th colspan="10">No Data Found</th>
                    </tr>
                @endforelse
            @empty
                <tr>
                    <th colspan="10">No Data Found</th>
                </tr>
            @endforelse
            <tr>
                <th colspan="7">Total</th>
                <td>{{ $totalInput }}</td>
                <td>{{ $totalOutput }}</td>
                <td>{{ $totalInput - $totalOutput }}</td>
            </tr>
            </tbody>
        </table>
        <table class="reportTable">
            <thead>
            <tr>
                <th>Unit</th>
                <th>Input Qty.</th>
                <th>Output Qty.</th>
                <th>Balance Qty.</th>
            </tr>
            </thead>
            <tbody>
            @forelse(collect($data)->groupBy('floor_id') as $item)
                <tr>
                    @php $input = collect($item)->sum('input_qty'); $output = collect($item)->sum('sewing_output_qty') @endphp
                    <td>{{ collect($item)->first()['floor']['floor_no'] ?? 'N/A' }}</td>
                    <td>{{ $input }}</td>
                    <td>{{ $output }}</td>
                    <td>{{ $input - $output }}</td>
                </tr>
            @empty
                <tr>
                    <th colspan="4">No Data Found</th>
                </tr>
            @endforelse
            <tr>
                <th>Total</th>
                <td>{{ $totalInput }}</td>
                <td>{{ $totalOutput }}</td>
                <td>{{ $totalInput - $totalOutput }}</td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection
