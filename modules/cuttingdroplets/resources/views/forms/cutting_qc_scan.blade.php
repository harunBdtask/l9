@extends('cuttingdroplets::layout')
@section('title', 'Cutting QC Scan')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Cutting Qc Update ||  {{ date("jS F, Y") }}</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">

            @include('partials.response-message')

            {!! Form::model(['url' => 'cutting-qc-scan', 'method' => 'POST']) !!}
              <div class="form-group">
                <div class="col-sm-8 col-sm-offset-2">
                   {!! Form::text('barcode', null, ['class' => 'form-control form-control-sm', 'id' => 'barcode', 'placeholder' => 'Enter barcode here', 'autofocus']) !!}
                   <span>Challan No: {{ $qc_challan }}</span>

                   @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                   @endif
                </div>
              </div>
              <div class="form-group m-t-md">
                <div class="col-sm-offset-5 col-sm-7">
                  <button type="button" class="btn white"><a href="{{ url('/close-challan/'.$qc_challan) }}">Close Challan</a></button>
                </div>
              </div>
            {!! Form::close() !!}

          </div>
            <!--table start -->
            <table class="table table-striped">
            <thead>
              <tr>
                <th>SL</th>
                <th>Buyer</th>
                <th>PO</th>
                <th>Colour</th>
                <th>Ct. No</th>
                <th>Bundle no.</th>
                <th>Ct. Qty</th>
                <th>Rplc</th>
                <th>FHS</th>
                <th>FHL</th>
                <th>EO</th>
                <th>DS</th>
                <th>OS</th>
                <th>CS</th>
                <th>LM</th>
                <th>MY</th>
                <th>YC</th>
                <th>CM</th>
                <th>Others</th>
              </tr>
            </thead>
            <tbody>
                @if(isset($bundle_info))
                  @foreach($bundle_info as $bundle)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $bundle->order->buyer->name ?? '' }}</td>
                        <td>{{ $bundle->order->order_no }}</td>
                        <td>{{ $bundle->color->name }}</td>
                        <td>{{ $bundle->details->cutting_no ?? '' }}</td>
                        <td>{{ $bundle->bundle_no }}</td>
                        <td>{{ $bundle->quantity }}</td>
                        <td>{{ $bundle->replace ?? 0 }}</td>
                        <td>{{ $bundle->fabric_holes_small ?? 0 }}</td>
                        <td>{{ $bundle->fabric_holes_large ?? 0 }}</td>
                        <td>{{ $bundle->end_out ?? 0 }}</td>
                        <td>{{ $bundle->dirty_spot ?? 0 }}</td>
                        <td>{{ $bundle->oil_spot ?? 0 }}</td>
                        <td>{{ $bundle->colour_spot ?? 0 }}</td>
                        <td>{{ $bundle->lycra_missing ?? 0 }}</td>
                        <td>{{ $bundle->missing_yarn ?? 0 }}</td>
                        <td>{{ $bundle->yarn_contamination ?? 0 }}</td>
                        <td>{{ $bundle->crease_mark ?? 0 }}</td>
                        <td>{{ $bundle->others ?? 0 }}</td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="18" align="center">No<td>
                  </tr>
                @endif
              </tbody>
            </table>
            <!--table end -->
        </div>
      </div>
    </div>
  </div>
@endsection
