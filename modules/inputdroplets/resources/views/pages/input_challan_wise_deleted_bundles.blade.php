@extends('inputdroplets::layout')
@section('title', $title ?? 'Deleted Challan Bundles')

@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>{{ $title ?? '' }}</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <div class="js-response-message text-center"></div>  
            <div class="factory-area text-center" style="font-size: 1.1em;">
              @if(isset($challan_info) && $challan_info->count())
                <b>{{ $title ?? '' }} No. :</b> {{ $challan_info->first()->challan_no }}
                @if ($challan_info->first()->line_no)
                  | 
                  <b>Floor No. :</b> {{ $challan_info->first()->floor_no ?? '' }} 
                  |
                  <b>Line No. :</b> {{ $challan_info->first()->line_no ?? '' }}
                @endif
              @elseif (isset($challan_alternate_info) && $challan_alternate_info->count())
                <b>{{ $title ?? '' }} No. :</b> {{ $challan_alternate_info->first()->challan_no }}
                @if ($challan_alternate_info->line_id)
                  | 
                  <b>Floor No. :</b> {{ $challan_alternate_info->line->floor->floor_no ?? '' }} 
                  |
                  <b>Line No. :</b> {{ $challan_alternate_info->line->line_no ?? '' }}
                @endif
              @endif  
            </div>

            <hr>

            <table class="reportTable">              
              <thead>
                <tr>
                  <th>SL</th>
                  <th>Barcode</th>
                  <th>Buyer</th>
                  {{-- <th>Booking No</th> --}}
                  <th>Style</th>
                  <th>PO</th>
                  <th>OQ</th>
                  <th>Color</th>
                  <th>Ct No.</th>
                  <th>Lot</th>                  
                  <th>Size</th>
                  <th>Bundle No.</th>                  
                  <th>Serial No.</th>                  
                  <th>Quantity</th>
                  <th>Sewing Scan</th>
                  <th>Deleted By</th>
                  <th>Deleted Time</th>
                </tr>
              </thead>
              <tbody>
                @if($challan_info)
                    @php 
                      $total = 0; 
                    @endphp
                  @foreach($challan_info as $bundle)
                    @php
                      $bundle_qty = $bundle->quantity 
                        - $bundle->total_rejection 
                        - $bundle->print_rejection
                        - $bundle->embroidary_rejection;
                      $total += $bundle_qty;
                      $bundle_no = $bundle->is_manual == 1 ? $bundle->size_wise_bundle_no : ($bundle->{getbundleCardSerial()} ?? $bundle->size_wise_bundle_no ?? $bundle->bundle_no ?? '')
                    @endphp
                    <tr class="tr-height">
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ str_pad($bundle->id, 9, '0', STR_PAD_LEFT) ?? '' }}</td>
                      <td>{{ $bundle->buyer_name ?? '' }}</td>
                      {{-- <td>{{ $bundle->booking_no ?? '' }}</td> --}}
                      <td>{{ $bundle->style_name ?? '' }}</td>  
                      <td>{{ $bundle->po_no ?? '' }}</td>
                      <td>{{ $bundle->po_quantity ?? 0 }}</td>
                      <td>{{ $bundle->color_name ?? '' }}</td>
                      <td>{{ $bundle->cutting_no ?? '' }}</td>
                      <td>{{ $bundle->lot_no ?? '' }}</td>
                      <td>{{ $bundle->size_name ?? ''}}@if($bundle->suffix)({{ $bundle->suffix }}) @endif</td>
                      <td>{{ $bundle_no }}</td>                      
                      <td>{{ $bundle->serial ?? '' }}</td>                      
                      <td>{{ $bundle_qty }}</td>
                      <td>{{ ($bundle->sewing_output_date != null) ? 'Yes': 'No' }}</td>
                      <td>{{ $bundle->deleted_by ? $bundle->deleted_by : '-' }}</td>
                      <td>{{ $bundle->deleted_at ? date('d M, Y h:m a', strtotime($bundle->deleted_at)): '-' }}</td>
                    </tr>
                  @endforeach
                    <tr class="tr-height" style="font-weight: bold;">
                      <td colspan="12" align="right">Total &nbsp;</td>
                      <td>{{ $total }}</td>
                      <td colspan="3"></td>
                    </tr>
                @else
                  <tr>
                    <td colspan="16" align="center">Not found</td>
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
  <script>
    $(document).on('click', '.input-bundle-btn', function () {
      if (confirm('Are you sure to delete this') == true) {
        var id = $(this).val();
        var current = $(this);
        if(id){
          $.ajax({
            type: 'DELETE',
            url: '/delete-input-bundle/'+id,
            success: function (response) {
              if(response == 200){
                $('.js-response-message').html(getMessage(D_SUCCESS, 'success')).fadeIn().delay(2000).fadeOut(2000);
                current.parent('td').parent('tr').remove();
              }else{
                  var message = response.message ? response.message : D_FAIL;
                $('.js-response-message').html(getMessage(message, 'danger')).fadeIn().delay(2000).fadeOut(2000);
              }
            }
          });
        }
      }
    });
  </script>
@endsection