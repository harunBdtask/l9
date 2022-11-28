@extends('cuttingdroplets::layout')
@section('title', 'Bundle Card Manual')
@section('content')
<div class="padding">
  <div class="box">
    <div class="box-header">
      <h2>Bundle Card List</h2>
    </div>
    <div class="box-body b-t">
      <a class="btn btn-sm white m-b" href="{{ url('bundle-card-generation-manual/create') }}">
        <i class="glyphicon glyphicon-plus"></i> Generate Bundle Card [Manual]
      </a>
      <div class="pull-right m-b-1">
        <form action="{{ url('/search-manual-bundle-card-generations') }}" method="GET">
          <div class="pull-left" style="margin-right: 10px;">
            <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}" placeholder="SID, Buyer, Booking">
          </div>
          <div class="pull-right">
            <input type="submit" class="btn btn-sm white" value="Search">
          </div>
        </form>
      </div>

      @if(Session::has('success'))
        <div class="col-md-6 col-md-offset-3 alert alert-success alert-dismissible text-center">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <small>{{ Session::get('success') }}</small>
        </div>
      @elseif(Session::has('failure'))
        <div class="col-md-6 col-md-offset-3 alert alert-danger alert-dismissible text-center">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <small>{{ Session::get('failure') }}</small>
        </div>
      @endif

      <table class="reportTable">
        <thead>
          <tr>
            <th>SID</th>
            <th>Buyer</th>
            <th>{{ localizedFor('Style') }}</th>
            <th>{{ localizedFor('PO') }}</th>
            <th>Part</th>
            <th>No of Bundles</th>
            <th>Total Qty</th>
            <th>Date</th>
            <th>Created By</th>
            <th width="15%">Actions</th>
          </tr>
        </thead>
        <tbody>
          @if(!$bundleCardDetails->getCollection()->isEmpty())
          @foreach($bundleCardDetails->getCollection()->groupBy('sid') as $bundleCardGroup)
            @php
              $index = $loop->index;
              $first = 0;
              $bundleById = $bundleCardGroup->groupBy('id');
              $trBgColor = ($index % 2) == 0 ? 'background: rgba(0, 0, 0, 0.025);' : 'background: white;';
            @endphp
            <tr class="tr-height" style="{{ $trBgColor }}">
              <td rowspan="{{ $bundleById->count() }}">{{ $bundleCardGroup->first()->sid }}</td>
            @foreach($bundleById as $bundleCardGroupById)
                @if(!$loop->first)
                  <tr class="tr-height" style="{{ $trBgColor }}">
                @endif
                <td>{{ $bundleCardGroupById->first()->buyer_name ?? '' }}</td>
                <td title="{{ $bundleCardGroupById->first()->style_name ?? '' }}">{{ $bundleCardGroupById->first()->style_name ?? '' }}</td>
                <td>{{ $bundleCardGroupById->implode('po_no', ', ') }}</td>
                <td>{{ $bundleCardGroupById->first()->part_name ?? '' }}</td>
                @if($first == 0)
                  <td rowspan="{{ $bundleById->count() }}">{{ $bundleCardGroup->first()->bundles_count ?? '' }}</td>
                  <td rowspan="{{ $bundleById->count() }}">{{ $bundleCardGroup->first()->bundles_quantity ?? '' }}</td>
                  <td rowspan="{{ $bundleById->count() }}">{{ $bundleCardGroupById->first()->created_at ? date('M d, Y', strtotime($bundleCardGroupById->first()->created_at)) : '' }}</td>
                @endif
                <td>{{ $bundleCardGroupById->first()->first_name ?? '' }}&nbsp;{{ $bundleCardGroupById->first()->last_name ?? '' }}</td>
                <td>
                  <a class="btn btn-xs btn-info" href="{{ url('bundle-card-generation-manual/'.$bundleCardGroupById->first()->id) }}"><i class="fa fa-eye"></i></a>
                  @if(Session::has('permission_of_bundle_card[manual]_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                    <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('bundle-card-generation-manual/'.$bundleCardGroupById->first()->id) }}">
                      <i class="fa fa-times"></i>
                    </button>
                  @endif
                  <a class="btn btn-xs btn-success" href="{{ url('bundle-card-generation-manual/'.$bundleCardGroupById->first()->id.'/re-generate') }}"><i class="fa fa-retweet"></i></a>
                  {{-- @if(Session::has('permission_of_bundle_card[manual]_update') || getRole() == 'super-admin' || getRole() == 'admin')
                    <a class="btn btn-xs btn-warning" href="{{ url('bundle-card-generation-manual/'.$bundleCardGroupById->first()->id.'/update-view-cache') }}" title="Update View"  data-toggle="tooltip" data-placement="top"><i class="fa fa-arrow-up"></i></a>
                  @endif --}}
                </td>
              </tr>
              @php
                ++$first;
              @endphp
            @endforeach
          @endforeach
        @else
            <tr class="tr-height">
              <td colspan="10" class="text-center text-danger">No bundle card</td>
            </tr>
          @endif
        </tbody>
        <tfoot>
          @if($bundleCardDetails->total() > 15)
            <tr>
              <td colspan="10" class="text-center">{{ $bundleCardDetails->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
        </tfoot>
      </table>
    </div>
  </div>
</div>
@endsection
