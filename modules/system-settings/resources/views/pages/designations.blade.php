@extends('skeleton::layout')
@section('title', 'Designation')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>DESIGNATIONS</h2>
            </div>
            <div class="box-body b-t">
                <div style="margin-bottom: 20px;">
                    @if(Session::has('permission_of_designation_add') || Session::get('user_role') == 'super-admin')
                        <a href="{{URL::to('designations/create')}}" class="btn btn-sm white m-b btn-sm">
                            <i class="glyphicon glyphicon-plus"></i> New Designation
                        </a>
                    @endif
                </div>
                <div class="flash-message" style="margin-top: 20px;">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                        @endif
                    @endforeach
                </div>
                <div class="table-responsive" style="margin-top: 20px;">
                    <table class="reportTable">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Designation</th>
                            <th>Company</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!$designations->getCollection()->isEmpty())
                            @foreach($designations->getCollection() as $designation)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $designation->designation }}</td>
                                    <td>{{ $designation->factory->factory_name }}</td>
                                    <td>
                                        @if(Session::has('permission_of_designation_edit') || Session::get('user_role') == 'super-admin')
                                            <a class="btn btn-sm white"
                                               href="{{URL::to('designations/'.$designation->id.'/edit')}}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif
                                        @if(Session::has('permission_of_designation_delete') || Session::get('user_role') == 'super-admin')
                                            <button type="button" class="btn btn-sm white show-modal"
                                                    data-toggle="modal" data-target="#confirmationModal"
                                                    ui-toggle-class="flip-x" ui-target="#animate"
                                                    data-url="{{ url('designations/'.$designation->id) }}">
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
                        @if($designations->total() > 15)
                            <tr>
                                <td colspan="4"
                                    align="center">{{ $designations->appends(request()->except('page'))->links() }}</td>
                            </tr>
                        @endif
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
