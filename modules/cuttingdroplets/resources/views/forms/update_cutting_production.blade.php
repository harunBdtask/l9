@extends('cuttingdroplets::layout')
@section('title', 'Update Cutting Production')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Update Cutting Production</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            @include('partials.response-message')
            <div class="js-response-message text-center"></div>
            <form action="{{ url('/update-cutting-production') }}" method="get" autocomplete="off">
              <div class="row form-group">
                <div class="col-sm-8 col-sm-offset-2">
                   <input type="text" class="form-control form-control-sm" id="challan" placeholder="Enter SID" autofocus="" required="required" name="challan" value="{{ ($challan ?:'')}}">

                   @if($errors->has('challan'))
                    <span class="text-danger">{{ $errors->first('challan') }}</span>
                   @endif
                </div>
              </div>
            </form>

            <table class="reportTable">
              <thead>
                <tr>
                  <th>SL</th>
                  <th>Barcode</th>
                  <th>Buyer</th>
                  <th>Style</th>
                  <th>PO</th>
                  <th>Colour</th>
                  <th>Size</th>
                  <th>Ct No.</th>
                  <th>Bundle no.</th>
                  <th>Qty</th>
                  <th>Cutting Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @if(!$bundles->isEmpty())
                  @foreach($bundles as $bundle)
                    @php
                      $bundle_no = $bundle->{getbundleCardSerial()} ?? $bundle->bundle_no;
                    @endphp
                    <tr class="tr-height">
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ str_pad($bundle->id, 9, '0', STR_PAD_LEFT)  }}</td>
                      <td>{{ $bundle->buyer->name ?? '' }}</td>
                      <td>{{ $bundle->order->style_name ?? '' }}</td>
                      <td>{{ $bundle->purchaseOrder->po_no ?? '' }}</td>
                      <td>{{ $bundle->color->name ?? '' }}</td>
                      <td>{{ $bundle->size->name ?? '' }}  {{ $bundle->suffix  ? '('. $bundle->suffix . ')' : '' }}</td>
                      <td>{{ $bundle->cutting_no ?? '' }}</td>
                      <td>{{ str_pad($bundle_no, 3, '0', STR_PAD_LEFT) }}</td>
                      <td>{{ str_pad($bundle->quantity, 2, '0', STR_PAD_LEFT) }}</td>
                      <td>{{ $bundle->cutting_date }}</td>
                      <td>
                        @if(getRole() == 'super-admin' || getRole() == 'admin' || session()->has('permission_of_update_cutting_production_delete'))
                        <button type="button" value="{{ $bundle->id }}" class="btn btn-xs btn-danger delete-bundle-btn">
                          <i class="fa fa-times"></i>
                        </button>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                @else
                  <tr class="tr-height">
                    <td colspan="12" class="text-danger text-center">Not found</td>
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
@section('scripts')
  <script src="{{ asset('protracker/custom.js')}}"></script>
  <script type="text/javascript">
    $(document).on('click', '.delete-bundle-btn', function () {
      if(confirm('Do you want to delete?') == true) {
        var current = $(this);
        var bundle_id = current.val();
        if(bundle_id) {
          showLoader();
          $.ajax({
            type: 'DELETE',
            url: '/delete-cutting-bundle/'+bundle_id,
            success: function (response) {
              hideLoader()
              if(response.status == 200) {
                current.parents('tr').remove();
                $('.js-response-message').html(getMessage(response.message, 'success')).fadeIn().delay(2000).fadeOut(2000);
              } else {
                $('.js-response-message').html(getMessage(response.message, 'danger')).fadeIn().delay(2000).fadeOut(2000);
              }
            },
            error: function (jqXHR, exception) {
              hideLoader()
              console.log(jqXHR)
            }
          });
        }
      }
    });
  </script>
@endsection
