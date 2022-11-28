@extends('skeleton::layout')
@section('title', 'Company List')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Factory List</h2>
            </div>
            <div class="box-body">
                <div class="col-md-6">
                    @if(Session::has('permission_of_factories_add') || getRole() == 'super-admin')
                        <a class="btn btn-sm white m-b b-t m-b-1" href="{{ url('factories/create') }}">
                            <i class="glyphicon glyphicon-plus"></i> New Factory
                        </a>
                    @endif
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-3">
                    {!! Form::open(['url' => 'factories', 'method' => 'GET']) !!}
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" name="q"
                               value="{{ request('q') ?? '' }}" placeholder="Search">
                        <span class="input-group-btn">
                            <button class="btn btn-sm white m-b" type="submit">Search</button>
                        </span>
                    </div>
                    {!! Form::close() !!}
                </div>
                @include('partials.response-message')

                <table class="reportTable">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Group Name</th>
                        <th>Factory Name</th>
                        <th>Factory Name Bn</th>
                        <th>Factory Short Name</th>
                        <th>Factory Address</th>
                        <th>Factory Address Bn</th>
                        <th>Resposible Person</th>
                        <th>Phone No.</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($factories))
                        @foreach($factories as $factory)
                            <tr class="tr-height">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $factory->group_name }}</td>
                                <td>{{ $factory->factory_name }}</td>
                                <td>{{ $factory->factory_name_bn }}</td>
                                <td>{{ $factory->factory_short_name }}</td>
                                <td>{{ $factory->factory_address }}</td>
                                <td>{{ $factory->factory_address_bn }}</td>
                                <td>{{ $factory->responsible_person }}</td>
                                <td>{{ $factory->phone_no }}</td>
                                <td>
                                    @if(Session::has('permission_of_factories_edit') || getRole() == 'super-admin')
                                        <a class="btn btn-xs btn-success"
                                           href="{{ url('/factories/'.$factory->id.'/edit') }}">
                                            <i class="fa fa-fw fa-edit"></i></a>
                                    @endif
{{--                                    @if(Session::has('permission_of_factories_delete') || getRole() == 'super-admin')--}}
{{--                                        <a class="btn btn-xs btn-danger"--}}
{{--                                           href="{{ url('/factories/'.$factory->id.'/delete') }}"><i--}}
{{--                                                class="fa fa-fw fa-trash-o del-confirm"></i></a>--}}
{{--                                    @endif--}}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="tr-height">
                            <td colspan="7" class="text-center text-danger">No Factories
                            <td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    @if($factories->total() > 15)
                        <tr>
                            <td colspan="5"
                                class="text-center">{{ $factories->appends(request()->except('page'))->links() }}</td>
                        </tr>
                    @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
