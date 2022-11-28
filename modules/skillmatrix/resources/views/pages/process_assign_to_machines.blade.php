@extends('skillmatrix::layout')

@section('title', 'Machine Wise Process List')

@section('content')
<div class="padding"> 
  <div class="box">
    <div class="box-header">
      <h2>Machine Wise Process List</h2>
    </div>
    <div class="box-body b-t">
      <div class="row">
        <div class="col-md-6">
          @if(Session::has('permission_of_process_assign_to_machine_add') || getRole() == 'super-admin' || getRole() == 'admin')
            <a class="btn btn-sm btn-info m-b" href="{{ url('process-assign-to-machines/create') }}">
                <i class="glyphicon glyphicon-plus"></i> New Process Assign
            </a>
          @endif
        </div>
        <div class="col-md-6">
          <div class="row">
            <div class="col-sm-6 col-sm-offset-6">
              <form action="{{ url('process-assign-to-machines') }}" method="GET">
                <div class="input-group">
                  <input type="hidden" name="page" id="page" value={{$processAssignToMachines->currentPage()}}>
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
            <th>Machine Name</th>
            <th>SL</th>
            <th>Process Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @if(!$processAssignToMachines->getCollection()->isEmpty())
            @foreach($processAssignToMachines->groupBy('sewing_machine_id') as $processMachines)              
              @foreach($processMachines as $processMachine)
                <tr style="height: 30px !important">                 
                  @if($loop->first)
                    <td rowspan="{{ count($processMachines) }}">{{ $processMachine->sewingMachine->name }}</td>
                  @endif
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $processMachine->sewingProcess->name }}</td>                
                  <td>                 
                  @if(Session::has('permission_of_process_assign_to_machine_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                    <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('process-assign-to-machines/'.$processMachine->id) }}"><i class="fa fa-times"></i></button>
                  @endif  
                  </td>
                </tr>
              @endforeach
            @endforeach
          @else
            <tr style="height: 35px !important">
              <td colspan="4" class="text-danger" align="center">Not found data</td>
            </tr>
          @endif
        </tbody>
        <tfoot>
          @if($processAssignToMachines->total() > 15)
            <tr>
              <td colspan="4" align="center">{{ $processAssignToMachines->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
        </tfoot>
      </table>
    </div>
  </div>
</div>
@endsection