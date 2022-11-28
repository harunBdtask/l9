@extends('sewingdroplets::layout')
@section('title', 'Bundle Wise QC')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Bundle Wise QC</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            @include('partials.response-message')
            {!! Form::open(array('url' => 'bundle-wise-qc', 'method' => 'get')) !!}
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-offset-3 col-sm-6">
                  {!! Form::text('bundlecard', $bundlecard ?? null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Please Enter bundlecard']) !!}
                </div>
              </div>
            </div>
            {!! Form::close() !!}
            <table class="reportTable">
              <thead>
              <tr>
                <th>Buyer</th>
                <th>Style</th>
                <th>Color</th>
                <th>Size</th>
                <th>Bundle No</th>
                <th>Cutting No</th>
                <th>Cutting Qty</th>
                <th>Cutting Rejection Qty</th>
                <th>Print Rejection Qty</th>
                <th>Embroidery Rejection Qty</th>
                <th>Sewing Rejection Qty</th>
                <th>Final QC</th>
              </tr>
              </thead>
              <tbody>
              @isset($bundleInfo)
                @php
                  $final_qc_pass = $bundleInfo->quantity - $bundleInfo->total_rejection - $bundleInfo->print_rejection - $bundleInfo->embroidary_rejection - $bundleInfo->sewing_rejection;
                @endphp
                <tr style="height: 40px; font-weight: bold;">
                  <td>{{ $bundleInfo->buyer->name ?? '' }}</td>
                  <td>{{ $bundleInfo->order->style_name ?? '' }}</td>
                  <td>{{ $bundleInfo->color->name ?? '' }}</td>
                  <td>{{ $bundleInfo->size->name ?? '' }}</td>
                  <td>{{ $bundleInfo->size_wise_bundle_no ?? '' }}</td>
                  <td>{{ $bundleInfo->cutting_no ?? '' }}</td>
                  <td>{{ $bundleInfo->quantity ?? 0 }}</td>
                  <td>{{ $bundleInfo->total_rejection ?? 0 }}</td>
                  <td>{{ $bundleInfo->print_rejection ?? 0 }}</td>
                  <td>{{ $bundleInfo->embroidary_rejection ?? 0 }}</td>
                  <td>{{ $bundleInfo->sewing_rejection ?? 0 }}</td>
                  <td>{{ $final_qc_pass }}</td>
                </tr>
              @endisset
              </tbody>
            </table>

            <div class="loader"></div>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
