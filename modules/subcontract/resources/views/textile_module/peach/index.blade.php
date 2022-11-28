@extends('subcontract::layout')
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
                        <a href="{{ url('subcontract/peaches/create') }}"
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
                                <th>M/C Name</th>
                                <th>Fin. QTY</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($subDyeingPeaches as $peach)
                                @php
                                    $colors = collect($peach->peachDetails)->pluck('color')->pluck('name')->join(', ');
                                    $totalOrderQty = collect($peach->peachDetails)->sum('order_qty');
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $peach->entry_basis_value }}</td>
                                    <td>{{ $peach->production_date }}</td>
                                    <td>{{ $peach->factory->factory_name }}</td>
                                    <td>{{ $peach->supplier->name }}</td>
                                    <td>{{ $peach->sub_textile_order_no ?? 'N\A' }}</td>
                                    <td>{{ $peach->sub_dyeing_batch_no ?? 'N\A' }}</td>
                                    <td>{{ $peach->shift->shift_name }}</td>
                                    <td>{{ $colors }}</td>
                                    <td>{{ $totalOrderQty }}</td>
                                    <td>{{ $peach->machine->name }}</td>
                                    <td>{{ $peach->peachDetails->pluck('finish_qty')->join(', ') }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-info" type="button"
                                           href="{{ url('subcontract/peaches/create?id='. $peach->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        {{--                                        <a class="btn btn-success btn-xs" type="button"--}}
                                        {{--                                           href="{{ url('subcontract/peaches/view/'. $peach->id) }}">--}}
                                        {{--                                            <em class="fa fa-eye"></em>--}}
                                        {{--                                        </a>--}}
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Order"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('subcontract/peaches/'. $peach->id) }}">
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
                            {{ $subDyeingPeaches->appends(request()->except('page'))->links() }}
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
    {{--    <script>--}}
    {{--        $(document).ready(function () {--}}
    {{--            $('#party_id').select2({--}}
    {{--                ajax: {--}}
    {{--                    url: "/subcontract/api/v1/textile-parties/select-search",--}}
    {{--                    dataType: 'json',--}}
    {{--                    data: function (params) {--}}
    {{--                        return {--}}
    {{--                            search: params.term--}}
    {{--                        };--}}
    {{--                    },--}}
    {{--                    processResults: function (response) {--}}
    {{--                        return {--}}
    {{--                            results: response.data,--}}
    {{--                            pagination: {--}}
    {{--                                more: false--}}
    {{--                            }--}}
    {{--                        };--}}
    {{--                    },--}}
    {{--                    cache: true,--}}
    {{--                    delay: 150,--}}
    {{--                },--}}
    {{--                placeholder: 'Search',--}}
    {{--                allowClear: true,--}}
    {{--            });--}}

    {{--            $(document).on('click', '#recipeModal', function () {--}}
    {{--                const recipeId = $(this).attr('data');--}}
    {{--                let url = $('#sub_dyeing_recipe_requisition_form').attr('action');--}}
    {{--                url += `/${recipeId}/store`;--}}
    {{--                $('#sub_dyeing_recipe_requisition_form').attr('action', url);--}}
    {{--            });--}}

    {{--        });--}}

    {{--    </script>--}}
@endsection
