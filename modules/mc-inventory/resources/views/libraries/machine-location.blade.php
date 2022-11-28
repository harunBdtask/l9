@extends('skeleton::layout')
@section("title","Machine Location")

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
                <h2>Machine Location</h2>
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
                                'route' => $machineLocation ? ['machine-location.update', $machineLocation->id] : 'machine-location.store',
                                'method' => $machineLocation ? 'PUT' : 'POST',
                                'id' => 'machine-location-entry-form']) !!}

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Location Name</label>
                                    <span class="text-danger">*</span>
                                    {!! Form::text('location_name', $machineLocation->location_name ?? null, ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('location_name'))
                                        <span class="text-danger">{{ $errors->first('location_name') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Address</label>
                                    {!! Form::text('address',$machineLocation->address ?? null,['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('address'))
                                        <span class="text-danger">{{ $errors->first('address') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Contact No</label>
                                    {!! Form::text('contact_no',$machineLocation->contact_no ?? null,['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('contact_no'))
                                        <span class="text-danger">{{ $errors->first('contact_no') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Email</label>
                                    {!! Form::text('email',$machineLocation->email ?? null,['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Attention</label>
                                    {!! Form::text('attention',$machineLocation->attention ?? null,['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('attention'))
                                        <span class="text-danger">{{ $errors->first('attention') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Location Type</label>
                                    <span class="text-danger">*</span>
                                    {!! Form::select('location_type', $locationTypes ,$machineLocation->location_type ?? null, ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('location_type'))
                                        <span class="text-danger">{{ $errors->first('location_type') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i>
                                        {{ $machineLocation ? 'Update' : 'Create' }}
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
                       {!! Form::open(['route' => ['machine-location.index'],'method' =>'GET']) !!}
                        <table class="reportTable table-responsive">
                            <thead>
                            <tr>
                                <th>
                                    <a href="{{ route('machine-location.index') }}" type="submit" class="btn btn-xs btn-info">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </th>
                                <td>
                                    {!! Form::text('location_name_filter', request()->get('location_name_filter'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </th>
                                <td>
                                    {!! Form::text('address_name_filter', request()->get('address_name_filter'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>

                                <td>
                                    {!! Form::text('contact_no_filter', request()->get('contact_no_filter'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>

                                <td>
                                    {!! Form::text('email_filter', request()->get('email_filter'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>

                                <td>
                                    {!! Form::text('attention_filter', request()->get('attention_filter'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>

                                <td>
                                    {!! Form::select('location_type_filter', $locationTypes , request()->get('location_type_filter'), ['class' => 'form-control form-control-sm']) !!}
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
                                <th>Location Name</th>
                                <th>Address</th>
                                <th>Contact No</th>
                                <th>Email</th>
                                <th>Attentation</th>
                                <th>Location Type</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                                @forelse($machineLocations as $machineLocation)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$machineLocation->location_name}}</td>
                                    <td>{{$machineLocation->address}}</td>
                                    <td>{{$machineLocation->contact_no}}</td>
                                    <td>{{$machineLocation->email}}</td>
                                    <td>{{$machineLocation->attention}}</td>
                                    <td>{{$machineLocation->location_type_value}}</td>
                                    <td style="white-space: nowrap;">
                                        <a href="{{ route('machine-location.edit',['machine_location'=>$machineLocation->id]) }}" class="edit-btn btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
                                        <button type="button" class="btn btn-xs btn-danger show-modal"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ route('machine-location.destroy',['machine_location'=>$machineLocation->id])  }}">
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
                             @if($machineLocations->total() > 15)
                                <tr>
                                    <td colspan="4"
                                        align="center">{{ $machineLocations->appends(request()->except('page'))->links() }}
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
