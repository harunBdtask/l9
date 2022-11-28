@extends('finance::layout')
@section('title', 'Accounting Projects')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Accounting Projects</h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="box-body">
                    @permission('permission_of_projects_add')
                    <a class="btn btn-sm white m-b" href="{{ url('finance/projects/create') }}">
                        <i class="glyphicon glyphicon-plus"></i> New Project
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
                        <th> Name of Project Head</th>
                        <th> Phone No</th>
                        <th> Email</th>
                        <th> Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($projects))
                        @foreach($projects as $project)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $project->factory->factory_name }}</td>
                                <td>{{ $project->project }}</td>
                                <td>{{ $project->project_head_name }}</td>
                                <td>{{ $project->phone_no }}</td>
                                <td>{{ $project->email }}</td>
                                <td>
                                    @permission('permission_of_projects_edit')
                                    <a href="{{url('finance/projects/'.$project->id.'/edit')}}" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Edit"><i
                                            class="fa fa-edit"></i></a>
                                    @endpermission
                                    @permission('permission_of_projects_delete')
                                    <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal"
                                            data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"
                                            data-url="{{ url('finance/projects/'.$project->id) }}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    @endpermission
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" align="center">No Data</td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    @if($projects->total() > 15)
                        <tr>
                            <td colspan="4" align="center">{{ $projects->appends(request()->except('page'))->links() }}</td>
                        </tr>
                    @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
