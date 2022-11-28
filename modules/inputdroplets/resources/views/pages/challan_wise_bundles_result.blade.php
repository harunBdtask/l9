@if(isset($challan_wise_bundles))
  @php
      $total_cutting_qty = 0;
      $total_print_sent_qty = 0; 
      $total_print_received_qty = 0; 
      $total_print_rejection = 0;
      $total_embr_sent_qty = 0; 
      $total_embr_received_qty = 0; 
      $total_embr_rejection = 0;
      $total_cutting_rejection = 0;
      $total_input_qty = 0;
      $total_sewing_qty = 0;
      $total_sewing_rejection = 0;
  @endphp
  @foreach($challan_wise_bundles->cutting_inventory as $bundle)
    @php 
        $cutting_qty = $bundle->bundlecard->quantity; 
        $total_cutting_qty += $cutting_qty;
        $cutting_rejection = $bundle->bundlecard->total_rejection;
        $total_cutting_rejection += $cutting_rejection; 

        $print_sent_qty = 0;
        $print_received_qty = 0;
        $print_rejection = 0;
        if ($bundle->print_status == 1) { // 1 = print          
          $print_sent_qty = $bundle->bundlecard->quantity - $bundle->bundlecard->total_rejection;
          $print_received_qty = $bundle->bundlecard->quantity 
            - $bundle->bundlecard->total_rejection 
            - $bundle->bundlecard->print_rejection;
          $print_rejection = $bundle->bundlecard->print_rejection;

          $total_print_sent_qty += $print_sent_qty; 
          $total_print_received_qty += $print_received_qty; 
          $total_print_rejection += $print_rejection;
        }

        $embr_sent_qty = 0;
        $embr_received_qty = 0;
        $embr_rejection = 0;
        if ($bundle->print_status == 2) { // 2 = embroidary
          $embr_sent_qty = $bundle->bundlecard->quantity - $bundle->bundlecard->total_rejection;
          $embr_received_qty = $bundle->bundlecard->quantity 
            - $bundle->bundlecard->total_rejection 
            - $bundle->bundlecard->embroidary_rejection;
          $embr_rejection = $bundle->bundlecard->embroidary_rejection;

          $total_embr_sent_qty += $embr_sent_qty; 
          $total_embr_received_qty += $embr_received_qty; 
          $total_embr_rejection += $embr_rejection;
        }
        $input_qty = $bundle->bundlecard->quantity 
          - $bundle->bundlecard->total_rejection 
          - $print_rejection - $embr_rejection;
        $total_input_qty += $input_qty;

        $sewing_qty = 0;
        $sewing_rejection = 0;
        if ($bundle->sewingoutput) {
          $sewing_rejection = $bundle->bundlecard->sewing_rejection;
          $sewing_qty = $cutting_qty 
          - $cutting_rejection 
          - $print_rejection 
          - $embr_rejection
          - $sewing_rejection;

          $total_sewing_qty += $sewing_qty;
          $total_sewing_rejection += $sewing_rejection;
        }
    @endphp
    <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ str_pad($bundle->bundlecard->id, 9, '0', STR_PAD_LEFT) }}</td>
      <td>{{ $bundle->bundlecard->buyer->name ?? '' }}</td>
      <td>{{ $bundle->bundlecard->order->style_name ?? '' }}</td>
      <td>{{ $bundle->bundlecard->purchaseOrder->po_no ?? '' }}</td>
      <td>{{ $challan_wise_bundles->color->name ?? '' }}</td>
      <td>{{ $bundle->bundlecard->cutting_no ?? '' }}</td>
      <td>{{ $bundle->bundlecard->{getbundleCardSerial()} ?? $bundle->bundlecard->bundle_no }}</td>    
      <td>{{ $bundle->bundlecard->size->name ?? '' }}</td>
      <td>{{ $cutting_qty }}</td>
      <td>{{ $print_sent_qty }}</td>
      <td>{{ $print_received_qty }}</td>
      <td>{{ $embr_sent_qty  }}</td>
      <td>{{ $embr_received_qty }}</td>
      <td>{{ $cutting_rejection }}</td>
      <td>{{ $print_rejection }}</td>
      <td>{{ $embr_rejection }}</td>
      <td>{{ $input_qty }}</td>
      <td>{{ $sewing_qty }}</td>
      <td>{{ $sewing_rejection }}</td>
      <td>{{ $cutting_rejection + $print_rejection + $embr_rejection + $sewing_rejection }}</td>
    </tr>
  @endforeach  
  <tr style="font-weight: bold">
    <td colspan="9" class="text-right">Total &nbsp;&nbsp; </td> 
    <td>{{ $total_cutting_qty }}</td>
    <td>{{ $total_print_sent_qty }}</td>
    <td>{{ $total_print_received_qty }}</td>
    <td>{{ $total_embr_sent_qty }}</td>
    <td>{{ $total_embr_received_qty }}</td>
    <td>{{ $total_cutting_rejection }}</td>
    <td>{{ $total_print_rejection }}</td>
    <td>{{ $total_embr_rejection }}</td>
    <td>{{ $total_input_qty }}</td>
    <td>{{ $total_sewing_qty }}</td>
    <td>{{ $total_sewing_rejection }}</td>
    <td>{{ $total_cutting_rejection + $total_print_rejection + $total_embr_rejection + $total_sewing_rejection }}</td>
  </tr>
@else
  <tr class="tr-height">
    <td colspan="21" class="text-center text-danger">Not found</td>
  </tr>
@endif