@extends('cuttingdroplets::layout')
@section('title', 'Individual Bundle Scan Check')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Individual Bundle Scan Check</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            @include('partials.response-message')
            <form method="get" action="{{ url('/individual-bundle-scan-check') }}">
              <div class="form-group">
                <div class="row m-b">
                    <div class="col-sm-offset-3 col-sm-6">
                      {!! Form::text('bundlecard', $bundlecard ?? null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Please Enter bundlecard']) !!}
                    </div>
                  </div>
                </div>
            </form>
            <table class="reportTable">
              <thead>
                <tr>
                  <th>Cutting Date</th>
                  <th>Print/Embroidery Sent</th>
                  <th>Print/Embroidery Receievd</th>
                  <th>Input/Tag</th>
                  <th>Sewingoutput</th>
                </tr>
              </thead>
              <tbody>
                @isset($bundleInfo)
                  @php
                    $notScanned = '<span style="color:red">Not Scanned</span>';
                  @endphp
                  <tr style="height: 40px; font-weight: bold;">
                    <td>{!! $bundleInfo->cutting_date ?? $notScanned !!}</td>
                    <td>{!! (isset($bundleInfo->print_inventory) && $bundleInfo->print_inventory->created_at) ? date('Y-m-d  ||  h:i A', strtotime($bundleInfo->print_inventory->created_at)) : $notScanned !!}</td>
                    <td>
                      {!!
                        (isset($bundleInfo->cutting_inventory) && $bundleInfo->cutting_inventory->print_status && $bundleInfo->cutting_inventory->created_at)
                          ? date('Y-m-d  ||  h:i A', strtotime($bundleInfo->cutting_inventory->created_at))
                          : $notScanned
                      !!}
                    </td>
                    <td>{!! (isset($bundleInfo->cutting_inventory) && $bundleInfo->cutting_inventory->updated_at) ? date('Y-m-d  ||  h:i A', strtotime($bundleInfo->cutting_inventory->updated_at)) : $notScanned !!}</td>
                    <td>{!! (isset($bundleInfo->sewingoutput) && $bundleInfo->sewingoutput->created_at) ? date('Y-m-d  ||  h:i A', strtotime($bundleInfo->sewingoutput->created_at)) : $notScanned !!}</td>
                  </tr>
                @endisset
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
