@extends('skeleton::layout')
@section('title', 'Operator')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>OPERATORS</h2>
            </div>
            <div class="box-body b-t">
                <div class="col-md-6">
                    @if(Session::has('permission_of_operators_add') || Session::get('user_role') == 'super-admin')
                        <a href="{{ url('operators/create') }}" class="btn btn-sm white m-b btn-sm">
                            <i class="glyphicon glyphicon-plus"></i> New Operator
                        </a>
                    @endif
                </div>
                <div class="col-md-6 pull-right">
                    <div class="form-group">
                        <div class="row m-b">
                            <form action="{{ url('/operators') }}" method="GET">
                                <div class="col-sm-offset-5 col-sm-5">
                                    <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}"
                                           placeholder="Operator Name, Code">
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
                <table class="reportTable">
                    <thead>
                    <tr>
                        <th> SL</th>
                        <th> Operator Name</th>
                        <th> Operator Type</th>
                        <th> Operator Code</th>
                        <th> Company</th>
                        <th> Action</th>
                    </tr>
                    </thead>
                    <tbody class="operators-list">
                    @if(!$operators->getCollection()->isEmpty())
                        @foreach($operators->getCollection() as $operator)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $operator->operator_name }}</td>
                                <td>{{ OPERATORTYPES[$operator->operator_type] }}</td>
                                <td>{{ $operator->operator_code }}</td>
                                <td style="background-color: #16682D; color: #e7e7e7; letter-spacing: 0.12rem;"
                                    class="font-weight-bold">{{ $operator->factory->factory_name }}</td>
                                <td>
                                    @if(Session::has('permission_of_operators_edit') || Session::get('user_role') == 'super-admin')
                                        <a href="{{ url('operators/'.$operator->id.'/edit')}}" class="btn btn-sm white"><i
                                                    class="fa fa-edit"></i></a>
                                    @endif
                                    @if(Session::has('permission_of_operators_delete') || Session::get('user_role') == 'super-admin')
                                        <button type="button" class="btn btn-sm white show-modal" data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate" data-url="{{ url('operators/'.$operator->id) }}">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    @endif
                                </td>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" align="center">No Data
                            </td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    @if($operators->total() > 15)
                        <tr>
                            <td colspan="6"
                                align="center">{{ $operators->appends(request()->except('page'))->links() }}</td>
                        </tr>
                    @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
