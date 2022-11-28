@extends('skillmatrix::layout')

@section('title', 'Sewing Machine List')
@section('content')
<div class="padding">
  <div class="box">
    <div class="box-header">
      <h2>Sewing Machine List</h2>
    </div>
    <div class="box-body b-t">
      <div class="row">
        <div class="col-md-6">
          @if(Session::has('permission_of_sewing_machines_add') || getRole() == 'super-admin' || getRole() == 'admin')
          <a class="btn btn-sm btn-info m-b" href="{{ url('sewing-machines/create') }}">
            <i class="glyphicon glyphicon-plus"></i> New Sewing Machine
          </a>
          @endif
        </div>
        <div class="col-md-6">
          <div class="row">
            <div class="col-sm-6 col-sm-offset-6">
              <form action="{{ url('sewing-machines') }}" method="GET">
                <div class="input-group">
                  <input type="hidden" name="page" id="page" value={{$sewingMachines->currentPage()}}>
                  <input type="hidden" name="paginateNumber" id="paginateNumber" value={{$paginateNumber}}>

                  <input type="text" class="form-control form-control-sm" id="search" name="search"
                    value="{{ request('search') ?? '' }}" placeholder="Search">
                  <span class="input-group-btn">
                    <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                  </span>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      @include('partials.response-message')

      <table class="reportTable">
        <thead>
          <tr>
            <th>SL</th>
            <th>Sewing Machine Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @if(!$sewingMachines->getCollection()->isEmpty())
          @foreach($sewingMachines->getCollection() as $sewing_machine)
          <tr style="height: 30px !important">
            <td>{{ $loop->iteration }}</td>
            <td>{{ $sewing_machine->name }}</td>
            <td>
              @if(Session::has('permission_of_sewing_machines_edit') || getRole() == 'super-admin' || getRole() == 'admin')
              <a class="btn btn-xs btn-success" title="Edit"
                href="{{ url('sewing-machines/'.$sewing_machine->id.'/edit') }}">
                <i class="fa fa-edit"></i>
              </a>
              @endif
              @if(Session::has('permission_of_sewing_machines_delete') || getRole() == 'super-admin' || getRole() == 'admin')
              <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal"
                      data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"
                      data-url="{{ url('sewing-machines/'.$sewing_machine->id) }}">
                <i class="fa fa-times"></i>
              </button>
              @endif
            </td>
          </tr>
          @endforeach
          @else
          <tr style="height: 35px !important">
            <td colspan="3" class="text-danger">No Sewing Machine</td>
          </tr>
          @endif
        </tbody>
        <tfoot>
          @if($sewingMachines->total() > $paginateNumber)
          <tr>
            <td colspan="3">{{ $sewingMachines->appends(request()->except('page'))->links() }}</td>
          </tr>
          @endif
        </tfoot>
      </table>
    </div>
  </div>
</div>
@endsection