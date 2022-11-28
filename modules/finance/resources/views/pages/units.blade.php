@extends('finance::layout')

@section('title', 'Accounting Units')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Accounting Units</h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="box-body">
                    @permission('permission_of_units_add')
                    <a class="btn btn-sm white m-b" href="{{ url('finance/units/create') }}">
                        <i class="glyphicon glyphicon-plus"></i> New Unit
                    </a>
                    @endpermission
                </div>
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                        @endif
                    @endforeach
                </div>
                <table class="reportTable">
                    <thead>
                    <tr>
                        <th> SL</th>
                        <th> Factory Name</th>
                        <th> Project Name</th>
                        <th> Unit Name</th>
                        <th> Head of Unit</th>
                        <th> Phone No</th>
                        <th> Email</th>
                        <th> Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($units))
                        @foreach($units as $unit)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $unit->factory->factory_name }}</td>
                                <td>{{ $unit->project->project }}</td>
                                <td>{{ $unit->unit }}</td>
                                <td>{{ $unit->unit_head_name }}</td>
                                <td>{{ $unit->phone_no }}</td>
                                <td>{{ $unit->email }}</td>
                                <td>
                                    @permission('permission_of_units_edit')
                                    <a href="{{url('finance/units/'.$unit->id.'/edit')}}" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Edit"><i
                                            class="fa fa-edit"></i></a>
                                    @endpermission
                                    @permission('permission_of_units_delete')
                                    <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal"
                                            data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"
                                            data-url="{{ url('finance/units/'.$unit->id) }}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    @endpermission
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" align="center">No Data</td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    @if($units->total() > 15)
                        <tr>
                            <td colspan="8" align="center">{{ $units->appends(request()->except('page'))->links() }}</td>
                        </tr>
                    @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
