@extends('skillmatrix::layout')

@section('title', 'Sewing Process List')

@section('content')
<div class="padding"> 
  <div class="box">
    <div class="box-header">
      <h2>Sewing Process List</h2>
    </div>
    <div class="box-body b-t">
      <div class="row">
        <div class="col-md-6">
          @if(Session::has('permission_of_sewing_processes_add') || getRole() == 'super-admin' || getRole() == 'admin')      
            <a class="btn btn-sm btn-info m-b" href="{{ url('sewing-processes/create') }}">
                <i class="glyphicon glyphicon-plus"></i> New Process
            </a>
          @endif
        </div>
        <div class="col-md-6">
          <div class="row">
            <div class="col-sm-6 col-sm-offset-6">
              <form action="{{ url('sewing-processes') }}" method="GET">
                <div class="input-group">
                  <input type="hidden" name="page" id="page" value={{$processes->currentPage()}}>
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
            <th>Process Name</th>
            <th>Standard Capacity</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @if(!$processes->getCollection()->isEmpty())
            @foreach($processes->getCollection() as $process)
              <tr style="height: 30px !important">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $process->name }}</td>
                <td>{{ $process->standard_capacity }}</td>
                <td>
                @if(Session::has('permission_of_sewing_processes_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                  <a class="btn btn-xs btn-success" title="Edit" href="{{ url('sewing-processes/'.$process->id.'/edit') }}">
                    <i class="fa fa-edit"></i>
                  </a>
                @endif  
                @if(Session::has('permission_of_sewing_processes_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                  <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal"
                        data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"
                        data-url="{{ url('sewing-processes/'.$process->id) }}">
                    <i class="fa fa-times"></i>
                  </button>  
                @endif  
                </td>
              </tr>
            @endforeach
          @else
            <tr style="height: 35px !important">
              <td colspan="4" class="text-danger">No Process</td>
            </tr>
          @endif
        </tbody>
        <tfoot>
          @if($processes->total() > $paginateNumber)
            <tr>
              <td colspan="4">{{ $processes->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
        </tfoot>
      </table>
    </div>
  </div>
</div>
@endsection