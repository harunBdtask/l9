@extends('skeleton::layout')
@section("title","Machine Sub Type")

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
                <h2>Machine Sub Type</h2>
            </div>

            <div class="box-body b-t">
                <div class="row">
                    <div class="col-md-12">
                        @include('McInventory::partials.response-message')
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 custom-form-section">
                        <div class="box">
                            <div class="box-header">
                                {!! Form::open([
                                    'route' => $machineSubType ? ['machine-sub-type.update', $machineSubType->id] : 'machine-sub-type.store',
                                    'method' => $machineSubType ? 'PUT' : 'POST',
                                    'id' => 'machine-sub-type-entry-form']) !!}

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Category<span class="text-danger">*</span></label>
                                    {!! Form::select('machine_category', $machineCategories ,$machineSubType->machine_category ?? null, ['class' => 'form-control form-control-sm machine_category']) !!}
                                    @if($errors->has('machine_category'))
                                        <span class="text-danger">{{ $errors->first('machine_category') }}</span>
                                    @endif

                                </div>

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Machine Type<span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm machine_type" name="machine_type" id="machine_type">
                                        <option value="">Select</option>
                                        @if($machineType)
                                            @foreach ($machineType as $type)
                                                <option value="{{ $type->id }}" {{$machineSubType->machine_type == $type->id ? "selected" : "" }}>{{ $type->machine_type }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if($errors->has('machine_type'))
                                        <span class="text-danger">{{ $errors->first('machine_type') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Sub Type<span class="text-danger">*</span></label>
                                    {!! Form::text('machine_sub_type',$machineSubType->machine_sub_type ?? null,['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('machine_sub_type'))
                                        <span class="text-danger">{{ $errors->first('machine_sub_type') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Description</label>
                                    {!! Form::text('description',$machineSubType->description ?? null,['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('description'))
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i>
                                        {{ $machineSubType ? 'Update' : 'Create' }}
                                    </button>
                                    <a href="" class="btn btn-sm btn-warning"><i
                                            class="fa fa-refresh"></i>
                                        Reset</a>
                                </div>
                               {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                       {!! Form::open(['method' =>'GET']) !!}
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>
                                    <a href="{{ route('machine-sub-type.index') }}" type="submit" class="btn btn-xs btn-info">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </th>
                                <td>
                                    {!! Form::select('category', $machineCategories ,request()->get('category') ?? null, ['class' => 'form-control form-control-sm']) !!}
                                </th>
                                <td>
                                {!! Form::select('machine_type', $machineTypes ,request()->get('machine_type') ?? null, ['class' => 'form-control form-control-sm']) !!}
                                </td>
                                <td>
                                    {!! Form::text('subtype', request()->get('subtype') ?? null, ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>
                                <td>
                                    {!! Form::text('description', request()->get('description') ?? null, ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>
                                </th>
                                <th>
                                    <button type="submit" class="btn btn-xs btn-info"><i class="fa fa-search"></i>
                                    </button>
                                </th>
                            </tr>
                            <tr>
                                <td colspan="6">&nbsp;&nbsp;</td>
                            </tr>
                            <tr>
                                <th>SL</th>
                                <th>Category</th>
                                <th>Machine Type</th>
                                <th>Sub Type</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                                @forelse ($machineSubTypes as $machineSubType)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$machineSubType->machine_category_value}}</td>
                                    <td>{{$machineSubType->machineType->machine_type}}</td>
                                    <td>{{$machineSubType->machine_sub_type}}</td>
                                    <td>{{$machineSubType->description}}</td>
                                    <td style="white-space: nowrap;">
                                        <a href="{{ route('machine-sub-type.edit',['machine_sub_type'=>$machineSubType->id]) }}" class="edit-btn btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
                                        <button type="button" class="btn btn-xs btn-danger show-modal"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ route('machine-sub-type.destroy',['machine_sub_type'=>$machineSubType->id])  }}">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" align="center">No Data</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                            @if($machineSubTypes->total() > 15)
                                <tr>
                                    <td colspan="4"
                                        align="center">{{ $machineSubTypes->appends(request()->except('page'))->links() }}
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
    $(document).on('change','.machine_category', function() {
    let machine_category = $('.machine_category').val();
    //console.log(factory)
    $.ajax({
        method : 'GET',
        url : `{{ url('mc-inventory/machine-category-wise-type') }}`,
        data : {
            machine_category
        },
        success: function(result){
            $('#machine_type').empty();
            $.each(result,function(index,data){
                $('#machine_type').append(`
                <option value="${data.id}">${data.machine_type}</option>
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
