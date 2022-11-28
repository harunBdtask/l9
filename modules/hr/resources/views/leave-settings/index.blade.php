@extends('skeleton::layout')
@section("title","Leave")
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Leave Settings </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('currencies-search') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ $search ?? '' }}" placeholder="Search">
                                <span class="input-group-btn">
                                    <button class="btn btn-sm white m-b" type="submit">Search</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12 col-md-5">
                        <div class="box form-colors" >
                            <div class="box-header">
                                {!! Form::model($leaveSetting, ['url' => $leaveSetting ? 'hr/leave-settings/'.$leaveSetting->id : 'hr/leave-settings', 'method' => $leaveSetting ? 'PUT' : 'POST']) !!}

                                @if($leaveSetting)
                                    <div class="form-group">
                                        <label for="employee">Employee Type</label>
                                        {!! Form::select('employee_type[]', $employeeTypes, null, ['class' => 'form-control form-control-sm c-select', 'id' => 'employee_type']) !!}
                                        @if($errors->has('employee_type'))
                                            <span class="text-danger">
                                                {{ $errors->first('employee_type') }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label for="employee">Employee Type</label>
                                        {!! Form::select('employee_type[]', $employeeTypes, null, ['class' => 'form-control form-control-sm select2-input c-select form-control form-control-sm-sm', 'id' => 'employee_type', 'multiple' => 'multiple']) !!}
                                        @if($errors->has('employee_type'))
                                            <span class="text-danger">
                                                {{ $errors->first('employee_type') }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                    <div class="form-group">
                                        <label for="party_type">Leave Type</label>
                                        {!! Form::select('leave_type_id', $leaveTypes, $leaveSetting->leave_types_id ?? null, ['class' => 'form-control form-control-sm c-select', 'id' => 'leave_type_id', 'placeholder' => 'Select Leave Types']) !!}
                                        @if($errors->has('leave_type_id'))
                                            <span class="text-danger">
                                                {{ $errors->first('leave_type_id') }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="number_of_days">Number of Days</label>
                                        {!! Form::text('number_of_days' , null, ['class' => 'form-control form-control-sm']) !!}

                                        @error('number_of_days')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" id="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i>
                                            {{ $leaveSetting ? 'Update' : 'Create' }}
                                        </button>
                                        <a href="{{ url('hr/leave-settings') }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
                                    </div>

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Employee Type</th>
                                <th>Leave Type</th>
                                <th>Number Of Days</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($leaveSettings as $leaveSetting)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ Str::ucfirst($leaveSetting->employee_type) }}</td>
                                    <td>{{ $leaveSetting->leaveType->name }}</td>
                                    <td>{{ $leaveSetting->number_of_days }}</td>
                                    <td>
                                        <a href='/hr/leave-settings/{{$leaveSetting->id}}/edit' class="edit-btn btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" align="center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $leaveSettings->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')

@endpush
