@extends('finance::layout')

@section('title', 'Accounting Cost Centers')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Accounting Cost Centers</h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="box-body">
                    @permission('permission_of_cost_centers_add')
                    <a class="btn btn-sm white m-b" href="{{ url('basic-finance/cost-centers/create') }}">
                        <i class="glyphicon glyphicon-plus"></i> New Cost Center
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
                        <th> Cost Center Name</th>
                        <th> Cost Center Details</th>
                        <th> Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($costCenters))
                        @foreach($costCenters as $costCenter)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $costCenter->cost_center }}</td>
                                <td>{{ $costCenter->cost_center_details }}</td>
                                <td>
                                    @permission('permission_of_cost_centers_edit')
                                    <a href="{{url('basic-finance/cost-centers/'.$costCenter->id.'/edit')}}" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Edit"><i
                                            class="fa fa-edit"></i></a>
                                    @endpermission
                                    @permission('permission_of_cost_centers_delete')
                                    <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal"
                                            data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"
                                            data-url="{{ url('basic-finance/cost-centers/'.$costCenter->id) }}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    @endpermission
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" align="center">No Data</td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    @if($costCenters->total() > 15)
                        <tr>
                            <td colspan="4" align="center">{{ $costCenters->appends(request()->except('page'))->links() }}</td>
                        </tr>
                    @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
