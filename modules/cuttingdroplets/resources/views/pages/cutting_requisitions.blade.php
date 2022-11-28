@extends('cuttingdroplets::layout')
@section('title', 'Cutting Requisition')
@section('content')
<div class="padding">
  <div class="box">
    <div class="box-header">
      <h2>Cutting Requisition List</h2>
    </div>
    <div class="box-body b-t">
      @include('partials.response-message')
      @if(Session::has('permission_of_cutting_requisitions_add') || getRole() == 'super-admin' || getRole() == 'admin')
        <a class="btn btn-sm white m-b" href="{{ url('cutting-requisitions/create') }}">
          <i class="glyphicon glyphicon-plus"></i> New Cutting Requisition
        </a>
      @endif
      <div class="pull-right" style="margin-right: 13px;">
        <form action="{{ url('/search-cutting-requisitions') }}" method="GET">
          <div class="form-group">
              <div class="col-sm-4">
                <input type="text" class="form-control form-control-sm" name="cutting_requisition_no" placeholder="Requisition No" value="{{ $cutting_requisition_no ?? '' }}">
              </div>
              <div class="col-sm-3">
                <input type="date" class="form-control form-control-sm" name="from_date" placeholder="From date" value="{{ $from_date ?? '' }}">
              </div>
              <div class="col-sm-3">
                <input type="date" class="form-control form-control-sm" name="to_date" placeholder="To date" value="{{ $to_date ?? '' }}">
              </div>
              <div class="col-sm-2">
                <input type="submit" class="btn btn-sm white" value="Search">
              </div>
          </div>
        </form>
      </div>

      <table class="reportTable reportTableCustom">
        <thead>
          <tr>
            <th>SL</th>
            <th>Cutting Requisition No</th>
            <th>Style</th>
            <th>Created Date</th>
            <th>Approval Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @if(!$cutting_requisitions->getCollection()->isEmpty())
            @foreach($cutting_requisitions->getCollection() as $requisition)
             @php
             @endphp
              <tr style="height: 28px">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $requisition->cutting_requisition_no }}</td>
                <td>{{
                  implode(", ",\SkylarkSoft\GoRMG\Cuttingdroplets\Models\CuttingRequisitionDetail::getRequisitionNoWiseBookingNo($requisition->id))
                  }}
                </td>
                <td>{{ $requisition->created_at->toDateString() }}</td>
                <td>
                  <button class="btn btn-xs {{ ($requisition->approval_status == 0) ? 'btn-warning' : 'btn-primary' }}">{{ APPROVAL_STATUS[$requisition->approval_status] }}
                  </button>
                </td>
                <td>
                  <div class="dropdown inline">
                    <button class="btn btn-xs white dropdown-toggle" data-toggle="dropdown"
                            aria-expanded="false">Action
                    </button>
                    <div class="dropdown-menu pull-right">
                      <a class="dropdown-item"
                         href="{{ url('cutting-requisitions/'.$requisition->id)}}">Details
                      </a>
                      <div class="dropdown-divider"></div>
                      @if(Session::has('permission_of_cutting_requisitions_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                        <a class="dropdown-item"
                         href="{{ url('cutting-requisitions/'.$requisition->id.'/edit') }}">Edit</a>
                        <div class="dropdown-divider"></div>
                      @endif
                      @if(getRole() == 'super-admin' || getRole() == 'admin')
                        @if($requisition->approval_status == 0)
                          <a class="dropdown-item"
                           href="{{ url('cutting-requisition-approved/'.$requisition->id) }}" onclick="return confirm('Are you sure to approve this?');">Approved</a>
                          <div class="dropdown-divider"></div>
                        @endif
                        <a class="dropdown-item"
                         href="{{ url('cutting-requisitions-delete/'.$requisition->id) }}" onclick="return confirm('Are you sure to delete this?');">Delete</a>
                      <div class="dropdown-divider"></div>
                      @endif
                    </div>
                  </div>
                </td>
              </tr>
            @endforeach
          @else
            <tr style="height: 40px;">
              <td colspan="6" align="center" class="text-danger">No Cutting Requisitions</td>
            </tr>
          @endif
        </tbody>
        <tfoot>
          @if($cutting_requisitions->total() > 15)
            <tr>
              <td colspan="6" align="center">{{ $cutting_requisitions->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
        </tfoot>
      </table>
    </div>
  </div>
</div>
<style type="text/css">
  @media screen and (-webkit-min-device-pixel-ratio: 0) {
      input[type=date].form-control form-control-sm {
          line-height: .75;
      }
  }
</style>
@endsection
