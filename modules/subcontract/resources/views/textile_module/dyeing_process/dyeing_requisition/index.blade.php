@extends('subcontract::layout')
@section("title","Sub Grey Store Material Fabric Receive")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Sub Dyeing Requisition</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('subcontract/dyeing-process/recipe-entry/create') }}"
                           class="btn btn-sm white m-b">
                            <em class="fa fa-plus"></em>&nbsp;Requisition Create
                        </a>
                    </div>
                </div> --}}
                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Factory</th>
                                <th>Recipe No</th>
                                <th>Requisition UID</th>
                                <th>Store</th>
                                <th>Requisition Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                {!! Form::open(['url'=>'/subcontract/dyeing-process/recipe-entry/requisition-entry', 'method'=>'GET']) !!}
                            <tr>
                                <td>
                                    <a href="/subcontract/dyeing-process/recipe-entry/requisition-entry" type="submit" class="btn btn-xs btn-info">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </td>
                                <td>
                                    {!! Form::select('factory_id', $factories ?? [], request('factory_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('sub_dyeing_recipe_id', $subDyeingRecipe ?? [], request('sub_dyeing_recipe_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('requisition_uid', request('requisition_uid') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('store_id', $dsStore ?? [], request('store_id') ?? null, [
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
                                <td colspan="7">&nbsp;</td>
                            </tr>
                                @forelse ($dyeingRequisition as $requisition)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $requisition->factory->factory_name }}</td>
                                        <td>{{ $requisition->subDyeingRecipe->recipe_uid }}</td>
                                        <td>{{ $requisition->requisition_uid }}</td>
                                        <td>{{ $requisition->dsStore->name }}</td>
                                        <td>{{ $requisition->requisition_date }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" align="center">No Data</td>
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

