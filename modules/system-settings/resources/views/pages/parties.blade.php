@extends('skeleton::layout')
@section('title', 'Party')
@section('content')
    <!-- ############ PAGE START-->
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>PARTIES</h2>
            </div>
            <div class="box-body b-t">
                <div style="margin-bottom: 20px;">
                    @if(Session::has('permission_of_parties_add') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
                        <a href="{{url('parties/create')}}" class="btn btn-sm white m-b btn-sm">
                            <i class="glyphicon glyphicon-plus"></i> New Party
                        </a>
                    @endif
                </div>
                <div class="flash-message" style="margin-top: 20px;">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                        @endif
                    @endforeach
                </div>{{-- .flash-message --}}
                <div class="table-responsive" style="margin-top: 20px;">
                    <table class="reportTable">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Party Name</th>
                            <th>Party Types</th>
                            <th>Company</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody class="parties-list">
                        @if(!$parties->getCollection()->isEmpty())
                            @foreach($parties->getCollection() as $party)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $party->party_name }}</td>
                                    <td>{{ $party->party_types->party_type }}</td>
                                    <td>{{ $party->factory->factory_name }}</td>
                                    <td>
                                        @if(Session::has('permission_of_parties_add') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
                                            <a href="{{url('parties/'.$party->id.'/edit')}}" class="btn btn-sm white"><i
                                                        class="fa fa-edit"></i></a>
                                        @endif
                                        @if(Session::has('permission_of_parties_delete') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
                                            <button type="button" class="btn btn-sm white show-modal"
                                                    data-toggle="modal" data-target="#confirmationModal"
                                                    ui-toggle-class="flip-x" ui-target="#animate"
                                                    data-url="{{ url('parties/'.$party->id) }}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" align="center">No Data
                                </td>
                            </tr>
                        @endif
                        </tbody>
                        <tfoot>
                        @if($parties->total() > 15)
                            <tr>
                                <td colspan="5"
                                    align="center">{{ $parties->appends(request()->except('page'))->links() }}</td>
                            </tr>
                        @endif
                        </tfoot>
                    </table>
                </div>
            </div>{{-- .box-body --}}
        </div>{{-- .box --}}
    </div>{{-- .padding --}}
@endsection
