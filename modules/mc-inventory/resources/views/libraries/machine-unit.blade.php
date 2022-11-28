@extends('skeleton::layout')
@section("title","Machine Unit")

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
                <h2>Machine Unit</h2>
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
                                    'route' => $machineUnit ? ['machine-unit.update', $machineUnit->id] : 'machine-unit.store',
                                    'method' => $machineUnit ? 'PUT' : 'POST',
                                    'id' => 'machine-unit-entry-form']) !!}

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Name<span class="text-danger">*</span></label>
                                    {!! Form::text('name',$machineUnit->name ?? null,['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Description</label>
                                    {!! Form::text('description',$machineUnit->description ?? null,['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('description'))
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="type" class="custom-control-label">Type<span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm" name="type" id="type">
                                        <option value="">Select</option>
                                        <option value="1" {{isset($machineUnit) && $machineUnit->type == 1 ? "selected" : "" }}>Rental</option>
                                        <option value="2" {{isset($machineUnit) && $machineUnit->type == 2 ? "selected" : "" }}>In House</option>
                                    </select>
                                    @if($errors->has('type'))
                                        <span class="text-danger">{{ $errors->first('type') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i>
                                        {{ $machineUnit?'Update':'Create' }}
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
                                    <a href="{{ route('machine-unit.index') }}" type="submit" class="btn btn-xs btn-info">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </th>
                                <td>
                                    {!! Form::text('machine_name_filter', request()->get('machine_name_filter'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>
                                <td>
                                    <select class="form-control form-control-sm" name="type" id="type">
                                        <option value="">Select</option>
                                        <option value="1" {{request('type') == 1 ? "selected" : "" }}>Rental</option>
                                        <option value="2" {{request('type') == 2 ? "selected" : "" }}>In House</option>
                                    </select>
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
                                <th>Name</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($machineUnits as $machineUnit)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$machineUnit->name}}</td>
                                    <td>
                                        @if($machineUnit->type == 1)
                                            <span>Rental</span>
                                        @elseif($machineUnit->type == 2)
                                            <span>In House</span>
                                        @endif
                                    </td>
                                    <td>{{$machineUnit->description}}</td>
                                    <td style="white-space: nowrap;">
                                        <a href="{{ route('machine-unit.edit',['machine_unit'=>$machineUnit->id]) }}" class="edit-btn btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
                                        <button type="button" class="btn btn-xs btn-danger show-modal"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ route('machine-unit.destroy',['machine_unit'=>$machineUnit->id])  }}">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" align="center">No Data</td>
                                </tr>
                            @endforelse
                            </tbody>
                            <tfoot>
                            @if($machineUnits->total() > 15)
                                <tr>
                                    <td colspan="4"
                                        align="center">{{ $machineUnits->appends(request()->except('page'))->links() }}
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
