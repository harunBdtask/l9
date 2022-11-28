@extends('skeleton::layout')
@section('title', 'Knit Fabric Fault Setting')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Knit Fabric Fault Setting</h2>
            </div>
            <div class="box-body b-t">
                <div class="col-md-6">
                    <a href="{{ url('knit_fabric_fault_settings/create') }}" class="btn btn-sm white m-b btn-sm">
                        <i class="glyphicon glyphicon-plus"></i> New Knit Fabric Fault Setting
                    </a>
                </div>
                <div class="col-md-6 pull-right">
                    <div class="form-group">
                        <div class="row m-b">
                            <form action="{{ url('/knit_fabric_fault_settings') }}" method="GET">
                                <div class="col-sm-offset-5 col-sm-5">
                                    <input type="text" class="form-control form-control-sm" name="q"
                                           value="{{ request()->get('q') }}"
                                           placeholder="Search here">
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
                <div class="reportTable" style="margin-top: 20px;">
                    <table class="reportTable">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Sequence</th>
                            <th>Fault Name</th>
                            <th>Status</th>
                            <th>Company</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody class="shifts-list">
                        @forelse($knit_fabric_fault as $value)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $value->sequence }}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->status == 1 ? 'Active' : 'Inactive' }}</td>
                                <td class="font-weight-bold">{{ $value->factory->factory_name }}</td>
                                <td>
                                    <a class="btn btn-sm btn-success"
                                       href="{{ url('knit_fabric_fault_settings/' . $value->id . '/edit')}}">
                                        <i class="fa fa-edit"></i></a>
                                    <button type="button" class="btn btn-sm btn-danger show-modal" data-toggle="modal"
                                            data-target="#confirmationModal" ui-toggle-class="flip-x"
                                            ui-target="#animate" data-url="{{ url('knit_fabric_fault_settings/'.$value->id) }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" align="center">No Data
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    {{ $knit_fabric_fault->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

