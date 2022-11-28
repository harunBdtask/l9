@extends('basic-finance::layout')
@section('title', 'Payment Realization List')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Payment Realization List</h2>
            </div>
            <div class="box-body">
                <div class="col-md-6">
                    @if(Session::has('permission_of_payment_realization_entry_add') || getRole() == 'admin' || getRole() == 'super-admin')
                        <a class="btn btn-sm white m-b b-t m-b-1" href="{{ url('basic-finance/accounting-realization/create') }}">
                            <i class="glyphicon glyphicon-plus"></i> New Entry
                        </a>
                    @endif
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-3">
                    {!! Form::open(['url' => 'basic-finance/accounting-realization', 'method' => 'GET']) !!}
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" name="q"
                               value="{{ request('q') ?? '' }}" placeholder="Search FDBC/LDBC No">
                        <span class="input-group-btn">
                            <button class="btn btn-sm white m-b" type="submit">Search</button>
                        </span>
                    </div>
                    {!! Form::close() !!}
                </div>
                @include('partials.response-message')

                <table class="reportTable">
                    <thead>
                    <tr>
                      <th>Sl.</th>
                      <th>FDBC/ LDBC No.</th>
                      <th>Type</th>
                      <th>Type Source</th>
                      <th>Company</th>
                      <th>Project</th>
                      <th>Unit</th>
                      <th>Date</th>
                      <th>Total Value</th>
                      <th>Currency</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($lists) && $lists->count())
                      @foreach($lists as $item)
                        <tr class="tr-height">
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $item->realization_number }}</td>
                          <td>{{ $item->realization_type_name }}</td>
                          <td>{{ $item->realization_type_source_name }}</td>
                          <td>{{ $item->factory->factory_name }}</td>
                          <td>{{ $item->bfProject->project }}</td>
                          <td>{{ $item->bfUnit->unit }}</td>
                          <td>{{ $item->realization_date_formatted }}</td>
                          <td>{{ $item->total_value_fc_amount }}</td>
                          <td>{{ $item->currency->currency_name }}</td>
                          <td>
                              @if((Session::has('permission_of_payment_realization_entry_delete') || getRole() == 'super-admin' || getRole() == 'admin') && $item->approve_status == 0)
                                  <a class="btn btn-xs btn-info"
                                     href="{{ url('/basic-finance/accounting-realization/vouchers/create/' . $item->id) }}">
                                      <i class="fa fa-fw fa-check"></i></a>
                              @endif
                              @if((Session::has('permission_of_payment_realization_entry_edit') || getRole() == 'super-admin' || getRole() == 'admin') && $item->approve_status == 0)
                                  <a class="btn btn-xs btn-success"
                                      href="{{ url('/basic-finance/accounting-realization/edit?id='.$item->id) }}">
                                      <i class="fa fa-fw fa-edit"></i></a>
                              @endif
                              @if((Session::has('permission_of_payment_realization_entry_delete') || getRole() == 'super-admin' || getRole() == 'admin') && $item->approve_status == 0)
                                <button type="button" class="btn btn-xs btn-danger show-modal"
                                        data-toggle="modal" data-target="#confirmationModal"
                                        ui-toggle-class="flip-x" ui-target="#animate"
                                        data-url="{{ url('/basic-finance/accounting-realization/'.$item->id) }}">
                                    <i class="fa fa-times"></i>
                                </button>
                              @endif
                          </td>
                        </tr>
                      @endforeach
                    @else
                      <tr class="tr-height">
                        <td colspan="11" class="text-center text-danger">No Data Found</td>
                      </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    @if($lists->total() > 15)
                      <tr>
                        <td colspan="11" class="text-center">{{ $lists->appends(request()->except('page'))->links() }}</td>
                      </tr>
                    @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
