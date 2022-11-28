@extends('subcontract::layout')
@section("title","Sub Grey Store Multiple Recipe Download")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Multiple Recipe Download</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b text-right">
                        <a id="pdf-download" data-value="" class="btn"
                           href="{{ url('subcontract/dyeing-process/multiple-recipe-download/pdf-download') }}">
                            <em class="fa fa-file-pdf-o"></em>
                        </a>

                        <a id="download" data-value=""
                           href="{{ url('subcontract/dyeing-process/multiple-recipe-download/download') }}"
                           class="btn">
                            <em class="fa fa-file-excel-o"></em>
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
                                <th style="width: 213px;">Batch No</th>
                                <th>Liquor Ratio</th>
                                <th>Total Liq Level</th>
                                <th>Batch Fab Weight</th>
                                <th>Shift</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! Form::open(['url'=>'/subcontract/dyeing-process/multiple-recipe-download/search', 'method'=>'GET']) !!}
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
                                    {!! Form::select('batch_id[]', $batchNo ?? [], request('batch_id') ?? null, [
                                        'class'=>'text-center select2-input', 'multiple' => true
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
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $dyeingRecipe->recipe_uid }}</td>
                                    <td>{{ $dyeingRecipe->recipe_date }}</td>
                                    <td>{{ $dyeingRecipe->factory->factory_name}}</td>
                                    <td>{{ $dyeingRecipe->supplier->name }}</td>
                                    <td>{{ $dyeingRecipe->subDyeingBatch->batch_no }}</td>
                                    <td>{{ $dyeingRecipe->liquor_ratio }}</td>
                                    <td>{{ $dyeingRecipe->total_liq_level }}</td>
                                    <td>{{ $dyeingRecipe->subDyeingBatch->total_batch_weight }}</td>
                                    <td>{{ $dyeingRecipe->Shift->shift_name }}</td>
                                    <td>
                                        {!! Form::checkbox('recipe_id', $dyeingRecipe->id, false, ['id' => 'recipe_id']) !!}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" align="center">No Data</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script type="text/javascript">
        $('#download').click(function (event) {
            event.preventDefault();

            let recipes = [];
            $.each($("input[name='recipe_id']:checked"), function () {
                recipes.push($(this).val());
            });

            window.open(`/subcontract/dyeing-process/multiple-recipe-download/download?recipes=${recipes}`)
        })
        $('#pdf-download').click(function (event) {
            event.preventDefault();

            let recipes = [];
            $.each($("input[name='recipe_id']:checked"), function () {
                recipes.push($(this).val());
            });

            window.open(`/subcontract/dyeing-process/multiple-recipe-download/pdf-download?recipes=${recipes}`)
        })
    </script>
@endsection
