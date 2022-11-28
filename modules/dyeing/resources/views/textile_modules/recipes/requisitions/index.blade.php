@extends('dyeing::layout')
@section("title","Recipe Requisitions")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Dyeing Requisition</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Factory</th>
                                <th>Booking Type</th>
                                <th>Recipe No</th>
                                <th>Requisition UID</th>
                                <th>Store</th>
                                <th>Requisition Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! Form::open(['url'=>'/dyeing/recipes/requisitions', 'method'=>'GET']) !!}
                            <tr>
                                <td>
                                    <a href="/dyeing/recipes/requisitions" type="submit" class="btn btn-xs btn-info">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </td>
                                <td>
                                    {!! Form::select('factory_id', $factories ?? [], request('factory_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    <select
                                        name="type"
                                        class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        <option @if(request()->get('type') == 'main') selected @endif value="main">Main</option>
                                        <option @if(request()->get('type') == 'short') selected @endif value="short">Short</option>
                                        <option @if(request()->get('type') == 'sample') selected @endif value="sample">Sample</option>
                                    </select>
                                </td>
                                <td>
                                    {!! Form::select('dyeing_recipe_id', $recipeNos ?? [], request('dyeing_recipe_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('unique_id', request('unique_id') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('store_id', $stores ?? [], request('store_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::date('requisition_date', request('requisition_date') ?? null, [
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
                                <td colspan="8">&nbsp;</td>
                            </tr>
                            @forelse ($requisitions as $requisition)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $requisition->factory->factory_name }}</td>
                                    <td style="text-transform: capitalize">{{ $requisition->dyeingRecipe->subDyeingBatch->fabricSalesOrder->booking_type??null  }}</td>
                                    <td>{{ $requisition->dyeingRecipe->unique_id }}</td>
                                    <td>{{ $requisition->unique_id }}</td>
                                    <td>{{ $requisition->store->name }}</td>
                                    <td>{{ $requisition->requisition_date }}</td>
                                    <td></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" align="center">No Data</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $requisitions->appends(request()->except('page'))->links() }}
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

