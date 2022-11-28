@extends('skillmatrix::layout')

@section('title', 'Sewing Operator Details')
@section('styles')
  <style>
    .reportTable tr:first-child > th {
      background-color: transparent!important;
    }
    @media print {
      .app-header ~ .app-body {
        padding: 0px!important;
      }

      .noprint {
        display: none!important;
      }

    }
  </style>
@endsection
@section('content')
<div class="padding"> 
  <div class="box">
    <div class="box-header text-center noprint">
      <h2>Sewing Operator Details
        <span class="pull-right">
          <button type="button" class="btn btn-xs btn-primary noprint" onclick="window.print();"><i class="fa fa-print"></i></button>
        </span>
      </h2>
    </div>
    <div class="box-body table-responsive b-t">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <table class="reportTable operator-info-table" aria-describedby="operator-info-table">
            <thead>
            <tr>
              <th colspan="3" style="font-size:18px">Operator Information</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <th>Title</th>
              <th>{{ $sewingOperator->title }}</th>
              <th rowspan="7">

                <img
                        @if($sewingOperator->image)
                        src="{{ asset('/storage/sewing_operators/'.$sewingOperator->image) }}"
                        @else
                        src="{{ asset('/images/no_image.jpg') }}"
                        @endif style="width: 150px; height: auto;" alt="Image">
              </th>
            </tr>
            <tr>
              <th>Operator Name</th>
              <th>{{ $sewingOperator->name }}</th>
            </tr>
            <tr>
              <th>Operator Id</th>
              <th>{{ $sewingOperator->operator_id }}</th>
            </tr>
            <tr>
              <th>Operator Grade</th>
              <th>{{ $sewingOperator->operator_grade }}</th>
            </tr>
            <tr>
              <th>Present Salary</th>
              <th>{{ $sewingOperator->present_salary }}</th>
            </tr>
            <tr>
              <th>Joining Date</th>
              <th>{{ $sewingOperator->joinning_date }}</th>
            </tr>
            <tr>
              <th>Floor &amp; Line</th>
              <th>Floor : {{ $sewingOperator->floor->floor_no }} Line : {{ $sewingOperator->line->line_no ?? '' }}</th>
            </tr>
            </tbody>
          </table>
    
          <table class="reportTable skill-info-table" >
            <thead>
              <tr>
                <th colspan="5" style="font-size:18px">Skill Information</th>
              </tr>
              <tr>
                <th>SL</th>
                <th>Machine Name</th>
                <th>Process</th>
                <th>Capacity</th>
                <th>Efficiency</th>
              </tr>
            </thead>
            <tbody>
              @if($sewingOperator->sewingOperatorSkills)
                @foreach($sewingOperator->sewingOperatorSkills as $operator)
                  @php
                    $standard_capacity = $operator->process ? $operator->process->standard_capacity : 0;
                    $efficiency = 0;
                    if ($standard_capacity > 0) {
                      $operator_process_capacity = $operator->capacity ?? 0;
                      $efficiency = round((($operator_process_capacity * 100) / $standard_capacity), 1);
                    }
                  @endphp
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $operator->sewingMachine->name ?? '' }}</td>
                    <td>{{ $operator->process->name ?? '' }}</td>
                    <td>{{ $operator->capacity }}</td>
                    <td>{{ $efficiency }} &#37;</td>
                  </tr>
                @endforeach
              @else
                <tr style="height: 35px !important">
                  <td colspan="5" class="text-danger">No Sewing Operator Skill</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
      
    </div>
    <div class="box-footer">
      <p style="font-size: 8px; text-align: center">&copy; Copyright goRMG ERP - Product of Skylark Soft Limited</p>
    </div>
  </div>
</div>
@endsection