@extends('skeleton::layout')
@section("title","Sub Dyeing Unit")
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
                <h2>Sub Dyeing Unit</h2>
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
                                'route' => $dyeingUnit ? ['sub-dyeing-unit.update', $dyeingUnit->id] : 'sub-dyeing-unit.store',
                                'method' => $dyeingUnit ? 'PUT' : 'POST',
                                'id' => 'sub-dyeing-unit-entry-form']) !!}

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Factory</label>
                                    {!! Form::select('factory_id', $factories , $dyeingUnit->factory_id ?? factoryId(), ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('factory_id') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Name</label>
                                    {!! Form::text('name', $dyeingUnit->name ?? null , ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="type" class="custom-control-label">Address</label>
                                    {!! Form::textarea('address', $dyeingUnit->address ?? null, ['class' => 'form-control form-control-sm','rows'=>2,'col'=>2]) !!}
                                </div>
                                <div class="form-group">
                                    <label for="type" class="custom-control-label">Contact No</label>
                                    {!! Form::text('contact_no', $dyeingUnit->contact_no ?? null, ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('contact_no'))
                                        <span class="text-danger">{{ $errors->first('contact_no') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="type" class="custom-control-label">Attention</label>
                                    {!! Form::text('attention', $dyeingUnit->attention ?? null, ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('attention'))
                                        <span class="text-danger">{{ $errors->first('attention') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="type" class="custom-control-label">Email</label>
                                    {!! Form::email('email', $dyeingUnit->email ?? null, ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i>
                                        {{ $dyeingUnit ? 'Update' : 'Create' }}
                                    </button>
                                    <a href="{{ route('sub-dyeing-unit.index') }}" class="btn btn-sm btn-warning"><i
                                            class="fa fa-refresh"></i>
                                        Reset</a>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        {!! Form::open(['route' => ['sub-dyeing-unit.index'], 'method' =>'GET']) !!}
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>
                                    <a href="{{ route('sub-dyeing-unit.index') }}" type="submit" class="btn btn-xs btn-info">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </th>
                                <td>
                                {!! Form::select('factory_filter', $factories , request()->get('factory_filter'), ['class' => 'form-control form-control-sm']) !!}
                                </th>
                                <td>
                                    {!! Form::text('name_filter', request()->get('name_filter'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>
                                <td>
                                    {!! Form::text('address_filter', request()->get('address_filter'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>
                                <td>
                                    {!! Form::text('contact_no_filter', request()->get('contact_no_filter'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>
                                <td>
                                    {!! Form::text('attention_filter', request()->get('attention_filter'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>
                                <td>
                                    {!! Form::text('email_filter',request()->get('email_filter'), ['class' => 'custom-field text-center',
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
                                <th>Name</th>
                                <th>Address</th>
                                <th>Contact No</th>
                                <th>Attention</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($subDyeingUnits as $dyeingUnit)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $dyeingUnit->factory->factory_name }}</td>
                                    <td>{{ $dyeingUnit->name }}</td>
                                    <td>{{ $dyeingUnit->address }}</td>
                                    <td>{{ $dyeingUnit->contact_no }}</td>
                                    <td>{{ $dyeingUnit->attention }}</td>
                                    <td>{{ $dyeingUnit->email }}</td>
                                    <td style="display: inline-flex">
                                        <a href="{{ route('sub-dyeing-unit.edit',['sub_dyeing_unit'=>$dyeingUnit->id]) }}"
                                           class="edit-btn btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
                                        <button type="button" class="btn btn-xs btn-danger show-modal"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ route('sub-dyeing-unit.destroy',['sub_dyeing_unit'=>$dyeingUnit->id])  }}">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" align="center">No Data</td>
                                </tr>
                            @endforelse
                            </tbody>
                            <tfoot>
                            @if($subDyeingUnits->total() > 15)
                                <tr>
                                    <td colspan="4"
                                        align="center">{{ $subDyeingUnits->appends(request()->except('page'))->links() }}
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
