@extends('skeleton::layout')
@section('title','WIP Reports')
@section('content')
    <div class="padding">
        <div class="box" style="min-height: 610px">
            <div class="box-header btn-info">
                <h2>
                    Weekly WIP List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{ url('/wip/create') }}" class="btn btn-info"><i class="fa fa-plus"></i> New WIP</a>
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
                                <th>Assign Factory</th>
                                <th>Style</th>
                                <th>Order Qty</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($wipData as $key => $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->factory->name ?? '' }}</td>
                                    <td>{{ $item->style ?? '' }}</td>
                                    <td>{{ $item->order_qty ?? 0 }}</td>

                                    <td style="padding: 0.2%;">
{{--                                                                                @buyerPermission($order->buyer->id)--}}
                                        <a class="btn btn-xs btn-warning" title="Edit Costing"
                                           href="{{ url('/wip/' . $item->id.'/edit' ) }}"><i
                                                    class="fa fa-edit"></i></a>
                                        <a class="btn btn-xs btn-success" title="Edit Costing"
                                           href="{{ url('/wip/' . $item->id.'/view' ) }}"><i
                                                class="fa fa-eye"></i></a>

                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Order"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('wip/'.$item->id) }}">
                                            <i class="fa fa-times"></i>
                                        </button>
{{--                                                                                @endbuyerPermission--}}
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
{{--                        {{ $preCostings->links() }}--}}
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
