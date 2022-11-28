@extends('dyeing::layout')
@section("title","Dyeing Recipes")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Dyeing Recipes</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('dyeing/recipes/create') }}"
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
                                <th>Company</th>
                                <th>Recipe Id</th>
                                <th>Buyer</th>
                                <th>Booking Type</th>
                                <th>Batch No</th>
                                <th>Liquor Ratio</th>
                                <th>Total liq level</th>
                                <th>Shift</th>
                                <th>Recipe Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! Form::open(['url'=>'/dyeing/recipes', 'method'=>'GET']) !!}
                            <tr>
                                <td>*</td>
                                <td>
                                    {!! Form::select('factory_id', $factories ?? [], request('factory_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('unique_id', request('unique_id') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('buyer_id', $buyers ?? [], request('buyer_id'),[
                                        'class'=>'text-center select2-input', 'id'=>'buyer_id'
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
                                    {!! Form::text('dyeing_batch_no', request('dyeing_batch_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('liquor_ratio', request('liquor_ratio'),[
                                        'class'=>'text-center form-control form-control-sm', 'id'=>'liquor_ratio'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('total_liq_level', request('total_liq_level') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('shift_id', $shifts ?? [], request('shift_id'),[
                                        'class'=>'text-center select2-input', 'id'=>'shift_id'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::date('recipe_date', request('recipe_date') ?? null, [
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
                                <td colspan="11">&nbsp;</td>
                            </tr>
                            @foreach ($recipes as $recipe)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $recipe->factory->factory_name }}</td>
                                    <td>{{ $recipe->unique_id }}</td>
                                    <td>{{ $recipe->buyer->name  }}</td>
                                    <td style="text-transform: capitalize">{{ $recipe->subDyeingBatch->fabricSalesOrder->booking_type??null  }}</td>
                                    <td>{{ $recipe->dyeing_batch_no }}</td>
                                    <td>{{ $recipe->liquor_ratio }}</td>
                                    <td>{{ $recipe->total_liq_level }}</td>
                                    <td>{{ $recipe->shift->shift_name }}</td>
                                    <td>{{ $recipe->recipe_date }}</td>
                                    <td>
                                        @if(!count($recipe->recipeRequisitions))
                                            <button type="button"
                                                    style="height: 25px;"
                                                    class="btn btn-xs btn-primary"
                                                    id="recipeModal"
                                                    data-toggle="modal"
                                                    data-target="#recipeModalCenter"
                                                    data="{{ $recipe->id }}">
                                                <em class="fa fa-send-o" style="color: #ffffff;"></em>
                                            </button>
                                        @endif
                                        <a class="btn btn-xs btn-info" type="button"
                                           href="{{ url('dyeing/recipes/create?id=' . $recipe->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        <a class="btn btn-success btn-xs" type="button"
                                           href="{{ url('dyeing/recipes/view/'.$recipe->id) }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Order"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('dyeing/recipes/' . $recipe->id) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $recipes->appends(request()->except('page'))->links() }}
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
                    'url' => 'dyeing/recipes/requisitions',
                    'method' => 'POST',
                    'id' => 'dyeing_recipe_requisition_form'
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
                                {!! Form::select('store_id', $stores ?? [], null, [
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
        $(document).on('click', '#recipeModal', function () {
            const recipeId = $(this).attr('data');
            let url = $('#dyeing_recipe_requisition_form').attr('action');
            url += `/${recipeId}`;
            $('#dyeing_recipe_requisition_form').attr('action', url);
        });
    </script>
@endsection
