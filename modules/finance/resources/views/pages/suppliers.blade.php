@extends('finance::layout')

@section('title', 'Suppliers')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Suppliers</h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="box-body">
                    <a class="btn btn-sm white m-b" href="{{ url('finance/suppliers/create') }}">
                        <i class="glyphicon glyphicon-plus"></i> New Supplier
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
                        <th> Supplier No</th>
                        <th> Control Account</th>
                        <th> Supplier Name</th>
                        <th> Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($suppliers))
                        @foreach($suppliers as $supplier)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $supplier->supplier_no }}</td>
                                <td>{{ $supplier->controlAccount->name }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>
                                    <a href="{{url('finance/suppliers/'.$supplier->id.'/edit')}}"
                                       class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top"
                                       title="Edit"><i
                                            class="fa fa-edit"></i></a>
                                    <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal"
                                            data-target="#confirmationModal" ui-toggle-class="flip-x"
                                            ui-target="#animate"
                                            data-url="{{ url('finance/suppliers/'.$supplier->id) }}">
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
                    @if($suppliers->total() > 15)
                        <tr>
                            <td colspan="4" align="center">
                                {{ $suppliers->appends(request()->except('page'))->links() }}
                            </td>
                        </tr>
                    @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
