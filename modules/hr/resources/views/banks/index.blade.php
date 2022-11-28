@extends('skeleton::layout')
@section("title","Banks")
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Banks</h2>
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

                                    {!! Form::model($bank, ['url' => $bank ? 'hr/banks/'.$bank->id : 'hr/banks/', 'method' => $bank ? 'PUT' : 'POST']) !!}

                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        {!! Form::text('name' , null, ['class' => 'form-control form-control-sm']) !!}

                                        @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="branch">Branch</label>
                                        {{ Form::text('branch', null, ['class' => 'form-control']) }}
                                        @error('branch')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        {{ Form::textarea('address', null, ['class' => 'form-control', 'rows' => 3, ]) }}
                                        @error('address')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" id="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i>
                                            Create
                                        </button>
                                        <a href="{{ url('hr/banks') }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
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
                                <th>Branch</th>
                                <th>Address</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($banks as $bank)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $bank->name }}</td>
                                    <td>{{ $bank->branch }}</td>
                                    <td>{{ $bank->address }}</td>
                                    <td>
                                        <a href="/hr/banks/{{$bank->id}}/edit" class="edit-btn btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
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
                             {{ $banks->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')

@endpush
