@extends('skeleton::layout')
@section('title', 'Machine')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>MACHINES</h2>
            </div>
            <div class="box-body b-t">
                <div style="margin-bottom: 20px;">
                    @if(Session::has('permission_of_machines_add') || Session::get('user_role') == 'super-admin')
                        <a href="{{url('machines/create')}}"
                           class="btn btn-sm white m-b add-new-btn btn-sm">
                            <i class="glyphicon glyphicon-plus"></i> New Machine
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
                            <th> SL</th>
                            <th> Knitting Floor </th>
                            <th> Machine Name</th>
                            <th> Machine Number</th>
                            <th> Process Type</th>
                            <th> Machine Type</th>
                            <th> Machine Dia</th>
                            <th> Machine GG</th>
                            <th> Machine RPM</th>
                            <th> Machine Capacity</th>
                            <th> Company</th>
                            <th> Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!$machines->getCollection()->isEmpty())
                            @foreach($machines->getCollection() as $machine)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ optional($machine->knittingFloor)->name }}</td>
                                    <td>{{ $machine->machine_name }}</td>
                                    <td>{{ $machine->machine_no }}</td>
                                    <td>{{ MACHINEYPES[$machine->machine_type] }}</td>
                                    <td>{{ $machine->machine_type_info }}</td>
                                    <td>{{ $machine->machine_dia }}</td>
                                    <td>{{ $machine->machine_gg }}</td>
                                    <td>{{ $machine->machine_rpm }}</td>
                                    <td>{{ $machine->machine_capacity }}</td>
                                    <td>{{ $machine->factory->factory_name }}</td>
                                    <td>
                                        @if(Session::has('permission_of_machines_edit') || Session::get('user_role') == 'super-admin')
                                            <a href="{{URL::to('machines/'.$machine->id.'/edit')}}"
                                               class="btn btn-sm btn-success"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if(Session::has('permission_of_machines_delete') || Session::get('user_role') == 'super-admin')
                                            <button type="button" class="btn btn-sm btn-danger show-modal"
                                                    data-toggle="modal" data-target="#confirmationModal"
                                                    ui-toggle-class="flip-x" ui-target="#animate"
                                                    data-url="{{ url('machines/'.$machine->id) }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" align="center">No Data
                                </td>
                            </tr>
                        @endif
                        </tbody>
                        <tfoot>
                        @if($machines->total() > 15)
                            <tr>
                                <td colspan="6"
                                    align="center">{{ $machines->appends(request()->except('page'))->links() }}</td>
                            </tr>
                        @endif
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
