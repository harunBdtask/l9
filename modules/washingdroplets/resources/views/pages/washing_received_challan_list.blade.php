@extends('washingdroplets::layout')
@section('title', 'Washing Received Challan List')
@section('content')
  <div class="padding">
    <div class="box">
      <div class="box-header">
        <h2>Washing Challan List</h2>
      </div>
      <!--  <div class="box-divider m-a-0"></div> -->
      <div class="box-body">
        @include('partials.response-message')
        <div class="box-body b-t">
          <a class="btn btn-sm btn-info m-b" href="{{ url('received-from-wash') }}">
            <i class="glyphicon glyphicon-plus"></i> Wash Receive
          </a>
          <div class="pull-right">
            <form action="{{ url('/search-washing-received-challan') }}" method="GET">
              <div class="pull-left" style="margin-right: 10px;">
                <input type="text" class="form-control form-control-sm" name="q" placeholder="Enter challan no" value="{{ $q ?? '' }}">
              </div>
              <div class="pull-right">
                <input type="submit" class="btn btn-sm btn-info" value="Search">
              </div>
            </form>
          </div>
        </div>

        <table class="reportTable">
          <thead>
          <tr>
            <th>SL</th>
            <th>Challan No</th>
            <th>Color Name</th>
            <th>Received Qty</th>
            <th>Rejection Qty</th>
            <th>Received Date</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody>
          @if($washingChallans)
            @foreach($washingChallans->groupBy('challan_no') as $washing)
              @php
                $colors = [];
                $uniqueChallan = $washing->first();
                foreach($washing as $colorsData) {
                    if (isset($colorsData->color->name) && !in_array($colorsData->color->name, $colors)) {
                        $colors[] = $colorsData->color->name ?? '';
                    }
                }
              @endphp
              <tr style="height: 40px !important">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $uniqueChallan->challan_no }}</td>
                <td>{{ implode(',  ', $colors) }}</td>
                <td>{{ $washing->sum('received_qty') + $washing->sum('rejection_qty') }}</td>
                <td>{{ $washing->sum('rejection_qty') }}</td>
                <td>{{ $uniqueChallan->created_at->toDateString() }}</td>
                <td>
                  <a class="btn btn-sm white"
                     href="{{ url('manual-washing-received-challan-edit/'.$uniqueChallan->challan_no) }}"><i
                        class="fa fa-pencil"></i></a> |

                  <button type="button" class="btn btn-sm white show-modal" data-toggle="modal"
                          data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"
                          data-url="{{ url('manual-washing-received-challan-list/'.$uniqueChallan->challan_no) }}">
                    <i class="fa fa-times"></i>
                  </button>
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="7" align="center">No Data
              </td>
            </tr>
          @endif
          </tbody>
          <tfoot>
          @if($washingChallans->total() > 20)
            <tr>
              <td colspan="7" align="center">{{ $washingChallans->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
          </tfoot>
        </table>
      </div>
    </div>
  </div>
@endsection
