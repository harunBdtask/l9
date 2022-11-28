@extends('inputdroplets::layout')
@section('title', 'Sewing Challan Tag')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Sewing Challan Tag
              || {{ date("jS F, Y", strtotime($tagChallan->updated_at)) }}</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">

            <div class="factory-area text-center">
              @if(isset($factory))
                <strong>{{ $factory->group_name}}</strong><br>
                {{ $factory->factory_address }}<br>
                <span>Unit: {{ $factory->factory_name }}</span><br>
                <strong>Sewing Challan Tag</strong><br>
              @endif
            </div>
            <br/><br/>

            <span><b>Tag No. : {{ $challan_no }}</b></span>
            <table class="reportTable">
              <thead>
              <tr>
                <th>SL</th>
                <th>Buyer</th>
                <th>Style</th>
                <th>PO</th>
                <th>Colour</th>
                <th>Lot</th>
                <th>Cutting No.</th>
                <th>Size</th>
                <th>Bundle No.</th>
                <th>Cutting Production</th>
                <th>Country</th>
              </tr>
              </thead>
              <tbody>
              @php
               $bundle_info = $tagChallan->cutting_inventory;
              @endphp
              @if(isset($bundle_info))
                @php $total = 0; @endphp
                @foreach($bundle_info as $bundle)
                  @php
                    $bundleQty = $bundle->bundlecard->quantity
                      - $bundle->bundlecard->total_rejection
                      - $bundle->bundlecard->print_rejection
                      - $bundle->bundlecard->embroidary_rejection;
                    $total += $bundleQty;
                    $bundle_no = $bundle->bundlecard->details->is_manual == 1 ? $bundle->bundlecard->size_wise_bundle_no : ($bundle->bundlecard->{getbundleCardSerial()} ?? $bundle->bundlecard->size_wise_bundle_no ?? $bundle->bundlecard->bundle_no ?? '')
                  @endphp
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $bundle->bundlecard->buyer->name ?? '' }}</td>
                    <td>{{ $bundle->bundlecard->order->style_name ?? '' }}</td>
                    <td>{{ $bundle->bundlecard->purchaseOrder->po_no ?? '' }}</td>
                    <td>{{ $bundle->bundlecard->color->name ?? '' }}</td>
                    <td>{{ $bundle->bundlecard->lot->lot_no ?? '' }}</td>
                    <td>{{ $bundle->bundlecard->cutting_no ?? '' }}</td>
                    <td>{{ $bundle->bundlecard->size->name ?? '' }}</td>
                    <td>{{ str_pad($bundle_no, 2, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $bundleQty }}</td>
                    <td>{{-- $bundle->bundlecard->order->buyer->country->name ?? '' --}}</td>
                  </tr>
                @endforeach
                <tr>
                  <td colspan="8" class="text-right"><b>Total :</b></td>
                  <td><b>{{ str_pad(count($bundle_info), 2, '0', STR_PAD_LEFT) }}</b></td>
                  <td><b>{{ str_pad($total, 3, '0', STR_PAD_LEFT) }}</b></td>
                  <td></td>
                </tr>
              @else
                <tr>
                  <td colspan="10" align="center">Not found</td>
                </tr>
              @endif
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection