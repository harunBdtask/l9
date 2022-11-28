@extends('iedroplets::layout')
@section('title', 'Operation Bulletin List')

@section('content')
<div class="padding">
  <div class="box">
    <div class="box-header">
      <h2>Operation Bulletin List</h2>
    </div>
    <div class="box-body b-t m-b-1">
      @if(Session::has('permission_of_operation_bulletins_add') || getRole() == 'super-admin' || getRole() == 'admin')
        <a class="btn btn-sm btn-info m-b" href="{{ url('/operation-bulletins/create') }}"><i class="glyphicon glyphicon-plus"></i> New Operation Bulletin</a>
      @endif

      <div class="pull-right  m-b-1">
        <form action="{{ url('/search-operation-bulletins') }}" method="GET">
          <div class="pull-left" style="margin-right: 10px;">
            <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}">
          </div>
          <div class="pull-right">
            <input type="submit" class="btn btn-sm btn-info" value="Search">
          </div>
        </form>
      </div>

      @include('partials.response-message')
      <table class="reportTable">
        <thead>
          <tr>
            <th>Buyer</th>
            <th>Order</th>
            <th>Floor</th>
            <th>Line</th>
            <th>Input date</th>
            <th>Proposed date</th>
            <th>Porposed Tgt.</th>
            <th width="20%">Actions</th>
          </tr>
        </thead>
        <tbody>
          @if(!$operation_bulletins->getCollection()->isEmpty())
            @foreach($operation_bulletins->getCollection() as $bulletin)
              <tr>
                <td>{{ $bulletin->buyer->name ?? '' }}</td>
                <td>{{ $bulletin->order->style_name ?? '' }}</td>
                <td>{{ $bulletin->floor->floor_no ?? '' }}</td>
                <td>{{ $bulletin->line->line_no }}</td>
                <td>{{ $bulletin->input_date }}</td>
                <td>{{ $bulletin->prepared_date }}</td>
                <td>{{ $bulletin->proposed_target }}</td>
                <td>
                @if(getRole() == 'super-admin' || Session::has('permission_of_operation_bulletins_edit') || getRole() == 'admin')
                  <a class="btn btn-sm white" title="Edit" href="{{ url('operation-bulletins/'.$bulletin->id.'/edit') }}"><i class="fa fa-edit"></i></a>
                @endif

                @if(Session::has('permission_of_operation_bulletins_view') || getRole() == 'super-admin' || getRole() == 'admin')
                  | <a class="btn btn-sm white" title="View" href="{{ url('operation-bulletins-view?id='.$bulletin->id) }}"><i class="fa fa-eye"></i></a>
                @endif

                | <a class="btn btn-sm white" title="Copy" href="{{ url('operation-bulletins-copy/'.$bulletin->id) }}"><i class="fa fa-copy"></i></a>

                @if(getRole() == 'super-admin' || Session::has('permission_of_operation_bulletins_delete') || getRole() == 'admin')
                  <button type="button" title="Delete" class="btn btn-sm white show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('operation-bulletins/'.$bulletin->id) }}">
                    <i class="fa fa-times"></i>
                  </button>
                @endif
                </td>
              </tr>
            @endforeach
          @else
            <tr style="height: 40px">
              <td colspan="9" class="text-danger" align="center">No Operation Bulletins</td>
            </tr>
          @endif
        </tbody>
        <tfoot>
          @if($operation_bulletins->total() > 15)
            <tr>
              <td colspan="9" align="center">{{ $operation_bulletins->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
        </tfoot>
      </table>
    </div>
  </div>
</div>
@endsection
