@extends('skeleton::layout')
@section('title', 'Bundle Card')
@section('content')
<div class="padding">
  <div class="box" >
    <div class="box-header">
      <h2>Bundle Card List</h2>
    </div>
    <div class="box-body b-t">
      <a class="btn btn-sm white m-b" href="{{ url('bundle-card-generations/create') }}">
        <i class="glyphicon glyphicon-plus"></i> Generate Bundle Card [Auto]
      </a>
      <div class="pull-right">
        <form action="{{ url('/search-bundle-card-generations') }}" method="GET">
          <div class="pull-left" style="margin-right: 10px;">
            <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}">
          </div>
          <div class="pull-right">
            <input type="submit" class="btn btn-sm white" value="Search">
          </div>
        </form>
      </div>
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

    <table class="table table-striped">
      <thead>
        <tr>
          <th>SID</th>
          <th>Buyer</th>
          <th>Style</th>
          <th>Order No</th>
          <th>Part</th>
          <th>Max Bundle Qty</th>
          <th>No of Bundles</th>
          <th>Total Qty</th>
          <th>Date</th>
          <th width="15%">Actions</th>
        </tr>
      </thead>
      <tbody>
        @if(!$bundleCardDetails->getCollection()->isEmpty())
          @foreach($bundleCardDetails->getCollection()->groupBy('sid') as $bundleCardGroup)
            @php
              $index = $loop->index;
            @endphp
            @foreach($bundleCardGroup as $bundleCard)
              <tr style="{{ ($index % 2) == 0 ? 'background: rgba(0, 0, 0, 0.025)' : 'background: white;' }}">
                @if($loop->first)
                  <td rowspan="{{ $bundleCardGroup->count() }}">{{ $bundleCard->sid }}</td>
                @endif
                <td>{{ $bundleCard->buyer->name ?? '' }}</td>
                <td>{{ $bundleCard->order->style->name ?? '' }}</td>
                <td>{{ $bundleCard->order->order_no ?? '' }}</td>
                <td>{{ $bundleCard->part->name ?? '' }}</td>
                <td>{{ $bundleCard->max_quantity ?? '' }}</td>
                <td>{{ $bundleCard->bundleCards->count() ?? '' }}</td>
                <td>{{ $bundleCard->bundleCards->sum('quantity') ?? '' }}</td>
                @if($loop->first)
                  <td rowspan="{{ $bundleCardGroup->count() }}">{{ $bundleCard->created_at->toFormattedDateString() ?? '' }}</td>
                @endif
                <td>
                  <a class="btn btn-sm white" href="{{ url('bundle-card-generations/'.$bundleCard->id) }}"><i class="fa fa-eye"></i></a>
                  <button type="button" class="btn btn-sm white show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('bundle-card-generations/'.$bundleCard->id) }}">
                    <i class="fa fa-times"></i>
                  </button>
                  <a class="btn btn-sm white" href="{{ url('bundle-card-generations/'.$bundleCard->id.'/re-generate') }}"><i class="fa fa-retweet"></i></a>
                </td>
              </tr>
            @endforeach
          @endforeach
        @else
          <tr>
            <td colspan="10" align="center">No bundle card<td>
          </tr>
        @endif
      </tbody>
      <tfoot>
        @if($bundleCardDetails->total() > 15)
          <tr>
            <td colspan="10" align="center">{{ $bundleCardDetails->appends(request()->except('page'))->links() }}</td>
          </tr>
        @endif
      </tfoot>
    </table>
  </div>
</div>
@endsection
