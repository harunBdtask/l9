@extends('skeleton::layout')
@section("title","Designations")
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Designations</h2>
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
                                {!! Form::model($designation, ['url' => $designation ? 'hr/designations/'.$designation->id : 'hr/designations', 'method' => $designation ? 'PUT' : 'POST']) !!}

                                    <div class="form-group">
                                        <label for="name">Designation Name</label>
                                        {!! Form::text('name' , null, ['class' => 'form-control form-control-sm']) !!}

                                        @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="name_bn">পদবী (বাংলায়)</label>
                                        {!! Form::text('name_bn' , null, ['class' => 'form-control form-control-sm']) !!}

                                        @error('name_bn')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" id="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i>
                                            {{ $designation ? 'Update' : 'Create' }}
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
                            @forelse($designations as $designation)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $designation->id }}</td>
                                    <td>{{ $designation->name }}</td>
                                    <td>{{ $designation->name_bn }}</td>
                                    <td>

                                        <a href="{{ url('hr/designations/edit', ['id' => $designation->id]) }}" class="edit-btn btn btn-xs btn-success"><i class="fa fa-edit"></i></a>

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
                            {{ $designations->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')

@endpush
