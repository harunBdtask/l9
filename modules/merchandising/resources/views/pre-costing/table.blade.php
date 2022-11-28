{{--@if(count($pos) == 1)--}}
@foreach(collect($preCostings)->groupBy('buyer_id') as $buyerId => $details)
    <table class="reportTable" style="width: 100%">
        <thead>
        <tr>
            <th>Company</th>
            <th>Buyer</th>
            <th>Season</th>
            <th>Style</th>
            <th>Item</th>
            <th>Customer</th>
            <th>Create Date</th>
            <th>Revise Date</th>
            <th>Costing Status</th>
            @if($type == 'view')
                <th>Files</th>
{{--                <th>Costing File</th>--}}
            @endif
        </tr>
        </thead>
        @foreach(collect($details) as $index => $item)
            <tr>
                <td>{{ $item->factory->factory_name ?? '' }}</td>
                <td>{{ $item->buyer->name ?? '' }}</td>
                <td>{{ $item->season->season_name }}</td>
                <td>{{ $item->style }}</td>
                <td>{{ $item->item->name ?? '' }}</td>
                <td>{{ $item->customer ?? '' }}</td>
                <td>{{ $item->create_date ?? '' }}</td>
                <td>{{ $item->revise_date ?? '' }}</td>
                <td>{{ $item->costing_status ?? '' }} </td>
                @if($type == 'view')
{{--                    <td>--}}
{{--                        @if($item->tp_file)--}}
{{--                            <a href="/storage/{{$item->tp_file}}" target="_blank"><i class="fa fa-eye"></i></a>--}}
{{--                        @endif--}}
{{--                    </td>--}}
{{--                    <td>--}}
{{--                        @if($item->costing_file)--}}
{{--                            <a href="/storage/{{$item->costing_file}}" target="_blank"><i class="fa fa-eye"></i></a>--}}
{{--                        @endif--}}
{{--                    </td>--}}
                    <td>
                        <div class="dropdown show">
                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                               id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                               aria-expanded="false">
                                Files
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                @if($item->tp_file)
                                    <a class="dropdown-item" target="__blank"
                                       href="{{ asset('storage/'.$item->tp_file) }}">{{ $item->tp_file ? 'TP-1' : 'N/A' }}
                                    </a>
                                @endif
                                @if($item->tp_file_2)
                                    <a class="dropdown-item" target="__blank"
                                       href="{{ asset('storage/'.$item->tp_file_2) }}" > {{ $item->tp_file_2 ? 'TP-2' : 'N/A' }}
                                    </a>
                                @endif
                                @if($item->tp_file_3)
                                    <a class="dropdown-item" target="__blank"
                                       href="{{ asset('storage/'.$item->tp_file_3) }}">{{ $item->tp_file_3 ? 'TP-3' : 'N/A' }}</a>
                                @endif
                                @if($item->costing_file)
                                    <a class="dropdown-item" target="__blank"
                                       href="{{ asset('storage/'.$item->costing_file) }}">{{ $item->costing_file ? 'Costing-1' : 'N/A' }}</a>
                                @endif
                                @if($item->costing_file_2)
                                    <a class="dropdown-item" target="__blank"
                                       href="{{ asset('storage/'.$item->costing_file_2) }}">{{ $item->costing_file_2 ? 'Costing-2' : 'N/A' }}</a>
                                @endif
                                @if($item->costing_file_3)
                                    <a class="dropdown-item" target="__blank"
                                       href="{{ asset('storage/'.$item->costing_file_3) }}">{{ $item->costing_file_3 ? 'Costing-3' : 'N/A' }}</a>
                                @endif

                            </div>
                        </div>

                    </td>

                @endif
            </tr>
        @endforeach
        <tbody>
        </tbody>
    </table>
    <br>
@endforeach

{{--@endif--}}


