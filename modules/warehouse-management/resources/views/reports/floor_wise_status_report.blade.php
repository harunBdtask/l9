@php
    $tableHeadColorClass = 'tableHeadColor';
    if (isset($type) || request()->has('type') || request()->route('type')) {
      $tableHeadColorClass = '';
    }
@endphp
@extends('warehouse-management::layout')
@section('title', 'Floor Wise Status Report')

@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Floor Wise Status Report
                            <span class="pull-right">
                                <a href="{{ $warehouse_floor_id ? url('/warehouse-floor-wise-status-report-download/pdf/'.$warehouse_floor_id) : '#' }}">
                                    <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | 
                                <a href="{{ $warehouse_floor_id ? url('/warehouse-floor-wise-status-report-download/excel/'.$warehouse_floor_id) : '#' }}"><i
                                            style="color: #0F733B" class="fa fa-file-excel-o"></i></a>
                            </span>
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        {!! Form::open(['url' => '/warehouse-floor-wise-status-report', 'action' => 'POST']) !!}
                        <div class="form-group">
                            <div class="row m-b">
                                <div class="col-sm-3">
                                    <label>Warehouse Floor</label>
                                    {!! Form::select('warehouse_floor_id', $warehouse_floors, $warehouse_floor_id, ['class' => 'form-control select2-input', 'placeholder' => 'Select Floor', 'onchange' => 'this.form.submit();']) !!}
                                    @if($errors->has('from_date'))
                                        <span class="text-danger">{{ $errors->first('from_date') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div class="table-responsive">
                            <table class="reportTable {{ $tableHeadColorClass }}">
                                @include('warehouse-management::reports.includes.floor_wise_status_report_table')
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
