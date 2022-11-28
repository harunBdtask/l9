@extends('skeleton::layout')
@section('title', 'Fabric Types')
@section('content')
    <div class="padding">
        @if(Session::has('permission_of_fabric_type_view') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
            <div class="box" >
                <div class="box-header">
                    <h2>Fabric Types</h2>
                </div>
                <div class="box-body b-t">
                    <div style="margin-bottom: 20px;">
                        @if(Session::has('permission_of_fabric_type_add') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
                            <a href="{{ url('fabric-types/create') }}" class="btn btn-sm white m-b btn-sm">
                                <i class="glyphicon glyphicon-plus"></i> New Fabric Type
                            </a>
                        @endif
                    </div>
                    <div class="flash-message" style="margin-top: 20px;">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                            @endif
                        @endforeach
                    </div>
                    <div class="table-responsive" style="margin-top: 20px;">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Fabric Type</th>
                                <th>Company</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!$fabric_types->getCollection()->isEmpty())
                                @foreach($fabric_types->getCollection() as $fabric)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $fabric->fabric_type_name }}</td>
                                        <td>{{ $fabric->factory->factory_name }}</td>
                                        <td>
                                            @if(Session::has('permission_of_fabric_type_edit') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
                                                <a href="{{ url('fabric-types/'.$fabric->id.'/edit')}}"
                                                   class="btn btn-sm btn-success"><i class="fa fa-edit"></i></a>
                                            @endif
                                            @if(Session::has('permission_of_fabric_type_delete') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
                                                <button type="button" class="btn btn-sm white show-modal"
                                                        data-toggle="modal" data-target="#confirmationModal"
                                                        ui-toggle-class="flip-x" ui-target="#animate"
                                                        data-url="{{ url('fabric-types/'.$fabric->id) }}">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" align="center">No Data
                                    <td>
                                </tr>
                            @endif
                            </tbody>
                            <tfoot>
                            @if($fabric_types->total() > 15)
                                <tr>
                                    <td colspan="3"
                                        align="center">{{ $fabric_types->appends(request()->except('page'))->links() }}</td>
                                </tr>
                            @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

