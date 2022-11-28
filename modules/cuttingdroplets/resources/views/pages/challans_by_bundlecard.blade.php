@extends('cuttingdroplets::layout')
@section('title', 'Get Challans')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Get Challans</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            @include('partials.response-message')
            <form method="get" action="{{ url('/get-challans-by-bundlecard') }}">
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
                  <th>Cutting Challan(SID)</th>
                  <th>Print/Embroidery Challan No</th>
                  <th>Input Challan/Tag No</th>
                  <th>Sewing Challan No</th>
                </tr>
              </thead>
              <tbody>
                @isset($bundleInfo)
                  <tr style="height: 40px; font-weight: bold;">
                    <td>{{ $bundleInfo->bundle_card_generation_detail_id ?? '' }}</td>
                    <td>{{ $bundleInfo->print_inventory->challan_no ?? '' }}</td>
                    <td>{{ $bundleInfo->cutting_inventory->challan_no ?? '' }}</td>
                    <td>{{  $bundleInfo->sewingoutput->output_challan_no ?? '' }}</td>
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
