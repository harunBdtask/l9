@extends('washingdroplets::layout')
@section('title', 'Washing Challan List')
@section('content')
  <div class="padding">
    <div class="box">
      <div class="box-header">
        <h2>Washing Challan List</h2>
      </div>
      <div class="box-divider m-a-0"></div>
      <div class="box-body">
        @include('partials.response-message')
        <div class="pull-right" style="margin-bottom: 10px;">
          <form action="{{ url('/search-washing-challan') }}" method="GET">
            <div class="pull-left" style="margin-right: 10px;">
              <input type="text" class="form-control form-control-sm" name="q" placeholder="Enter challan no" value="{{ $q ?? '' }}">
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
            <th>Washing Challan No</th>
            <th>Factory Name</th>
            <th>Factory Address</th>
            <th>Responsible Person</th>
            <th>Bag(s)</th>
            <th>Created Date</th>
            <!-- <th>View Bundles</th> -->
            <th width="18%">Actions</th>
            {{--
            @if(Session::has('permission_of_parts_add') || getRole() == 'super-admin' || getDept() == 'security')
                <th>Security Status</th>
            @endif
            --}}
          </tr>
          </thead>
          <tbody>
          @if(!$washingChallans->getCollection()->isEmpty())
            @foreach($washingChallans->getCollection() as $washing)
              <tr style="height: 40px !important">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $washing->washing_challan_no }}</td>
                <td>{{ $washing->printWashFactory->factory_name ?? '' }}</td>
                <td>{{ $washing->printWashFactory->factory_address ?? '' }}</td>
                <td>{{ $washing->printWashFactory->responsible_person ?? '' }}</td>
                <td>{{ $washing->bag }}</td>
                <td>{{ date('Y-m-d', strtotime($washing->created_at)) }}</td>
                {{--
                <td>
                    <a href="{{ url('/get-challan-wise-bundle-list/'.$washing->washing_challan_no) }}" class="btn btn-sm white"><i class="fa fa-eye"></i>
                    </a>
                </td>
                --}}
                <td>
                  <a class="btn btn-sm white"
                     href="{{ url('/view-washing-challan?washing_challan_no='.$washing->washing_challan_no) }}"><i
                        class="fa fa-eye"></i></a>
                  {{-- ||
                  <a class="btn btn-sm white" href="{{ url('washinge/'.$washing->id.'/edit') }}"><i class="fa fa-pencil"></i></a>  |
                  <button type="button" class="btn btn-sm white show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('washinges/'.$washing->id) }}">
                      <i class="fa fa-times"></i>
                  </button>
                  | --}}
                  <a href="{{ url('/washing-challan/'.$washing->id.'/edit') }}"
                     class="btn btn-sm white security-status-btn"><i class="fa fa-edit"></i>
                  </a>
                </td>

                {{--
                    @if(Session::has('permission_of_parts_add')
                    || getRole() == 'super-admin'
                    || getDept() == 'security')
                        <td width="5%" @if($washing->security_staus == 'send') style="background-color: green" @elseif($washing->security_staus == 'cancel') style="background-color: red" )
                            @elseif($washing->security_staus == 'hold') style="background-color: yellow" ) @endif>
                            <span>{{ $washing->security_staus }} </span>
                        </td>
                    @endif
                --}}
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="8" align="center">No Data
              <td>
            </tr>
          @endif
          </tbody>
          <tfoot>
          @if($washingChallans->total() > 15)
            <tr>
              <td colspan="10" align="center">{{ $washingChallans->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
          </tfoot>
        </table>
      </div>
    </div>
  </div>
@endsection
