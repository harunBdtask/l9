@extends('skeleton::layout')
@section("title","Shifts")
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Shifts </h2>
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
                                {!! Form::model($hrShift, ['url' => $hrShift ? 'hr/shifts/'.$hrShift->id : 'hr/shifts/', 'method' => $hrShift ? 'PUT' : 'POST']) !!}

                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        {!! Form::text('name' , null, ['class' => 'form-control form-control-sm']) !!}

                                        @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="start_time">Start Time</label>
                                        {!! Form::time('start_time', null, ['class' => 'form-control form-control-sm']) !!}

                                        @error('start_time')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="end_time">End Time</label>
                                        {!! Form::time('end_time' , null, ['class' => 'form-control form-control-sm']) !!}

                                        @error('end_time')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" id="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i>
                                            {{ $hrShift ? 'Update' : 'Create' }}
                                        </button>
                                        <a href="{{ url('hr/shifts') }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
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
                                <th>Name</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($hrShifts as $hrShift)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $hrShift->name }}</td>
                                    <td>{{ $hrShift->start_time }}</td>
                                    <td>{{ $hrShift->end_time }}</td>
                                    <td>
                                        <a href='/hr/shifts/{{$hrShift->id}}/edit' class="edit-btn btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" align="center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                             {{ $hrShifts->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')

@endpush
