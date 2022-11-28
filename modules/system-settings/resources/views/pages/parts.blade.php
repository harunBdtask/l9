@extends('skeleton::layout')
@section('title', 'Parts')
@section('content')
  <div class="padding">
    <div class="box" >
      <div class="box-header">
        <h2>Part List</h2>
      </div>
      <div class="box-body b-t">
        @if(Session::has('permission_of_parts_add') || getRole() == 'super-admin' || getRole() == 'admin')
          <a class="btn btn-sm white m-b" href="{{ url('parts/create') }}">
            <i class="glyphicon glyphicon-plus"></i> New Part
          </a>
        @endif
        <div class="pull-right">
          <form action="{{ url('/search-parts') }}" method="GET">
            <div class="pull-left" style="margin-right: 10px;">
              <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}">
            </div>
            <div class="pull-right">
              <input type="submit" class="btn btn-sm white" value="Search">
            </div>
          </form>
        </div>
        <hr>
        <div class="flash-message print-delete">
          @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
              <p class="text-center alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
            @endif
          @endforeach
        </div>
        <table class="reportTable">
          <thead>
          <tr>
            <th width="20%">SL</th>
            <th width="60%">Part Name</th>
            <th width="20%">Actions</th>
          </tr>
          </thead>
          <tbody>
          @if(!$parts->getCollection()->isEmpty())
            @foreach($parts->getCollection() as $part)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $part->name }}</td>
                <td>
                  @if(Session::has('permission_of_parts_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                    <a class="btn btn-sm white" href="{{ url('parts/'.$part->id.'/edit') }}"><i
                          class="fa fa-edit"></i></a>
                  @endif
                  @if(Session::has('permission_of_parts_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                    <button type="button" class="btn btn-sm white show-modal" data-toggle="modal"
                            data-target="#confirmationModal" ui-toggle-class="flip-x"
                            ui-target="#animate" data-url="{{ url('parts/'.$part->id) }}">
                      <i class="fa fa-times"></i>
                    </button>
                  @endif
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="3" align="center">No Parts
              <td>
            </tr>
          @endif
          </tbody>
          <tfoot>
          @if($parts->total() > 15)
            <tr>
              <td colspan="3" align="center">{{ $parts->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
          </tfoot>
        </table>
      </div>
    </div>
  </div>
@endsection
