@extends('skeleton::layout')
@section('title', 'Sewing Floor')
@section('content')
  <div class="padding">
    <div class="box" >
      <div class="box-header">
        <h2>Sewing Floor List</h2>
      </div>

      <div class="box-body b-t">
        @if(Session::has('permission_of_sewing_floor_add') || getRole() == 'super-admin' || getRole() == 'admin')
          <a class="btn btn-sm white m-b" href="{{ url('floors/create') }}">
            <i class="glyphicon glyphicon-plus"></i> New Sewing Floor
          </a>
        @endif
        <div class="pull-right">
          <form action="{{ url('/floors') }}" method="GET">
            <div class="pull-left m-b-1" style="margin-right: 10px;">
              <input type="text" class="form-control form-control-sm" name="q" value="{{ request('q') ?? '' }}">
            </div>
            <div class="pull-right">
              <input type="submit" class="btn btn-sm white" value="Search">
            </div>
          </form>
        </div>
        @include('partials.response-message')
        <table class="reportTable">
          <thead>
          <tr>
            <th>SL</th>
            <th>Sewing Floor No.</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody>
          @if(!$floors->getCollection()->isEmpty())
            @foreach($floors->getCollection() as $floor)
              <tr class="tr-height">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $floor->floor_no }}</td>
                <td>
                  @if(Session::has('permission_of_sewing_floor_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                    <a class="btn btn-xs btn-success"
                       href="{{ url('floors/'.$floor->id.'/edit') }}"><i class="fa fa-edit"></i></a>
                  @endif
                  @if(Session::has('permission_of_sewing_floor_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                    <a class="btn btn-xs btn-danger show-modal" data-toggle="modal"
                       data-target="#confirmationModal" ui-toggle-class="flip-x"
                       ui-target="#animate" data-url="{{ url('floors/'.$floor->id) }}">
                      <i class="fa fa-times"></i>
                    </a>
                  @endif
                </td>
              </tr>
            @endforeach
          @else
            <tr class="tr-height">
              <td colspan="3" align="center" class="text-danger">No Floors</td>
            </tr>
          @endif
          </tbody>
          <tfoot>
          @if($floors->total() > 15)
            <tr>
              <td colspan="3"
                  align="center">{{ $floors->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
          </tfoot>
        </table>
      </div>
    </div>
  </div>
@endsection
