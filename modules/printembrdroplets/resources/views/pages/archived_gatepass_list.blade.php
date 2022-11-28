@extends('printembrdroplets::layout')
@section('title', 'Gatepass List (Archived)')
@section('content')
  <div class="padding">
    <div class="box">
      <div class="box-header">
        <h2>Gatepass List (Archived)</h2>
      </div>
      <div class="box-divider m-a-0"></div>
      <div class="box-body">
        @include('partials.response-message')
        <div class="pull-right" style="margin-bottom: 10px;">
          <form action="{{ url('/search-archived-gatepass-list') }}" method="GET">
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
            <th>Operation</th>
            <th>Bag(s)</th>
            <th>Date</th>
            <th>View Bundles</th>
            <th width="12%">Actions</th>
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
                  <a href="{{ url('/get-archived-challan-wise-bundle-list/'.$gatepass->challan_no) }}"
                     class="btn btn-xs btn-success">
                    <i class="fa fa-eye"></i>
                  </a>
                </td>
                <td>
                  <a class="btn btn-xs btn-success" href="{{ url('view-archived-print-getapass/'.$gatepass->challan_no) }}">
                    <i class="fa fa-eye"></i>
                  </a>
                </td>
              </tr>
            @endforeach
          @else
            <tr class="tr-height">
              <td colspan="10" align="center" class="text-danger">No Gatepasses</td>
            </tr>
          @endif
          </tbody>
          <tfoot>
          @if($gatepass_list->total() > 15)
            <tr>
              <td colspan="10" align="center">{{ $gatepass_list->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
          </tfoot>
        </table>
      </div>
    </div>
  </div>
@endsection
