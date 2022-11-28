@extends('skeleton::layout')
@section('title', 'Sub Module')
@section('content')
<div class="padding">
  <div class="box" >
    <div class="box-header">
      <h2>Sub Module List</h2>
    </div>
    @if(Session::has('permission_of_buyers_add') || getRole() == 'super-admin')
      <div class="box-body b-t">
        <a class="btn btn-sm white m-b" href="{{ url('submodules/create') }}">
          <i class="glyphicon glyphicon-plus"></i> New Sub Module
        </a>
      </div>
    @endif
    <table class="reportTable">
      <thead>
        <tr>
          <th>SL</th>
          <th>Sub Module Name</th>
          <th>Module Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @if(!$submodules->getCollection()->isEmpty())
          @foreach($submodules->getCollection() as $submodule)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $submodule->submodule_name }}</td>
              <td>{{ $submodule->module->module_name }}</td>
              <td>
                @if(Session::has('permission_of_buyers_edit') || getRole() == 'super-admin')
                    <a class="btn btn-sm white" href="{{ url('submodules/'.$submodule->id.'/edit') }}"><i class="fa fa-edit"></i></a>
                @endif
                @if(Session::has('permission_of_buyers_delete') || getRole() == 'super-admin')
                  <button type="button" class="btn btn-sm white show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('submodules/'.$submodule->id) }}">
                    <i class="fa fa-times"></i>
                  </button>
                @endif
              </td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="4" align="center">No Sub modules<td>
          </tr>
        @endif
      </tbody>
      <tfoot>
        @if($submodules->total() > 15)
          <tr>
            <td colspan="4" align="center">{{ $buyers->appends(request()->except('page'))->links() }}</td>
          </tr>
        @endif
      </tfoot>
    </table>
  </div>
</div>
@endsection
