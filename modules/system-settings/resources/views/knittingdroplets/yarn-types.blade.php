@extends('skeleton::layout')

@section('content')
    <!-- ############ PAGE START-->
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Yarn Types</h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="col-md-6">
                    @if(Session::has('permission_of_yarn_types_add') || Session::get('user_role') == 'super-admin')
                        <a href="{{url('yarn-types/create')}}" class="btn btn-sm white m-b btn-sm">
                            <i class="glyphicon glyphicon-plus"></i>Add New Yarn Type
                        </a>
                    @endif
                </div>
                <div class="col-md-6 pull-right">
                    <div class="form-group">
                        <div class="row m-b">
                            <form action="{{ url('/yarn-types/search') }}" method="GET">
                                <div class="col-sm-5 col-sm-offset-5">
                                    <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}"
                                           placeholder="Yarn Type">
                                </div>
                                <div class="col-sm-2">
                                    <input type="submit" class="btn btn-sm white" value="Search">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="reportTable" style="margin-top: 20px;">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Yarn Types</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!$yarn_types->getCollection()->isEmpty())
                            @foreach($yarn_types->getCollection() as $yarn_type)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $yarn_type->yarn_type }}</td>
                                    <td>
                                        @if(Session::has('permission_of_yarn_types_edit') || Session::get('user_role') == 'super-admin')
                                            <a href="{{ url('yarn-types/'.$yarn_type->id.'/edit') }}" class="btn btn-sm btn-success"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if(Session::has('permission_of_yarn_types_delete') || Session::get('user_role') == 'super-admin')
                                            <button type="button" class="btn btn-sm white show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('yarn-types/'.$yarn_type->id) }}">
                                                <i class="fa fa-times"></i>
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
                        @if($yarn_types->total() > 15)
                            <tr>
                                <td colspan="4" align="center">{{ $yarn_types->appends(request()->except('page'))->links() }}</td>
                            </tr>
                        @endif
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
