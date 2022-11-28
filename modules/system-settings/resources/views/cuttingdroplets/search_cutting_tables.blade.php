@extends('skeleton::layout')
@section('title', 'Cutting Table')

@section('content')
  <div class="padding">
    <div class="box" >
      <div class="box-header">
        <h2>Cutting Table List</h2>
      </div>
      <div class="box-body b-t">
        @if(Session::has('permission_of_tables_edit') || getRole() == 'super-admin')
          <a class="btn btn-sm white m-b" href="{{ url('cutting-tables/create') }}">
            <i class="glyphicon glyphicon-plus"></i> New Cutting Table
          </a>
        @endif
        <div class="pull-right">
          <form action="{{ url('/search-cutting-tables') }}" method="GET">
            <div class="pull-left" style="margin-right: 10px;">
              <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}">
            </div>
            <div class="pull-right">
              <input type="submit" class="btn btn-sm white" value="Search">
            </div>
          </form>
        </div>
        @include('partials.response-message')

        <table class="table table-striped">
          <thead>
          <tr>
            <th>SL</th>
            <th>Cutting Floor No.</th>
            <th>Cutting Table No</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody>
          @if(!$tables->getCollection()->isEmpty())
            @foreach($tables->getCollection() as $table)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $table->floor_no ?? '' }}</td>
                <td>{{ $table->table_no }}</td>
                <td>
                  @if(Session::has('permission_of_tables_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                    <a class="btn btn-sm white" href="{{ url('cutting-tables/'.$table->id.'/edit') }}"><i
                          class="fa fa-edit"></i></a>
                  @endif
                  @if(Session::has('permission_of_tables_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                    <button type="button" class="btn btn-sm white show-modal" data-toggle="modal"
                            data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"
                            data-url="{{ url('cutting-tables/'.$table->id) }}">
                      <i class="fa fa-times"></i>
                    </button>
                  @endif
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="5" align="center">No Cutting Tables
              <td>
            </tr>
          @endif
          </tbody>
          <tfoot>
          @if($tables->total() > 15)
            <tr>
              <td colspan="5" align="center">{{ $tables->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
          </tfoot>
        </table>
      </div>
    </div>
  </div>
@endsection
