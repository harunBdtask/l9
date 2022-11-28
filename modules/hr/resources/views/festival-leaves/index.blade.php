@extends('skeleton::layout')
@section("title","Festival Leaves")
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Festival Leaves</h2>
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
                                {!! Form::model($festival, ['url' => $festival ? 'hr/festival-leaves/'.$festival->id : 'hr/festival-leaves', 'method' => $festival ? 'PUT' : 'POST']) !!}

                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        {!! Form::text('name' , null, ['class' => 'form-control form-control-sm']) !!}
                                        
                                        @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="leave_date">Leave Date</label>
                                        {!! Form::date('leave_date' , null, ['class' => 'form-control form-control-sm']) !!}
                                        
                                        @error('leave_date')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" id="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> 
                                            {{ $festival ? 'Update' : 'Create' }}
                                        </button>
                                        <a href="{{ url('hr/festival-leaves/') }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
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
                                <th>Leave Date</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($festivals as $festival)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $festival->name }}</td>
                                    <td>{{ $festival->leave_date }}</td>
                                    <td>
                                        <a href='/hr/festival-leaves/edit/{{$festival->id}}' class="edit-btn btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
                                        
                                        <button type="button" class="btn btn-xs danger show-modal"
                                                data-toggle="modal" data-target="#confirmationModal"
                                                ui-toggle-class="flip-x" ui-target="#animate"
                                                data-url="{{ url('hr/festival-leaves/'.$festival->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
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
                            {{ $festivals->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')
    
@endpush
