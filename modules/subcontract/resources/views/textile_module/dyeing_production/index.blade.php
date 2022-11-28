@extends('subcontract::layout')
@section("title","Sub Dyeing Production")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Sub Dyeing Production</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('subcontract/dyeing-production/create') }}"
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
                                <th>Production Date</th>
                                <th>Factory</th>
                                <th>Party</th>
                                <th>Order No</th>
                                <th>Batch No</th>
                                <th>M/C Name</th>
                                <th>Shift</th>
                                <th>Dyeing Production QTY</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! Form::open(['url'=>'/subcontract/dyeing-production', 'method'=>'GET']) !!}
                            <tr>
                                <td>*</td>
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
                                    {!! Form::select('supplier_id', $suppliers ?? [], request('supplier_id') ?? null,[
                                       'class'=>'text-center select2-input'
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
                                    {!! Form::select('machine_id', $machines ?? [], request('machine_id') ?? null, [
                                       'class'=>'text-center select2-input'
                                   ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('shift_id', $shifts ?? [], request('shift_id') ?? null, [
                                       'class'=>'text-center form-control  select2-input'
                                   ]) !!}
                                </td>
                                <td>

                                </td>
                                <td>
                                    <button class="btn btn-xs white" type="submit">
                                        <em class="fa fa-search"></em>
                                    </button>
                                </td>
                            </tr>
                            @forelse ($dyeingProduction as $production)
                            @php
                                 $machines = collect($production->subDyeingBatch->machineAllocations)
                                                    ->pluck('machine.name')
                                                    ->implode(',');
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $production->production_date }}</td>
                                <td>{{ $production->factory->factory_name }}</td>
                                <td>{{ $production->supplier->name }}</td>
                                <td>{{ $production->order_no }}</td>
                                <td>{{ $production->batch_no }}</td>
                                <td>{{ $machines }}</td>
                                <td>{{ $production->shift->shift_name }}</td>
                                <th>{{ collect($production->subDyeingProductionDetails)->sum('dyeing_production_qty') }}</th>
                                <td>
                                    <a class="btn btn-xs btn-info" type="button"
                                    href="{{ url('subcontract/dyeing-production/create?id='. $production->id) }}">
                                        <em class="fa fa-pencil"></em>
                                    </a>
                                    <a class="btn btn-success btn-xs" type="button"
                                    href="{{ url('subcontract/dyeing-production/view/'. $production->id) }}">
                                        <em class="fa fa-eye"></em>
                                    </a>
                                    <button style="margin-left: 2px;" type="button"
                                            class="btn btn-xs btn-danger show-modal"
                                            title="Delete Order"
                                            data-toggle="modal"
                                            data-target="#confirmationModal" ui-toggle-class="flip-x"
                                            ui-target="#animate"
                                            data-url="{{ url('subcontract/dyeing-production/'. $production->id) }}">
                                        <em class="fa fa-trash"></em>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" align="center">No Data</td>
                            </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->


    <style>
        /*.custom-field {*/
        /*    */
        /*    */
        /*}*/
    </style>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#party_id').select2({
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

            $(document).on('click', '#recipeModal', function () {
                const recipeId = $(this).attr('data');
                let url = $('#sub_dyeing_recipe_requisition_form').attr('action');
                url += `/${recipeId}/store`;
                $('#sub_dyeing_recipe_requisition_form').attr('action', url);
            });

        });

    </script>
@endsection
