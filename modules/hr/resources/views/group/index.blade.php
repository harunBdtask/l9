@extends('skeleton::layout')
@section("title","Groups")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Group</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('hr/groups') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ request('search') }}" placeholder="Search">
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
                        <div class="box form-colors">
                            <div class="box-header">
                                {!! Form::open(['url' => isset($group) ? 'hr/groups/'.$group->id : 'hr/groups', 'method' => isset($group) ? 'PUT' : 'POST']) !!}

                                <div class="form-group">
                                    <label for="name">Group Name</label>
                                    {!! Form::text('name' , $group->name ?? null, ['class' => 'form-control form-control-sm']) !!}

                                    @error('name')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="medical_fee">Medical Fee(৳)</label>
                                    {!! Form::text('medical_fee' , $group->medical_fee ?? null, ['class' => 'form-control form-control-sm']) !!}

                                    @error('medical_fee')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="transport_fee">Transportation fee(৳)</label>
                                    {!! Form::text('transport_fee' , $group->transport_fee ?? null, ['class' => 'form-control form-control-sm']) !!}

                                    @error('transport_fee')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="food_fee">Food cost(৳)</label>
                                    {!! Form::text('food_fee' , $group->food_fee ?? null, ['class' => 'form-control form-control-sm']) !!}

                                    @error('food_fee')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-sm btn-success">
                                        <em class="fa fa-save"></em>
                                        {{ isset($group) ? 'Update' : 'Create' }}
                                    </button>
                                    <a href="{{ url('hr/groups') }}" class="btn btn-sm btn-warning">
                                        <em class="fa fa-refresh"></em> Refresh</a>
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
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($groups as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->id }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>
                                        <a href="{{ url('hr/groups/edit', ['id' => $data->id]) }}"
                                           class="edit-btn btn btn-xs btn-success">
                                            <em class="fa fa-edit"></em>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $groups->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')

@endpush
