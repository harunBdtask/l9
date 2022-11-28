@extends('printembrdroplets::layout')
@section('title', 'Bundle Send To Print/Embroidery')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Bundle Send To Print/Embroidery</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          @include('partials.response-message')
          <div class="text-center js-response-message"></div>
          <div class="row">
            <form id="printSendFormSubmit" autocomplete="off">
              <div class="form-group" style="margin-bottom: 0px !important;">
                <div class="col-sm-8 col-sm-offset-2">
                  <input type="text" class="form-control form-control-sm has-value" id="printBarcode" placeholder="Scan barcode here"
                    autofocus="" name="barcode" required="required">
                  <span class="print-send-challan" print-send-challan="{{ $challan_no ?? '' }}"> Challan no:
                    {{ $challan_no }}</span>
                </div>
              </div>
              <div class="form-group" style="margin-top: 0px !important;">
                <div class="col-sm-8 col-sm-offset-2 form-group text-center @if(count($bundle_info) == 0) hide @endif">
                  <div class="text-center">
                    <a href="{{ url('/send-to-print/'.$challan_no)}}" class="btn btn-success">Create GatePass/Challan</a>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="row">
            <span class="loader"></span>
          </div>

          <div class="table-responsive">
            <table class="reportTable">
              <thead>
                <tr>
                  <th>SL</th>
                  <th>Buyer</th>
                  <th>Style</th>
                  <th>PO</th>
                  <th>Colour</th>
                  <th>Lot</th>
                  <th>Size</th>
                  <th>Cutting No.</th>
                  <th>Bundle No</th>
                  <th>Cutt. Production</th>
                </tr>
              </thead>
              <tbody id="printSendScanResult">
                @if(isset($bundle_info))
                @php $total = 0; @endphp
                @foreach($bundle_info as $bundle)
                @php $total += $bundle['quantity'] - $bundle['total_rejection'];@endphp
                <tr style="height: 19px !important">
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $bundle['buyer_name'] ?? '' }}</td>
                  <td>{{ $bundle['style_name'] ?? ''}}</td>
                  <td>{{ $bundle['po_no'] ?? ''}}</td>
                  <td>{{ $bundle['color_name'] ?? '' }}</td>
                  <td>{{ $bundle['lot_no'] ?? '' }}</td>
                  <td>{{ $bundle['size_name'] ?? '' }}</td>
                  <td>{{ $bundle['cutting_no'] ?? ''}}</td>
                  <td>{{ $bundle['bundle_no'] ?? '' }}</td>
                  <td>{{ $bundle['quantity'] - $bundle['total_rejection'] }}</td>
                </tr>
                @endforeach
                <tr>
                  <td colspan="8" class="text-right"><b>Total :</b></td>
                  <td><b class="totalBundle">{{ count($bundle_info) }}</b></td>
                  <td><b class="totalQty">{{ $total }}</b></td>
                </tr>
                @else
                <tr>
                  <td colspan="10" align="center">Not found
                  <td>
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
@section('scripts')
  <script src="{{ asset('protracker/custom.js') }}"></script>
  <script src="{{ asset('protracker/scan-related.js') }}"></script>
@endsection
