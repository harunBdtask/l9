@extends('printembrdroplets::layout')
@section('title', 'Gatepass List')
@section('content')
  <div class="padding">
    <div class="box">
      <div class="box-header">
        <h2>Gatepass List</h2>
      </div>
      <div class="box-divider m-a-0"></div>
      <div class="box-body">
        @include('partials.response-message')
        <div class="pull-right" style="margin-bottom: 10px;">
          <form action="{{ url('/search-gatepass-list') }}" method="GET">
            <div class="pull-left" style="margin-right: 10px;">
              <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}">
            </div>
            <div class="pull-right">
              <input type="submit" class="btn btn-sm btn-info" value="Search">
            </div>
          </form>
        </div>
        <table class="reportTable">
          <thead>
          <tr>
            <th>SL</th>
            <th>Challan No</th>
            <th>Factory Name</th>
            <th>Factory Address</th>
            <th>Part</th>
            <th>Operation</th>
            <th>Bag(s)</th>
            <th>Date</th>
            <th>Appr.</th>
            <th>View Bundles</th>
            <th width="12%">Actions</th>
            @if(Session::has('permission_of_gatepass_list_edit')
            || getRole() == 'super-admin'
            || getRole() == 'admin'
            || getDept() == 'security'
            || getDept() == 'print-send')
            <th>Security Status</th>
            @endif
            {{-- @if(Session::has('permission_of_gatepasses_add') || getRole() == 'super-admin' || getDept() == 'security')
                <th>Security Status</th>
            @endif --}}
          </tr>
          </thead>
          <tbody>
          @if(!$gatepass_list->getCollection()->isEmpty())
            @foreach($gatepass_list->getCollection() as $gatepass)
              @php
                $challan_originial_time = $gatepass->updated_at;
                $new_challan_time = date('Y-m-d', strtotime($challan_originial_time)).' ';
                if (isset($gatepass)) {
                  if (date('H', strtotime($challan_originial_time)) < 8) {
                    $new_challan_time .= '08:'.date('i:s', strtotime($challan_originial_time));
                  } elseif (date('H', strtotime($challan_originial_time)) >= 19) {
                    $new_challan_time .= '18:'.date('i:s', strtotime($challan_originial_time));
                  } else {
                    $new_challan_time .= date('H:i:s', strtotime($challan_originial_time));
                  }
                }
              @endphp
              <tr class="tr-height">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $gatepass->challan_no }}</td>
                <td>{{ $gatepass->printFactory->factory_name }}</td>
                <td>{{ $gatepass->printFactory->factory_address }}</td>
                <td>{{ $gatepass->part->name }}</td>
                <td>{{ OPERATION[$gatepass->operation_name] }}</td>
                <td>{{ $gatepass->bag }}</td>
                <td>{{ $new_challan_time }}</td>
                <td>
                  @if($gatepass->cut_manager_approval_status == 1)
                      <i class="fa fa-check-circle-o label-success-md"></i>
                  @else
                      <button type="button" class="btn btn-xs btn-warning">
                          <i class="fa fa-circle-o-notch label-primary-md"></i>
                      </button>
                  @endif
                </td>
                <td>
                  <a href="{{ url('/get-challan-wise-bundle-list/'.$gatepass->challan_no) }}"
                     class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="View Bundles">
                    <i class="fa fa-eye"></i>
                  </a>
                  <a href="{{ url('/get-challan-wise-deleted-bundle-list/'.$gatepass->challan_no) }}"
                     class="btn btn-xs btn-warning" data-toggle="tooltip" data-placement="top" title="Deleted Bundles">
                    <i class="fa fa-eye-slash"></i>
                  </a>
                </td>
                <td>
                  <a class="btn btn-xs btn-success" href="{{ url('view-print-getapass/'.$gatepass->challan_no) }}"  data-toggle="tooltip" data-placement="top" title="View Challan">
                    <i class="fa fa-eye"></i>
                  </a>
                  @if(getRole() == 'super-admin' || getRole() == 'admin' || Session::has('permission_of_gatepass_list_edit'))
                    <a href="{{ url('/get-security-status/'.$gatepass->id) }}"
                      class="btn btn-xs btn-info security-status-btn" data-toggle="tooltip" data-placement="top" title="Edit Challan">
                      <i class="fa fa-edit"></i>
                    </a>
                  @endif
                  @if(getRole() == 'super-admin' || getRole() == 'admin' || Session::has('permission_of_gatepass_list_delete'))
                    <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal"
                            data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"
                            data-url="{{ url('gatepasses/'.$gatepass->id) }}">
                      <i class="fa fa-times"></i>
                    </button>
                  @endif
                </td>
                @if(Session::has('permission_of_gatepass_list_edit')
                || getRole() == 'super-admin'
                || getRole() == 'admin'
                || getDept() == 'security'
                || getDept() == 'print-send')
                  <td width="5%" @if($gatepass->security_status == 1) style="background-color: green"
                      @elseif($gatepass->security_status == 3) style="background-color: red" )
                      @elseif($gatepass->security_status == 2) style="background-color: yellow" ) @endif>
                    <span>{{ $gatepass->security_status ? SECURITY_STATUS[$gatepass->security_status]: $gatepass->security_staus }} </span>
                  </td>
                @endif
              </tr>
            @endforeach
          @else
            <tr class="tr-height">
              <td colspan="12" class="text-danger text-center">No Gatepasses</td>
            </tr>
          @endif
          </tbody>
          <tfoot>
          @if($gatepass_list->total() > 15)
            <tr>
              <td colspan="12" class=" text-center">{{ $gatepass_list->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
          </tfoot>
        </table>
      </div>
    </div>
  </div>
@endsection
