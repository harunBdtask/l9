@extends('printembrdroplets::layout')
@section('title', 'Gatepass List')
@section('content')
  <div class="padding">
    <div class="box">
      <div class="box-header">
        <h2>Gatepass List</h2>
        @include('partials.response-message')
      </div>
      <div class="box-divider m-a-0"></div>
      <div class="box-body">
        <div class="pull-right" style="margin-bottom: 10px;">
          <form action="{{ url('/search-gatepass-list') }}" method="GET">
            <div class="pull-left" style="margin-right: 10px;">
              <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}">
            </div>
            <div class="pull-right">
              <input type="submit" class="btn btn-sm white" value="Search">
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
            <th>Bag(s)</th>
            <th>Date</th>
            <th>View Bundles</th>
            <th width="18%">Actions</th>
            @if(Session::has('permission_of_parts_add') || getRole() == 'super-admin' || getDept() == 'security')
              <th>Security Status</th>
            @endif
          </tr>
          </thead>
          <tbody>
          @if(!$gatepass_list->getCollection()->isEmpty())
            @foreach($gatepass_list->getCollection() as $gatepass)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $gatepass->challan_no }}</td>
                <td>{{ $gatepass->factory_name }}</td>
                <td>{{ $gatepass->factory_address }}</td>
                <td>{{ $gatepass->name }}</td>
                <td>{{ $gatepass->bag }}</td>
                <td>{{ date('Y-m-d', strtotime($gatepass->created_at)) }}</td>
                <td>
                  <a href="{{ url('/get-challan-wise-bundle-list/'.$gatepass->challan_no) }}"
                     class="btn btn-sm white"><i class="fa fa-eye"></i>
                  </a>
                </td>
                <td>
                  <a class="btn btn-sm white" href="{{ url('view-print-getapass/'.$gatepass->challan_no) }}"><i
                        class="fa fa-eye"></i></a> {{-- ||
                <a class="btn btn-sm white" href="{{ url('gatepasse/'.$gatepass->id.'/edit') }}"><i class="fa fa-pencil"></i></a> --}}
                  ||
                  <button type="button" class="btn btn-sm white show-modal" data-toggle="modal"
                          data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"
                          data-url="{{ url('gatepasses/'.$gatepass->id) }}">
                    <i class="fa fa-times"></i>
                  </button>
                  ||

                  <a href="{{ url('/get-security-status/'.$gatepass->id) }}"
                     class="btn btn-sm white security-status-btn"><i class="fa fa-edit"></i>
                  </a>
                </td>
                @if(Session::has('permission_of_parts_add') || getRole() == 'super-admin' || getDept() == 'security')
                  <td width="5%" @if($gatepass->security_staus == 'send') style="background-color: green"
                      @elseif($gatepass->security_staus == 'cancel') style="background-color: red" )
                      @elseif($gatepass->security_staus == 'hold') style="background-color: yellow" ) @endif>
                    <span>{{ $gatepass->security_staus }} </span>
                  </td>
                @endif
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="9" align="center">No Gatepasses
              <td>
            </tr>
          @endif
          </tbody>
          <tfoot>
          @if($gatepass_list->total() > 15)
            <tr>
              <td colspan="8" align="center">{{ $gatepass_list->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
          </tfoot>
        </table>
      </div>
    </div>
  </div>
@endsection
