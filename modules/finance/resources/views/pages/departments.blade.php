@extends('finance::layout')

@section('title', 'Accounting Departments')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Accounting Departments</h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="box-body">
                    @permission('permission_of_departments_add')
                    <a class="btn btn-sm white m-b" href="{{ url('finance/departments/create') }}">
                        <i class="glyphicon glyphicon-plus"></i> New Department
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
                        <th> Department Name</th>
                        <th> Department Details</th>
                        <th> Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($departments))
                        @foreach($departments as $department)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $department->department }}</td>
                                <td>{{ $department->dept_details }}</td>
                                <td>
                                    @permission('permission_of_departments_edit')
                                    <a href="{{url('finance/departments/'.$department->id.'/edit')}}"
                                       class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top"
                                       title="Edit"><i
                                            class="fa fa-edit"></i></a>
                                    @endpermission
                                    @permission('permission_of_departments_delete')
                                    <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal"
                                            data-target="#confirmationModal" ui-toggle-class="flip-x"
                                            ui-target="#animate"
                                            data-url="{{ url('finance/departments/'.$department->id) }}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    @endpermission
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" align="center">No Data</td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    @if($departments->total() > 15)
                        <tr>
                            <td colspan="4" align="center">
                                {{ $departments->appends(request()->except('page'))->links() }}
                            </td>
                        </tr>
                    @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
