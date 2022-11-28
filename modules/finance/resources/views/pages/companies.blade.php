@extends('finance::layout')

@section('title', 'Accounting Company')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Accounting Company</h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="box-body">
                    <a class="btn btn-sm white m-b" href="{{ url('finance/ac-companies/create') }}">
                        <i class="glyphicon glyphicon-plus"></i> New Company
                    </a>
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
                        <th> Company Name</th>
                        <th> Group Name</th>
                        <th> Corporate Address</th>
                        <th> Factory Address</th>
                        <th> TIN </th>
                        <th> Country </th>
                        <th> Phone no</th>
                        <th> Email Address</th>
                        <th> Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!$companies->getCollection()->isEmpty())
                        @foreach($companies->getCollection() as $cmp)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $cmp->name }}</td>
                                <td>{{ $cmp->group_name }}</td>
                                <td>{{ $cmp->corporate_address }}</td>
                                <td>{{ $cmp->factory_address }}</td>
                                <td>{{ $cmp->tin }}</td>
                                <td>{{ $cmp->country }}</td>
                                <td>{{ $cmp->phone_no }}</td>
                                <td>{{ $cmp->email }}</td>
                                <td>
                                    <a href="{{url('finance/ac-companies/'.$cmp->id.'/edit')}}" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                    <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal"
                                            data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"
                                            data-url="{{ url('finance/ac-companies/'.$cmp->id) }}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" align="center">No Data</td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    @if($companies->total() > 15)
                        <tr>
                            <td colspan="4" align="center">{{ $companies->appends(request()->except('page'))->links() }}</td>
                        </tr>
                    @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
