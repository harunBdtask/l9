@extends('cuttingdroplets::layout')
@section('title', 'Cutting Production Update')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Cutting Production Update || {{ date("jS F, Y") }}</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">

          @include('partials.response-message')

          {!! Form::open(['url' => 'cutting-scan-post', 'method' => 'POST']) !!}
          <div class="row form-group">
            <div class="col-sm-8 col-sm-offset-2">
              <span>Challan No: {{ $challan_no }}</span>
              {!! Form::hidden('challan_no', $challan_no) !!}
              <input class="form-control form-control-sm has-value" id="barcode" placeholder="Enter barcode here"
                autofocus="" required="required" name="barcode" type="text">


              @if($errors->has('barcode'))
              <span class="text-danger">{{ $errors->first('barcode') }}</span>
              @endif
            </div>
          </div>
          <div class="row form-group m-t-md">
            <div class="col-sm-offset-5 col-sm-7">
              <button type="button" class="btn btn-danger"><a href="{{ url('/close-cutting-challan/'.$challan_no) }}">Close
                  Challan</a></button>
            </div>
          </div>
          {!! Form::close() !!}

          <div class="row table-responsive">
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
                  <th>Bundle Scanned</th>
                  <th>Cutting Production</th>
                  <th>Country</th>
                </tr>
              </thead>
              <tbody>
                @if(isset($bundle_info))
                @foreach($bundle_info as $bundle)
                @php
                $bundle_no = $bundle->{getbundleCardSerial()} ?? $bundle->bundle_no;
                @endphp
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $bundle->buyer->name ?? '' }}</td>
                  <td>{{ $bundle->order->style_name ?? '' }}</td>
                  <td>{{ $bundle->purchaseOrder->po_no }}</td>
                  <td>{{ $bundle->color->name ?? '' }}</td>
                  <td>{{ $bundle->lot['lot_no'] }}</td>
                  <td>{{ $bundle->cutting_no }}</td>
                  <td>{{ $bundle->size['name'] }}</td>
                  <td>{{ str_pad($bundle_no, 3, '0', STR_PAD_LEFT) }}</td>
                  <td>{{ str_pad($bundle->quantity, 2, '0', STR_PAD_LEFT) }}</td>
                  <td>{{ $bundle->details->buyer->country->name ?? '' }}</td>
                </tr>
                @endforeach
                <tr>
                  <td colspan="9" class="text-right"><b>Total :</b></td>
                  <td><b>{{ $bundle_info->sum('quantity') }}</b>
                  <td></td>
                </tr>
                @else
                <tr>
                  <td colspan="11" class="text-center">No Rows</td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection