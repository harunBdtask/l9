@extends('skeleton::layout')
@section('title','Pre Costing')
@section('content')
    <div class="padding">
        <div class="box" style="min-height: 610px">
            <div class="box-header btn-info">
                <h2>
                    Pre Costing List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{ url('/pre-costing/create') }}" class="btn btn-info"><i class="fa fa-plus"></i> New
                            Pre Costing</a>
                    </div>
                    {{--                    <div class="col-sm-4 col-sm-offset-2">--}}
                    {{--                        <form action="{{ url('orders/search') }}" method="GET">--}}
                    {{--                            <div class="input-group">--}}
                    {{--                                <input type="text" class="form-control" name="search"--}}
                    {{--                                       value="{{ $search ?? '' }}" placeholder="Search">--}}
                    {{--                                <span class="input-group-btn">--}}
                    {{--                                            <button class="btn btn-info" type="submit">Search</button>--}}
                    {{--                                        </span>--}}
                    {{--                            </div>--}}
                    {{--                        </form>--}}
                    {{--                    </div>--}}
                </div>
                @include('partials.response-message')
                <div class="row m-t">
                    <div class="col-sm-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Buyer</th>
                                <th>Style</th>
                                <th>Season</th>
                                <th>Item</th>
                                <th>Create Date</th>
                                <th>Revise Date</th>
                                <th>Costing Status</th>
                                <th>File</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($preCostings as $key => $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->buyer->name ?? '' }}</td>
                                    <td>{{ $item->style }}</td>
                                    <td>{{ $item->season->season_name }}</td>
                                    <td>{{ $item->item->name ?? '' }}</td>
                                    <td>{{ $item->create_date ?? '' }}</td>
                                    <td>{{ $item->revise_date ?? '' }}</td>
                                    <td>{{ $item->costing_status ?? '' }} </td>
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
                                    <td style="padding: 0.2%;">
                                        {{--                                        @buyerPermission($order->buyer->id)--}}
                                        <a class="btn btn-xs btn-info" title="Show Costing" target="__blank"
                                           href="{{ url('/pre-costing-report?factory_id=' . $item->factory_id ) . '&buyer_id=' . $item->buyer_id . '&season_id='
                                        . $item->season_id . '&style_name=' . $item->style  . '&item_id=' . $item->item_id . '&from_date=' . $item->create_date . '&to_date=' . $item->create_date }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a class="btn btn-xs btn-warning" title="Edit Costing"
                                           href="{{ url('/pre-costing/' . $item->id.'/edit' ) }}"><i
                                                    class="fa fa-edit"></i></a>

                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Order"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('pre-costing/'.$item->id) }}">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        {{--                                        @endbuyerPermission--}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center p-a" colspan="10">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $preCostings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')
    <script>
        $(document).on('click', '#priceQuotationCopy', function () {

        })
    </script>
@endpush
