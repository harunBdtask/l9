@extends('subcontract::layout')
@section("title","Sub Grey Store Material Fabric Receive")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Sub Dyeing Recipe</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('subcontract/dyeing-process/recipe-entry/create') }}"
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
                                <th>Recipe Id</th>
                                <th>Recipe Date</th>
                                <th>Dyeing Company</th>
                                <th>Party/Buyer</th>
                                <th>Color</th>
                                <th>Batch No</th>
                                <th>Liquor Ratio</th>
                                <th>Total Liq Level</th>
                                <th>Batch Fab Weight</th>
                                <th>Shift</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! Form::open(['url'=>'/subcontract/dyeing-process/recipe-entry', 'method'=>'GET']) !!}
                            <tr>
                                <td>*</td>
                                <td>
                                    {!! Form::text('recipe_uid', request('recipe_uid') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::date('recipe_date', request('recipe_date') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('factory_id', $factories ?? [], request('factory_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('supplier_id', $parties ?? [], request('supplier_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('color_id', $colors ?? [], request('color_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('batch_no', request('batch_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('liquor_ratio', request('liquor_ratio') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('total_liq_level', request('total_liq_level') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('total_batch_weight', request('total_batch_weight') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('shift_id', $shifts ?? [], request('shift_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    <button class="btn btn-xs white" type="submit">
                                        <em class="fa fa-search"></em>
                                    </button>
                                </td>
                            </tr>
                            {!! Form::close() !!}
                            @forelse ($subDyeingRecipes as $dyeingRecipe)
                                @php
                                    $color = $dyeingRecipe->subDyeingBatch->fabricColor;
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $dyeingRecipe->recipe_uid }}</td>
                                    <td>{{ $dyeingRecipe->recipe_date }}</td>
                                    <td>{{ $dyeingRecipe->factory->factory_name}}</td>
                                    <td>{{ $dyeingRecipe->supplier->name }}</td>
                                    <td>{{ $color->name ?? ''  }}</td>
                                    <td>{{ $dyeingRecipe->subDyeingBatch->batch_no }}</td>
                                    <td>{{ $dyeingRecipe->liquor_ratio }}</td>
                                    <td>{{ $dyeingRecipe->total_liq_level }}</td>
                                    <td>{{ $dyeingRecipe->subDyeingBatch->total_batch_weight }}</td>
                                    <td>{{ $dyeingRecipe->Shift->shift_name }}</td>
                                    <td>
                                        @if(!count($dyeingRecipe->recipeRequisitions))
                                            <button type="button"
                                                    style="height: 25px;"
                                                    class="btn btn-xs btn-primary"
                                                    id="recipeModal"
                                                    data-toggle="modal"
                                                    data-target="#recipeModalCenter"
                                                    data="{{ $dyeingRecipe->id }}">
                                                <em class="fa fa-send-o" style="color: #ffffff;"></em>
                                            </button>
                                        @endif
                                        <a class="btn btn-xs btn-info" type="button"
                                           href="{{ url('subcontract/dyeing-process/recipe-entry/create?id=' . $dyeingRecipe->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        <a class="btn btn-success btn-xs" type="button"
                                           href="{{ url('subcontract/dyeing-process/recipe-entry/view/'.$dyeingRecipe->id) }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Order"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('subcontract/dyeing-process/recipe-entry/' . $dyeingRecipe->id) }}">
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
                            {{ $subDyeingRecipes->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="recipeModalCenter" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title" id="exampleModalLongTitle">Recipe Requisition</h5>
                </div>
                {!! Form::open([
                    'url' => 'subcontract/dyeing-process/recipe-entry/requisition-entry',
                    'method' => 'POST',
                    'id' => 'sub_dyeing_recipe_requisition_form'
                ]) !!}
                <div class="modal-body" style="max-height : 350px; overflow-x: scroll">
                    <table class="reportTable">
                        <thead>
                        <tr style="background: #0ab4e6;">
                            <th>Store</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                {!! Form::select('store_id', $stores, null, [
                                    'class' => 'form-control select2-input',
                                    'id' => 'store_id',
                                    'placeholder' => 'Select',
                                ]) !!}
                            </td>
                            <td>
                                {!! Form::date('requisition_date', null, [
                                    'class' => 'form-control',
                                    'id' => 'requisition_date',
                                    'placeholder' => 'Date',
                                ]) !!}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success">Submit</button>
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                </div>
                {!! Form::close() !!}
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
    <script>
        $(document).ready(function () {
            $(document).on('click', '#recipeModal', function () {
                const recipeId = $(this).attr('data');
                let url = $('#sub_dyeing_recipe_requisition_form').attr('action');
                url += `/${recipeId}/store`;
                $('#sub_dyeing_recipe_requisition_form').attr('action', url);
            });

        });

    </script>
@endsection
