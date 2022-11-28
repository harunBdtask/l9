@extends('skeleton::layout')
@section('title', 'Shift')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>SHIFTS</h2>
            </div>
            <div class="box-body b-t">
                <div class="col-md-6">
                    @if(Session::has('permission_of_shifts_add') || Session::get('user_role') == 'super-admin')
                        <a href="{{ url('shifts/create') }}" class="btn btn-sm white m-b btn-sm">
                            <i class="glyphicon glyphicon-plus"></i> New Shift
                        </a>
                    @endif
                </div>
                <div class="col-md-6 pull-right">
                    <div class="form-group">
                        <div class="row m-b">
                            <form action="{{ url('/shifts') }}" method="GET">
                                <div class="col-sm-offset-5 col-sm-5">
                                    <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}"
                                           placeholder="Search here">
                                </div>
                                <div class="col-sm-2">
                                    <input type="submit" class="btn btn-sm white" value="Search">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 flash-message p-t-1">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                        @endif
                    @endforeach
                </div>
                    <div class="reportTable" style="margin-top: 20px;">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Shift Name</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Extra Time</th>
                                <th>Company</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody class="shifts-list">
                            @if(!$shifts->getCollection()->isEmpty())
                                @foreach($shifts->getCollection() as $shift)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $shift->shift_name }}</td>
                                        <td>{{ $shift->start_time ? date('h:i A', strtotime($shift->start_time)) : '' }}</td>
                                        <td>{{ $shift->end_time ? date('h:i A', strtotime($shift->end_time)) : '' }}</td>
                                        <td>{{ $shift->extra_time }}</td>
                                        <td style="background-color: #16682D; color: #e7e7e7; letter-spacing: 0.12rem;"
                                            class="font-weight-bold">{{ $shift->factory->factory_name }}</td>
                                        <td>
                                            @if(Session::has('permission_of_shifts_edit') || Session::get('user_role') == 'super-admin')
                                                <a class="btn btn-sm btn-success" href="{{ url('shifts/'.$shift->id.'/edit')}}"><i
                                                            class="fa fa-edit"></i></a>
                                            @endif
                                            @if(Session::has('permission_of_shifts_delete') || Session::get('user_role') == 'super-admin')
                                                <button type="button" class="btn btn-sm btn-danger show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('shifts/'.$shift->id) }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" align="center">No Data
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                            <tfoot>
                            @if($shifts->total() > 15)
                                <tr>
                                    <td colspan="4" align="center">{{ $shifts->appends(request()->except('page'))->links() }}</td>
                                </tr>
                            @endif
                            </tfoot>
                        </table>
                    </div>
            </div>
        </div>
    </div>
@endsection

