@extends('tqm::layout')
@section('title', 'Defects')
@section('content')
  <div class="padding">
    <div class="box" >
      <div class="box-header">
        <h2>Defects List</h2>
      </div>
      <div class="box-body b-t">
        @if(Session::has('permission_of_defects_add') || getRole() == 'super-admin' || getRole() == 'admin')
          <a class="btn btn-sm btn-info m-b" href="{{ url('tqm-defects/create') }}">
            <i class="glyphicon glyphicon-plus"></i> New Defect
          </a>
        @endif
        <div class="pull-right">
          <form action="{{ url('/tqm-defects') }}" method="GET">
            <div class="pull-left" style="margin-right: 10px;">
              <input type="text" class="form-control form-control-sm" name="search" value="{{ request()->get('search') ?? '' }}">
            </div>
            <div class="pull-right">
              <input type="submit" class="btn btn-sm btn-info" value="Search">
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
        <table class="reportTable" aria-describedby="Defects List">
          <thead>
          <tr>
            <th>SL</th>
            <th>Defect Name</th>
            <th>Section</th>
            <th>Factory</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody>
          @if(!$defects->getCollection()->isEmpty())
            @foreach($defects->getCollection() as $defect)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $defect->name }}</td>
                <td>{{ $defect->section_name }}</td>
                <td>{{ $defect->factory->factory_name }}</td>
                <td>
                  @if(Session::has('permission_of_defects_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                    <a class="btn btn-sm white" href="{{ url('tqm-defects/'.$defect->id.'/edit') }}"><i
                          class="fa fa-edit"></i></a>
                  @endif
                  @if(Session::has('permission_of_defects_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                    <button type="button" class="btn btn-sm white show-modal" data-toggle="modal"
                            data-target="#confirmationModal" ui-toggle-class="flip-x"
                            ui-target="#animate" data-url="{{ url('tqm-defects/'.$defect->id) }}">
                      <i class="fa fa-times"></i>
                    </button>
                  @endif
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="5">No Data Found</td>
            </tr>
          @endif
          </tbody>
          <tfoot>
          @if($defects->total() > 15)
            <tr>
              <td colspan="5">{{ $defects->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
          </tfoot>
        </table>
      </div>
    </div>
  </div>
@endsection