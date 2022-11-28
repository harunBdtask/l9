@extends('skeleton::layout')
@section("title","Sub Grey Store")

@section('styles')
    <style>
        .custom-control-label {
            padding: 0.165rem 0;
        }

        .custom-form-section {
            border-radius: 6px;
            /*padding: 13px 0;*/
        }

        .custom-field {
            width: 90%;
            border: 1px solid #cecece;
        }
    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Dyeing Operation Function</h2>
            </div>

            <div class="box-body b-t">
                <div class="row">
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <div
                                    class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 custom-form-section">
                        <div class="box">
                            <div class="box-header">
                                {!! Form::open([
                                'route' => $subDyeingOperationFunction ? ['sub-dyeing-operation-function.update', $subDyeingOperationFunction->id] : 'sub-dyeing-operation-function.store',
                                'method' => $subDyeingOperationFunction ? 'PUT' : 'POST',
                                'id' => 'sub-dyeing-operation-function-entry-form']) !!}

                                <div class="form-group">
                                    <label for="factory_id" class="custom-control-label">Factory</label>
                                    {!! Form::select('factory_id', $factories , $subDyeingOperationFunction->factory_id ?? null, ['class' => 'form-control form-control-sm factory']) !!}
                                    @if($errors->has('factory_id'))
                                        <span class="text-danger">{{ $errors->first('factory_id') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="dyeing_recipe_operation_id" class="custom-control-label">Dye Re Operation Name</label>
                                    <select class="form-control form-control-sm" name="dyeing_recipe_operation_id" id="dye_re_operation">
                                        <option value="">Select</option>
                                        @if ($dyeingRecipe)
                                            @foreach ($dyeingRecipe as $recipe)
                                                <option value="{{ $recipe->id }}" {{$subDyeingOperationFunction->dyeing_recipe_operation_id == $recipe->id ? "selected" : "" }}>{{ $recipe->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if($errors->has('dyeing_recipe_operation_id'))
                                        <span class="text-danger">{{ $errors->first('dyeing_recipe_operation_id') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="function_name" class="custom-control-label">Function Name</label>
                                    {!! Form::text('function_name', $subDyeingOperationFunction->function_name ?? null , ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('function_name'))
                                        <span class="text-danger">{{ $errors->first('function_name') }}</span>
                                    @endif
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i>
                                        {{ $subDyeingOperationFunction ? 'Update' : 'Create' }}
                                    </button>
                                    <a href="{{ route('sub-dyeing-operation-function.index') }}" class="btn btn-sm btn-warning"><i
                                            class="fa fa-refresh"></i>
                                        Reset</a>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        {!! Form::open(['route' => ['sub-dyeing-operation-function.index'], 'method' =>'GET']) !!}
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>
                                    <a href="{{ route('sub-dyeing-operation-function.index') }}" type="submit" class="btn btn-xs btn-info">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </th>
                                <td>
                                {!! Form::select('factory_filter', $factories , request()->get('factory_filter'), ['class' => 'form-control form-control-sm']) !!}
                                </td>
                                <td>
                                    {!! Form::select('name', $subDyeingRecipeOperation , request()->get('name'), ['class' => 'form-control form-control-sm']) !!}
                                    </td>
                                <td>
                                    {!! Form::text('function_name', request()->get('function_name'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>
                                </th>
                                <th>
                                    <button type="submit" class="btn btn-xs btn-info"><i class="fa fa-search"></i>
                                    </button>
                                </th>
                            </tr>
                            <tr>
                                <td colspan="8">&nbsp;&nbsp;</td>
                            </tr>
                            <tr>
                                <th>SL</th>
                                <th>Factory</th>
                                <th>Dyeing Re Operation Name</th>
                                <th>Function Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                                @forelse ($subDyeingOperationFunctions as $dyeingOperation)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $dyeingOperation->factory->factory_name }}</td>
                                        <td>{{ $dyeingOperation->dyeingRecipeOperation->name }}</td>
                                        <td>{{ $dyeingOperation->function_name }}</td>
                                        <td style="display: inline-flex">
                                            <a href="{{ route('sub-dyeing-operation-function.edit',['sub_dyeing_operation_function'=>$dyeingOperation->id]) }}" class="edit-btn btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
                                            <button type="button" class="btn btn-xs btn-danger show-modal"
                                                    data-toggle="modal"
                                                    data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                    ui-target="#animate"
                                                    data-url="{{ route('sub-dyeing-operation-function.destroy',['sub_dyeing_operation_function'=>$dyeingOperation->id])  }}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" align="center">No Data</td>
                                    </tr>
                                @endforelse
                                
                            </tbody>
                            <tfoot>
                            @if($subDyeingOperationFunctions->total() > 15)
                                <tr>
                                    <td colspan="4"
                                        align="center">{{ $subDyeingOperationFunctions->appends(request()->except('page'))->links() }}
                                    </td>
                                </tr>
                            @endif
                            </tfoot>
                        </table>
                        {!! Form::close() !!}
                    </div>
                </div>



            </div>

        </div>
    </div>

@endsection
@push("script-head")
<script>
    $(document).on('change','.factory', function() {
    let factory = $('.factory').val();
    //console.log(factory)
    $.ajax({
        method : 'GET',
        url : `{{ url('subcontract/factory-wise-dye-re-operation-name') }}`,
        data : {
            factory
        },
        success: function(result){
            $('#dye_re_operation').empty();
            $.each(result,function(index,data){
                $('#dye_re_operation').append(`
                <option value="${data.id}">${data.name}</option>
                `)
            })
            console.log(result)
        },
        error: function(error){
            console.log(error)
        }
    });
});
</script>
@endpush