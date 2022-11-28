@extends('cuttingdroplets::layout')
@section('title', 'Challan Wise Bundle')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>SID Wise Bundles</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
              @include('partials.response-message')
              <form action="{{ url('/challan-wise-bundle') }}" method="get" autocomplete="off">
                  <div class="row form-group">
                    <div class="col-sm-8 col-sm-offset-2">
                       <input type="text" class="form-control form-control-sm" id="sid" placeholder="Enter SID" autofocus="" required="required" name="sid" value="{{ $sid ?? '' }}">

                       @if($errors->has('sid'))
                        <span class="text-danger">{{ $errors->first('sid') }}</span>
                       @endif
                    </div>
                  </div>
              </form>
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
                      <th>C. No.</th>
                      <th>Size</th>
                      <th>Bn. no.</th>
                      <th>Cutt. Qty</th>
                      <th>Print/Embr Sent Qty </th>
                      <th>Recv. Qty </th>
                      <th>Input Qty</th>
                      <th>Output Qty</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($bundles)
                      @foreach($bundles as $bundle)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $bundle->buyer->name ?? '' }}</td>
                          <td>{{ $bundle->order->style_name ?? '' }}</td>
                          <td>{{ $bundle->purchaseOrder->po_no ?? '' }}</td>
                          <td>{{ $bundle->color->name ?? '' }}</td>
                          <td>{{ $bundle->lot->lot_no ?? '' }}</td>
                          <td>{{ $bundle->cutting_no }}</td>
                          <td>{{ $bundle->size->name ?? '' }}</td>
                          <td>{{ $bundle->{getbundleCardSerial()} ?? $bundle->bundle_no }}</td>
                          <td>{{ $bundle->quantity }}</td>
                          <td>
                            @if ($bundle->print_sent_date || $bundle->embroidary_sent_date)
                              {{ $bundle->quantity - $bundle->total_rejection }}
                            @else
                              0
                            @endif
                          </td>
                          <td>
                            @if ($bundle->print_received_date || $bundle->embroidary_received_date)
                              {{ $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection - $bundle->embroidery_rejection }}
                            @else
                              0
                            @endif
                          </td>
                          <td>
                            @if ($bundle->input_date)
                              {{ $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection - $bundle->embroidery_rejection }}
                            @else
                              0
                            @endif
                          </td>
                          <td>
                            @if ($bundle->input_date)
                              {{
                                $bundle->quantity - $bundle->total_rejection - $bundle->print_rejection
                                  - $bundle->embroidery_rejection - $bundle->embroidery_rejection - $bundle->sewing_rejection
                              }}
                            @else
                              0
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    @else
                      <tr class="tr-height">
                        <td colspan="14" align="center" class="text-danger">Not found<td>
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
