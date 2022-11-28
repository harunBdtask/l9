@extends('skeleton::layout')
@section("title","Dyeing Machine")

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
                <h2>Dyeing Machine</h2>
            </div>

            <div class="box-body b-t">
                <div class="row">
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <div class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 custom-form-section">
                        <div class="box">
                            <div class="box-header">
                                {!! Form::open([
                                'route' => $dyeingMachine ? ['dyeing-machine.update', $dyeingMachine->id] : 'dyeing-machine.store',
                                'method' => $dyeingMachine ? 'PUT' : 'POST',
                                'id' => 'dyeing-machine-entry-form']) !!}

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Floor Type</label>
                                    {!! Form::select('floor_type', $floorTypes ,$dyeingMachine->floor_type ?? null, ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('floor_type'))
                                        <span class="text-danger">{{ $errors->first('floor_type') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Name</label>
                                    {!! Form::text('name', $dyeingMachine->name ?? null , ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="heating_rate" class="custom-control-label">Heating Rate</label>
                                    {!! Form::text('heating_rate', $dyeingMachine->heating_rate ?? null , ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('heating_rate'))
                                        <span class="text-danger">{{ $errors->first('heating_rate') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="maximum_working_pressure" class="custom-control-label">Maximum Working Pressure</label>
                                    {!! Form::text('maximum_working_pressure', $dyeingMachine->maximum_working_pressure ?? null , ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('maximum_working_pressure'))
                                        <span class="text-danger">{{ $errors->first('maximum_working_pressure') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Machine Type</label>
                                    {!! Form::select('type', $machineTypes ,$dyeingMachine->type ?? null, ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('type'))
                                        <span class="text-danger">{{ $errors->first('type') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="description" class="custom-control-label">Description</label>
                                    {!! Form::text('description', $dyeingMachine->description ?? null , ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('description'))
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="cooling_rate" class="custom-control-label">Cooling Rate</label>
                                    {!! Form::text('cooling_rate', $dyeingMachine->cooling_rate ?? null , ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('cooling_rate'))
                                        <span class="text-danger">{{ $errors->first('cooling_rate') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="maximum_working_temp" class="custom-control-label">Maximum Working Temp</label>
                                    {!! Form::text('maximum_working_temp', $dyeingMachine->maximum_working_temp ?? null , ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('maximum_working_temp'))
                                        <span class="text-danger">{{ $errors->first('maximum_working_temp') }}</span>
                                    @endif
                                </div>


                                <div class="form-group">
                                    <label for="capacity" class="custom-control-label">Capacity</label>
                                    {!! Form::text('capacity', $dyeingMachine->capacity ?? null , ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('capacity'))
                                        <span class="text-danger">{{ $errors->first('capacity') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Status</label>
                                    {!! Form::select('status', $status ,$dyeingMachine->status ?? null, ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('status'))
                                        <span class="text-danger">{{ $errors->first('status') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i>
                                        {{ $dyeingMachine ? 'Update' : 'Create' }}
                                    </button>
                                    <a href="{{ route('dyeing-machine.index') }}" class="btn btn-sm btn-warning"><i
                                            class="fa fa-refresh"></i>
                                        Reset</a>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        {!! Form::open(['route' => ['dyeing-machine.index'], 'method' =>'GET']) !!}
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>
                                    <a href="{{ route('dyeing-machine.index') }}" type="submit" class="btn btn-xs btn-info">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </th>

                                <td colspan="3">
                                {!! Form::select('floor_type_filter', $floorTypes , request()->get('floor_type_filter'), ['class' => 'form-control form-control-sm']) !!}
                                </td>

                                <td colspan="3">
                                    {!! Form::text('machine_name_filter', request()->get('machine_name_filter'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search Machine Name']) !!}
                                </td>

                                <td colspan="2">
                                    {!! Form::select('machine_type_filter', $machineTypes , request()->get('machine_type_filter'), ['class' => 'form-control form-control-sm']) !!}
                                </td>

                                <td colspan="2">
                                    {!! Form::select('machine_status_filter', $status , request()->get('machine_status_filter'), ['class' => 'form-control form-control-sm']) !!}
                                </td>
                                </th>
                                <th>
                                    <button type="submit" class="btn btn-xs btn-info"><i class="fa fa-search"></i>
                                    </button>
                                </th>
                            </tr>
                            <tr>
                                <td colspan="12">&nbsp;&nbsp;</td>
                            </tr>
                            <tr>
                                <th>SL</th>
                                <th>Floor Type</th>
                                <th>Name</th>
                                <th>Heating Rate</th>
                                <th>Maximum Working Pressure</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Cooling Rate</th>
                                <th>Maximum Working Temp</th>
                                <th>Capacity</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                                @forelse ($dyeingMachines as $machine)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $machine->floor_type_value }}</td>
                                    <td>{{ $machine->name }}</td>
                                    <td>{{ $machine->heating_rate }}</td>
                                    <td>{{ $machine->maximum_working_pressure }}</td>
                                    <td>{{ $machine->machine_type_value }}</td>
                                    <td>{{ $machine->description }}</td>
                                    <td>{{ $machine->cooling_rate }}</td>
                                    <td>{{ $machine->maximum_working_temp }}</td>
                                    <td>{{ $machine->capacity }}</td>
                                    <td>{{ $machine->status_value }}</td>
                                    <td style="display: inline-flex">
                                        <a href="{{ route('dyeing-machine.edit',['dyeing_machine'=>$machine->id]) }}" class="edit-btn btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
                                         <button type="button" class="btn btn-xs btn-danger show-modal"
                                                 data-toggle="modal"
                                                 data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                 ui-target="#animate"
                                                 data-url="{{ route('dyeing-machine.destroy',['dyeing_machine'=>$machine->id])  }}">
                                             <i class="fa fa-times"></i>
                                         </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="12" align="center">No Data</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                            @if($dyeingMachines->total() > 15)
                                <tr>
                                    <td colspan="4"
                                        align="center">{{ $dyeingMachines->appends(request()->except('page'))->links() }}
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
