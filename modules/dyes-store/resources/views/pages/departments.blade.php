@extends('dyes-store::layout')
@section('title', 'Department')
@section('content')

    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Departments</h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="box-body">
                    <a style="margin-left: -1.5%;" href="{{ url('/dyes-store/department/create') }}"
                       class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Create Department
                    </a>
                </div>

                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SI</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($departments as $department)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $department->name }}</td>
                                        <td>
                                            <a href="{{url('/dyes-store/department/'.$department->id.'/edit')}}"
                                               class="btn btn-xs btn-success"
                                               data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                    class="fa fa-edit"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
