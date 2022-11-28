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
                    <a class="btn btn-sm white m-b" href="{{ url('finance/ac-departments/create') }}">
                        <i class="glyphicon glyphicon-plus"></i> New Department
                    </a>
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
                        <th> Company Name</th>
                        <th> Project</th>
                        <th> Cost Center</th>
                        <th> Department</th>
                        <th> Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($actualDepartments))
                        @foreach($actualDepartments as $department)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $department->company->name }}</td>
                                <td>{{ $department->unit->unit }}</td>
                                <td>{{ $department->department->name }}</td>
                                <td>{{ $department->name }}</td>
                                <td>
                                    <a href="{{url('finance/ac-departments/'.$department->id.'/edit')}}" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Edit"><i
                                            class="fa fa-edit"></i></a>
                                    <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal"
                                            data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"
                                            data-url="{{ url('finance/ac-departments/'.$department->id) }}">
                                        <i class="fa fa-times"></i>
                                    </button>
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
                    @if($actualDepartments->total() > 15)
                        <tr>
                            <td colspan="4" align="center">{{ $actualDepartments->appends(request()->except('page'))->links() }}</td>
                        </tr>
                    @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
