@extends('dyeing::layout')
@section("title","Brush / Peach")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Brush / Peaches</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('dyeing/peaches/create') }}"
                           class="btn btn-sm btn-info m-b">
                            <em class="fa fa-plus"></em>&nbsp;Create
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Type</th>
                                <th>Production Date</th>
                                <th>Factory</th>
                                <th>Party</th>
                                <th>Order No</th>
                                <th>Batch No</th>
                                <th>Shift</th>
                                <th>Colors</th>
                                <th>Total Order Qty</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($peaches as $peach)
                                @php
                                    $colors = collect($peach->peachDetails)->pluck('color')->pluck('name')->join(', ');
                                    $totalOrderQty = collect($peach->peachDetails)->sum('order_qty');
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $peach->entry_basis_value }}</td>
                                    <td>{{ $peach->production_date }}</td>
                                    <td>{{ $peach->factory->factory_name }}</td>
                                    <td>{{ $peach->buyer->name }}</td>
                                    <td>{{ $peach->textile_order_no ?? 'N\A' }}</td>
                                    <td>{{ $peach->dyeing_batch_no ?? 'N\A' }}</td>
                                    <td>{{ $peach->shift->shift_name }}</td>
                                    <td>{{ $colors }}</td>
                                    <td>{{ $totalOrderQty }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-info" type="button"
                                           href="{{ url('dyeing/peaches/create?id='. $peach->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        {{--                                        <a class="btn btn-success btn-xs" type="button"--}}
                                        {{--                                           href="{{ url('dyeing/peaches/view/'. $peach->id) }}">--}}
                                        {{--                                            <em class="fa fa-eye"></em>--}}
                                        {{--                                        </a>--}}
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Order"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('dyeing/peaches/'. $peach->id) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" align="center" class="text-danger">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $peaches->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /*.custom-field {*/
        /*    */
        /*    */
        /*}*/
    </style>
@endsection
@section('scripts')

@endsection
