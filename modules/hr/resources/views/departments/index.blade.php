@extends('skeleton::layout')
@section("title","Departments")
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Department Details</h2>
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
                                {!! Form::model($department, ['url' => $department ? 'hr/departments/'.$department->id : 'hr/departments', 'method' => $department ? 'PUT' : 'POST']) !!}

                                    <div class="form-group">
                                        <label for="name">Department Name</label>
                                        {!! Form::text('name' , null, ['class' => 'form-control form-control-sm']) !!}

                                        @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="name_bn">বিভাগ (বাংলায়)</label>
                                        {!! Form::text('name_bn' , null, ['class' => 'form-control form-control-sm']) !!}

                                        @error('name_bn')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" id="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i>
                                            {{ $department ? 'Update' : 'Create' }}
                                        </button>
                                        <a href="{{ url('hr/departments') }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
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
                                <th>ID</th>
                                <th>Name</th>
                                <th>নাম (বাংলায়)</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($departments as $department)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $department->id }}</td>
                                    <td>{{ $department->name }}</td>
                                    <td>{{ $department->name_bn }}</td>
                                    <td>

                                        <a href="{{ url('hr/departments/edit', ['id' => $department->id]) }}" class="edit-btn btn btn-xs btn-success"><i class="fa fa-edit"></i></a>

                                        {{-- <button type="button" class="btn btn-xs danger show-modal"
                                                data-toggle="modal" data-target="#confirmationModal"
                                                ui-toggle-class="flip-x" ui-target="#animate"
                                                data-url="{{ url('hr/departments/'.$department->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button> --}}
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
                            {{ $departments->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')

@endpush
