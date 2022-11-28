@extends('skeleton::layout')
@section('title', 'Machine Types')
@section('content')
  <div class="padding">
    <div class="box" >
      <div class="box-header">
        <h2>Machine Type List</h2>
      </div>
      <div class="box-body b-t">
        @if(Session::has('permission_of_machine_types_add') || getRole() == 'super-admin')
          <a class="btn btn-sm white m-b" href="{{ url('machine-types/create') }}">
            <i class="glyphicon glyphicon-plus"></i> New Machine Type
          </a>
        @endif
        <div class="pull-right">
          <form action="{{ url('search-machine-types') }}" method="GET">
            <div class="pull-left" style="margin-right: 10px;">
              <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}">
            </div>
            <div class="pull-right">
              <input type="submit" class="btn btn-sm white" value="Search">
            </div>
          </form>
        </div>
        <hr>
        @include('partials.response-message')
        <table class="reportTable">
          <thead>
          <tr>
            <th>SL</th>
            <th>Machine Type Name</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody>
          @if(!$machine_types->getCollection()->isEmpty())
            @foreach($machine_types->getCollection() as $machine_type)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $machine_type->name }}</td>
                <td>
                  @if(Session::has('permission_of_machine_types_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                    <a class="btn btn-sm white" href="{{ url('machine-types/'.$machine_type->id.'/edit') }}"><i
                          class="fa fa-edit"></i></a>
                  @endif
                  @if(Session::has('permission_of_machine_types_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                    <button type="button" class="btn btn-sm white show-modal" data-toggle="modal"
                            data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"
                            data-url="{{ url('machine-types/'.$machine_type->id) }}">
                      <i class="fa fa-times"></i>
                    </button>
                  @endif
                </td>
              </tr>
            @endforeach
          @else
            <tr style="height: 40px">
              <td colspan="3" class="text-danger" align="center">No Machine Types
              <td>
            </tr>
          @endif
          </tbody>
          <tfoot>
          @if($machine_types->total() > 15)
            <tr>
              <td colspan="4" align="center">{{ $machine_types->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
          </tfoot>
        </table>
      </div>
    </div>
  </div>
@endsection
