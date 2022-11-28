@extends('skeleton::layout')
@section('title', 'Cutting Floor')
@section('content')
  <div class="padding">
    <div class="box" >
      <div class="box-header">
        <h2>Cutting Floor List</h2>
      </div>
      <div class="box-body b-t">
        @if(Session::has('permission_of_cutting_floor_add') || getRole() == 'super-admin' || getRole() == 'admin')
          <a class="btn btn-sm white m-b" href="{{ url('cutting-floors/create') }}">
            <i class="glyphicon glyphicon-plus"></i> New Cutting Floor
          </a>
        @endif
        <hr>
        @include('partials.response-message')
        <table class="reportTable">
          <thead>
          <tr>
            <th>SL</th>
            <th>Cutting Floor No.</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody>
          @if(!$cutting_floors->getCollection()->isEmpty())
            @foreach($cutting_floors->getCollection() as $floor)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $floor->floor_no }}</td>
                <td>
                  @if(Session::has('permission_of_cutting_floor_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                    <a class="btn btn-sm white" href="{{ url('cutting-floors/'.$floor->id.'/edit') }}"><i
                          class="fa fa-edit"></i></a>
                  @endif
                  @if(Session::has('permission_of_cutting_floor_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                    <a class="btn btn-sm white show-modal" data-toggle="modal" data-target="#confirmationModal"
                       ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('cutting-floors/'.$floor->id) }}">
                      <i class="fa fa-times"></i>
                    </a>
                  @endif
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="3" align="center">No Cutting Floors</td>
            </tr>
          @endif
          </tbody>
          <tfoot>
          @if($cutting_floors->total() > 15)
            <tr>
              <td colspan="3" align="center">{{ $cutting_floors->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
          </tfoot>
        </table>
      </div>
    </div>
  </div>
@endsection
