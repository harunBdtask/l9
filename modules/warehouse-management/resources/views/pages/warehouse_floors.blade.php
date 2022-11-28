@extends('warehouse-management::layout')
@section('title', 'Warehouse Floors')

@section('content')
    <div class="padding">
        @if(Session::has('permission_of_warehouse_floors_view') || getRole() == 'super-admin' || getRole() == 'admin')
            <div class="box">
                <div class="box-header">
                    <h2>Warehouse Floors</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="box-body b-t">
                    <div>
                        @if(Session::has('permission_of_warehouse_floors_add') || getRole() == 'super-admin' || getRole() == 'admin')
                            <a href="{{url('warehouse-floors/create')}}" class="btn btn-info add-new-btn btn-sm">
                                <i class="glyphicon glyphicon-plus"></i> Add New
                            </a>
                        @endif
                        <div class="pull-right">
                            <form action="{{ url('/warehouse-floors/search') }}" method="GET">
                                <div class="pull-left" style="margin-right: 10px;">
                                    <input type="text" class="form-control" name="q" value="{{ $q ?? '' }}">
                                </div>
                                <div class="pull-right">
                                    <input type="submit" class="btn btn-md btn-info" value="Search">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-top: 20px; min-height: 300px;">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Floor Name/ No</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!$warehouse_floors->getCollection()->isEmpty())
                                @foreach($warehouse_floors->getCollection() as $warehouse_floor)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $warehouse_floor->name }}</td>
                                        <td>
                                            <div class="dropdown inline">
                                                <button class="btn btn-xs white dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action
                                                </button>
                                                <div class="dropdown-menu pull-right">
                                                    @if (Session::has('permission_of_warehouse_floors_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                                        <a href="{{ url('/warehouse-floors/' . $warehouse_floor->id.'/edit') }}" class="dropdown-item">Edit</a>
                                                    @endif
                                                    @if (Session::has('permission_of_warehouse_floors_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                                        <a href="#" class="dropdown-item show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('/warehouse-floors/' . $warehouse_floor->id) }}">Delete</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($warehouse_floors->total() > 15)
                                    <tr>
                                        <td colspan="3" class="text-center">{{ $warehouse_floors->appends(request()->except('page'))->links() }}</td>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <td colspan="3" class="text-center">No Data</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
