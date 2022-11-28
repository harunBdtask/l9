@extends('subcontract::layout')
@section("title","Tube Compacting")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Tube Compacting</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('subcontract/tube-compacting/create') }}"
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
                            {!! Form::open(['url'=>'/subcontract/tube-compacting', 'method'=>'GET']) !!}
                            <tr>
                                <td>*</td>
                                <td>
                                    {!! Form::select('entry_basis', $entryBasis ?? [], request('entry_basis'),[
                                        'class'=>'text-center select2-input', 'id'=>'entry_basis',
                                        'placeholder' => 'Select'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::date('production_date', request('production_date') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('factory_id', $factories ?? [], request('factory_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('supplier_id', [], request('supplier_id'),[
                                        'class'=>'text-center', 'id'=>'supplier_id'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('sub_textile_order_no', request('sub_textile_order_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('sub_dyeing_batch_no', request('sub_dyeing_batch_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('shift_id', $shifts ?? [], request('shift_id') ?? null, [
                                        'class'=>'text-center form-control  select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('color_id', $colors ?? [], request('color_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('total_order_qty', request('total_order_qty') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td></td>
                                <td>
                                    {!! Form::text('finish_qty', request('finish_qty') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    <button class="btn btn-xs white" type="submit">
                                        <em class="fa fa-search"></em>
                                    </button>
                                </td>
                            </tr>
                            {!! Form::close() !!}
                            <tr>
                                <td colspan="13">&nbsp;</td>
                            </tr>
                            @forelse ($subDyeingTubeCompacting as $tubeCompacting)
                                @php
                                    $colors = collect($tubeCompacting->subDyeingTubeCompactingDetail)->pluck('color')->pluck('name')->join(', ');
                                    $totalOrderQty = collect($tubeCompacting->subDyeingTubeCompactingDetail)->sum('order_qty');
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $tubeCompacting->entry_basis_value }}</td>
                                    <td>{{ $tubeCompacting->production_date }}</td>
                                    <td>{{ $tubeCompacting->factory->factory_name }}</td>
                                    <td>{{ $tubeCompacting->supplier->name }}</td>
                                    <td>{{ $tubeCompacting->sub_textile_order_no ?? 'N\A' }}</td>
                                    <td>{{ $tubeCompacting->sub_dyeing_batch_no ?? 'N\A' }}</td>
                                    <td>{{ $tubeCompacting->shift->shift_name }}</td>
                                    <td>{{ $colors }}</td>
                                    <td>{{ $totalOrderQty }}</td>
                                    <td>{{ $tubeCompacting->machine->name }}</td>
                                    <td>{{ $tubeCompacting->subDyeingTubeCompactingDetail->pluck('finish_qty')->join(', ') }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-info" type="button"
                                           href="{{ url('subcontract/tube-compacting/create?id='. $tubeCompacting->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>

                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Order"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('subcontract/tube-compacting/'. $tubeCompacting->id) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" style="text-align: center">No Data</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $subDyeingTubeCompacting->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#supplier_id').select2({
                ajax: {
                    url: "/subcontract/api/v1/textile-parties/select-search",
                    dataType: 'json',
                    data: function (params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response.data,
                            pagination: {
                                more: false
                            }
                        };
                    },
                    cache: true,
                    delay: 150,
                },
                placeholder: 'Search',
                allowClear: true,
            });
        });

    </script>
@endsection
