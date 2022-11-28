@extends('basic-finance::layout')
@section('title', 'Cheque Books')
@section('styles')
    <style type="text/css">
        .addon-btn-primary {
            padding: 0;
            margin: 0px;
            background: #0275d8;
        }

        .addon-btn-primary:hover {
            background: #025aa5;
        }

        .reportTable th.text-left, .reportTable td.text-left {
            text-align: left;
            padding-left: 5px;
        }

        .reportTable th.text-right, .reportTable td.text-right {
            text-align: right;
            padding-right: 5px;
        }

        .reportTable th.text-center, .reportTable td.text-center {
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Cheque Books</h2>
            </div>
            <div class="box-body b-t">
                <div style="margin-bottom: 15px">
                    @permission('permission_of_cheque_books_add')
                    <a class="btn btn-sm white" href="{{ url('basic-finance/cheque-books/create') }}">
                        <i class="glyphicon glyphicon-plus"></i> New Cheque Book
                    </a>
                    @endpermission
                    <div class="pull-right" style="width: 40%">
                        <form action="{{ url('basic-finance/cheque-books') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Search by Bank/Account Name or Cheque No">
                                <div class="input-group-addon addon-btn-primary">
                                    <button class="btn btn-sm btn-primary">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if(Session::has('success'))
                    <div class="col-md-6 col-md-offset-3 alert alert-success alert-dismissible text-center">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <small>{{ Session::get('success') }}</small>
                    </div>
                @elseif(Session::has('failure'))
                    <div class="col-md-6 col-md-offset-3 alert alert-danger alert-dismissible text-center">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <small>{{ Session::get('failure') }}</small>
                    </div>
                @endif

                <table class="reportTable">
                    <thead class="thead-light">
                    <tr>
                        <th>Sl No.</th>
                        <th>Cheque Book No</th>
                        <th>Cpmpany</th>
                        <th>Bank Name</th>
                        <th>Bank Account Name</th>
                        <th>Cheque Start Form</th>
                        <th>Cheque End</th>
                        <th>Total Cheque Number</th>
                        <th>Used Cheque</th>
                        <th>Canceled Cheque</th>
                        <th>Unsued Cheque</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($cheque_books as $cheque_book)
                        @php
                            $groupByCheque = collect($cheque_book->details)->groupBy('status');
                            $used = $calceled = $unused = 0;
                            foreach($groupByCheque as $key=>$value){
                                if($key == 1){
                                    $unused = $unused + count($value);
                                }elseif ($key == 2){
                                    $calceled = $calceled + count($value);
                                }elseif ($key == 3){
                                    $used = $used + count($value);
                                }elseif ($key == 4){
                                    $used = $used + count($value);
                                }elseif ($key == 5){
                                    $calceled = $calceled + count($value);
                                }elseif ($key == 6){
                                    $used = $used + count($value);
                                }elseif ($key == 7){
                                    $used = $used + count($value);
                                }
                            }
                        @endphp
                        <tr class="tr-height">
                            <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $cheque_book->cheque_book_no }}</td>
                            <td>{{ $cheque_book->bankAccount->factory->factory_name }}</td>
                            <td>{{ $cheque_book->bank->account->name }}</td>
                            <td>{{ $cheque_book->bankAccount->account_number }}</td>
                            <td>{{ $cheque_book->cheque_no_from }}</td>
                            <td>{{ $cheque_book->cheque_no_to }}</td>
                            <td>{{ $cheque_book->cheque_no_to - $cheque_book->cheque_no_from + 1 }}</td>
                            <td>{{ $used }}</td>
                            <td>{{ $calceled }}</td>
                            <td>{{ $unused }}</td>
                            <td>
                                @permission('permission_of_cheque_books_edit')
                                <a class="btn btn-xs btn-success"
                                   href="{{ url('basic-finance/cheque-books/'.$cheque_book->id.'/edit') }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @endpermission
                                @permission('permission_of_cheque_books_view')
                                <a href="{{ url('basic-finance/cheque-books/'.$cheque_book->id.'/view') }}"
                                   class="btn btn-xs btn-primary">
                                    <i class="fa fa-eye"></i>
                                </a>
                                @endpermission
                                {{--                                {{ Form::open(['url' => "basic-finance/cheque-books/$cheque_book->id", 'method'=>'DELETE'])  }}--}}
                                {{--                                <button onclick="return confirm('Are you sure?')" class="btn btn-xs btn-danger">--}}
                                {{--                                    <i class="fa fa-trash"></i>--}}
                                {{--                                </button>--}}
                                {{--                                {{ Form::close()  }}--}}
                                @permission('permission_of_cheque_books_delete')
                                <button style="margin-left: 2px;" type="button"
                                        class="btn btn-xs btn-danger show-modal" title="Delete Budget"
                                        data-toggle="modal" data-target="#confirmationModal"
                                        ui-toggle-class="flip-x"
                                        ui-target="#animate"
                                        data-url="{{ url('/basic-finance/cheque-books/'.$cheque_book->id. '/delete') }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                                @endpermission
                            </td>
                        </tr>
                    @empty
                        <tr class="tr-height">
                            <td colspan="6" class="text-center text-danger">No Account Found</td>
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    @if($cheque_books->total() > 15)
                        <tr>
                            <td colspan="10" align="center">
                                {{ $cheque_books->appends(request()->except('page'))->links() }}
                            </td>
                        </tr>
                    @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
