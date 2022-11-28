@extends('skeleton::layout')
@section("title","Machine Type")

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
                <h2>Machine Type</h2>
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
                                    'route' => $machineType ? ['machine-type.update', $machineType->id] : 'machine-type.store',
                                    'method' => $machineType ? 'PUT' : 'POST',
                                    'id' => 'machine-type-entry-form']) !!}

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Machine Category<span class="text-danger">*</span></label>
                                    {!! Form::select('machine_category', $machineCategories ,$machineType->machine_category ?? null, ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('machine_category'))
                                        <span class="text-danger">{{ $errors->first('machine_category') }}</span>
                                    @endif

                                </div>

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Machine Type<span class="text-danger">*</span></label>
                                    {!! Form::text('machine_type',$machineType->machine_type ?? null,['class' => 'form-control form-control-sm input-validate']) !!}
                                    @if($errors->has('machine_type'))
                                        <span class="text-danger">{{ $errors->first('machine_type') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Description</label>
                                    {!! Form::text('description',$machineType->description ?? null,['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('description'))
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i>
                                    {{ $machineType?'Update':'Create' }}
                                    </button>
                                    <a href="{{ url('mc-inventory/machine-type') }}" class="btn btn-sm btn-warning"><i
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
                                    <a href="{{ route('machine-type.index') }}" type="submit" class="btn btn-xs btn-info">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </th>
                                <td>
                                    {!! Form::select('machine_category_filter', $machineCategories , request()->get('machine_category_filter'), ['class' => 'form-control form-control-sm']) !!}
                                </td>
                                <td>
                                    {!! Form::text('machine_type_filter', request()->get('machine_type_filter'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>
                                <td>
                                    {!! Form::text('machine_description_filter', request()->get('machine_description_filter'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>
                                <th>
                                    <button type="submit" class="btn btn-xs btn-info"><i class="fa fa-search"></i>
                                    </button>
                                </th>
                            </tr>
                            <tr>
                                <td colspan="5">&nbsp;&nbsp;</td>
                            </tr>
                            <tr>
                                <th>SL</th>
                                <th>Machine Category</th>
                                <th>Machine Type</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                                @forelse($machineTypes as $machineType)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$machineType->machine_category_value}}</td>
                                    <td>{{$machineType->machine_type}}</td>
                                    <td>{{$machineType->description}}</td>
                                    <td style="white-space: nowrap;">
                                        <a href="{{ route('machine-type.edit',['machine_type'=>$machineType->id]) }}" class="edit-btn btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
                                        <button type="button" class="btn btn-xs btn-danger show-modal"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ route('machine-type.destroy',['machine_type'=>$machineType->id])  }}">
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
                             @if($machineTypes->total() > 15)
                                <tr>
                                    <td colspan="5"
                                        align="center">{{ $machineTypes->appends(request()->except('page'))->links() }}
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

