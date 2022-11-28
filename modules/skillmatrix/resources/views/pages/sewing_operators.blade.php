@extends('skillmatrix::layout')

@section('title', 'Sewing Operator List')
@section('styles')
   <style type="text/css">
    .tooltipp .tooltiptext {
      width: 120px;
      bottom: 100%;
      left: 50%; 
      margin-left: -60px; /* Use half of the width (120/2 = 60), to center the tooltip */
    }
  </style>
@endsection
@section('content')
<div class="padding"> 
  <div class="box">
    <div class="box-header">
      <h2>Sewing Operator List</h2>
    </div>
    <div class="box-body b-t">
      <div class="js-response-message text-center"></div>
      <div class="row">
        <div class="col-md-6">
          @if(Session::has('permission_of_sewing_operators_add') || getRole() == 'super-admin' || getRole() == 'admin')      
            <a class="btn btn-sm btn-info m-b" href="{{ url('sewing-operators/create') }}">
                <i class="glyphicon glyphicon-plus"></i> New Sewing Operator
            </a>
          @endif
        </div>
        <div class="col-md-6">
          <div class="row">
            <div class="col-sm-6 col-sm-offset-6">
              <form action="{{ url('sewing-operators') }}" method="GET">
                <div class="input-group">
                  <input type="hidden" name="page" id="page" value={{$sewingOperators->currentPage()}}>
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
            <th>Name</th>
            <th>Title</th>
            <th>Operator Id</th>
            <th>Grade</th>
            <th>Floor</th>
            <th>Line</th>
            <th>Present Salary</th>
            <th>Joinning Date</th>
            <th width="27%">Actions</th>
          </tr>
        </thead>
        <tbody>
          @if(!$sewingOperators->getCollection()->isEmpty())
            @foreach($sewingOperators->getCollection() as $operator)
              <tr style="height: 30px">
                <td>{{ $loop->iteration }}</td>
                <td><a style="color: green" href="{{ url('/sewing-operators/'.$operator->id) }}">{{ $operator->name }}</a></td>
                <td>{{ $operator->title }}</td>
                <td><a style="color: green" href="{{ url('/sewing-operators/'.$operator->id) }}">{{ $operator->operator_id }}</a></td>
                <td>{{ $operator->operator_grade }}</td>
                <td>{{ $operator->floor->floor_no }}</td>
                <td>{{ $operator->line->line_no }}</td>
                <td>{{ $operator->present_salary }}</td>
                <td>{{ date('jS F, Y', strtotime($operator->joinning_date)) }}</td>
                <td>
                  @php
                    $skills = [];
                    foreach ($operator->sewingOperatorSkills as $skillData) {
                      if (!in_array($skillData->sewingProcess->name, $skills)) {
                        $skills[] = $skillData->sewingProcess->name ?? '';
                      }                      
                    }
                  @endphp                  
                 
                  <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="{{ (count($skills) > 0) ? implode(', ', $skills) : 'No skill' }} " style=" cursor: pointer;">
                    <button class="btn btn-xs btn-info" style="pointer-events: none;" type="button">Skills</button>
                  </span>
               
                  @if(Session::has('permission_of_sewing_operators_add') || getRole() == 'super-admin' || getRole() == 'admin')
                    &nbsp;
                    <a class="btn btn-xs btn-success" title="Add Skill" href="{{ url('sewing-operators/add-skills/'.$operator->id) }}">Add Skill</a>
                    &nbsp;
                  @endif
                
                  @if(Session::has('permission_of_sewing_operators_view') || getRole() == 'super-admin' || getRole() == 'admin')
                    <a class="btn btn-xs btn-info" title="View" href="{{ url('sewing-operators/'.$operator->id) }}">View</a>
                    &nbsp;
                  @endif                  
                  @if(Session::has('permission_of_sewing_operators_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                    <a class="btn btn-xs btn-success" title="Edit" href="{{ url('sewing-operators/'.$operator->id.'/edit') }}">Edit</a>
                    &nbsp;
                  @endif
                
                  @if(Session::has('permission_of_sewing_operators_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                    <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal"
                        data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"
                        data-url="{{ url('sewing-operators/'.$operator->id) }}">
                      <i class="fa fa-times"></i>
                    </button>  
                  @endif
                </td>
              </tr>
            @endforeach
          @else
            <tr style="height: 35px !important">
              <td colspan="10" class="text-danger">No Sewing Operator</td>
            </tr>
          @endif
        </tbody>
        <tfoot>
          @if($sewingOperators->total() > $paginateNumber)
            <tr>
              <td colspan="10">{{ $sewingOperators->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
        </tfoot>
      </table>
    </div>
  </div>
</div>
@endsection
@section('scripts')

  <script type="text/javascript">
    $(function () {
      $('[data-toggle="tooltip"]').tooltip();
      $('[data-toggle="tooltip"]').on('shown.bs.tooltip', function () {
        $('.tooltip').addClass('animated fadeIn');
      })
    });
  </script>
@endsection