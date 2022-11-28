<tbody>
  @if($reportData)
    @php
      error_reporting(0);
      $grand_total_cutting = 0;
      $grand_total_cutting_rejection = 0;
      $grand_balance_cutting = 0;
      $grand_total_sent = 0;
      $grand_total_received = 0;
      $grand_balance_print = 0;
      $grand_total_print_rejection = 0;
      $grand_total_input = 0;
      $grand_total_sewing_output = 0;
      $grand_total_sewing_rejection = 0;
      $grand_total_finishing = 0;
      $grand_total_finishing_rejection = 0;
      $grand_total_sewing_rejection = 0;
      $grand_total_inspection = 0;
      $grand_total_inspection_rejection = 0;
      $grand_total_ship_quantity = 0;
    @endphp
    @foreach($reportData as $report)
        @php
           $grand_total_cutting += $report->totalProductionReport->sum('total_cutting') ?? 0;
           $grand_total_cutting_rejection += $report->totalProductionReport->sum('total_cutting_rejection') ?? 0;
           $grand_total_sent += $report->totalProductionReport->sum('total_sent') ?? 0;
           $grand_total_received += $report->totalProductionReport->sum('total_received') ?? 0;
           $grand_balance_print += $report->totalProductionReport->sum('total_input') ?? 0;
           $grand_total_print_rejection += $report->totalProductionReport->sum('total_print_rejection') ?? 0;
           $grand_total_input += $report->totalProductionReport->sum('total_input') ?? 0;
           $grand_total_sewing_output += $report->totalProductionReport->sum('total_sewing_output') ?? 0;
           $grand_total_sewing_rejection += $report->totalProductionReport->sum('total_sewing_rejection') ?? 0;
           $grand_total_finishing += 0;//$report->totalProductionReport->total_finishing ?? 0;
           $grand_total_finishing_rejection += 0;//$report->totalProductionReport->total_finishing_rejection ?? 0;;
           $grand_total_inspection += $report->totalProductionReport->sum('total_inspection') ?? 0;
           $grand_total_inspection_rejection += $report->totalProductionReport->sum('total_inspection_rejection') ?? 0;
           $grand_total_shipment_qty += $report->totalProductionReport->sum('total_shipment_qty') ?? 0;
        @endphp

      <tr @if($print) style="font-size: 7px !important" @endif>
        <td title="{{ $report->buyer->name ?? '' }}">{{ $print ? ($report->buyer->name ?? '') : substr($report->buyer->name, 0, 6) ?? '' }}</td>
        <td title="{{ $report->order->order_style_no ?? '' }}">{{ $print ? ($report->order->order_style_no ?? '') : substr($report->order->order_style_no, 0, 6) ?? '' }}</td>
        <td title="{{  $report->po_no ?? '' }}">{{ $print ? ($report->po_no ?? '') : substr($report->po_no, -7) }}</td>
        <td>{{ $report->po_quantity ?? 0 }}</td>
        <td>
            {{  $report->ex_factory_date ? date('j M Y', strtotime($report->ex_factory_date)) : '' }}
        </td>
        @php          
          $cuttingStartDate = '';
          if(isset($report->bundleCards->first()->cutting_date)) {
            $cuttingStartDate = date('j M Y', strtotime($report->bundleCards->first()->cutting_date));
            $parties = \SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintInventoryChallan::getPurchaseOrderWiseParties($report->id);
          }
        @endphp       
        <td>{{ $cuttingStartDate ?? '' }}</td>       
        <td>{{ $report->totalProductionReport->sum('total_cutting') - $report->totalProductionReport->sum('total_cutting_rejection') }}</td>
        <td>{{ $report->totalProductionReport->sum('total_cutting') - $report->po_quantity - $report->totalProductionReport->sum('total_cutting_rejection') }}</td>
        <td>{{ $report->totalProductionReport->sum('total_sent') }} </td>
        <td>{{ $report->totalProductionReport->sum('total_received') }}</td>
        <td>{{ $report->totalProductionReport->sum('total_received') - $report->totalProductionReport->sum('total_sent') }}</td>
        <td title="{{ implode($parties, ',')  }}"> @if(!$print){{ substr($parties[0] ?? '', 0, 10) }} @else {{ implode(',', $parties) }}  @endif
        </td>
        <td>{{ $report->totalProductionReport->sum('total_input') }}</td>
        <td>{{ $report->totalProductionReport->sum('total_sewing_output') }}</td>
        <td>{{ $report->totalProductionReport->sum('finishing_qty') }}</td>
        <td></td>
        <td>{{ $report->totalProductionReport->sum('total_inspection') }}</td>
        <td>{{ $report->totalProductionReport->sum('total_inspection_rejection') }}</td>
        <td>{{ $report->totalProductionReport->sum('total_cutting_rejection') }}</td>
        <td>{{ $report->totalProductionReport->sum('total_print_rejection') }}</td>
        <td>{{ $report->totalProductionReport->sum('total_sewing_rejection') }}</td>
        <td>{{ $report->totalProductionReport->sum('finishing_rejection_qty')  }}</td>
        <td>{{ $report->totalProductionReport->sum('total_cutting_rejection') + $report->totalProductionReport->sum('total_print_rejection') + $report->totalProductionReport->sum('total_sewing_rejection') + $report->totalProductionReport->sum('finishing_rejection_qty')  + $report->totalProductionReport->sum('total_inspection_rejection') }}</td>
        <td>{{ $report->totalProductionReport->sum('total_shipment_qty') }}</td>
        <td>{{ $report->totalProductionReport->sum('total_shipment_qty') - $report->po_quantity }}</td>
        <td></td>
      </tr>
    @endforeach        
      <tr style="line-height: 30px;@if($print)font-size: 7px @else font-weight: bold; @endif">
        <td colspan="3">Total</td>
        <td>{{ $reportData->sum('po_quantity') }}</td>
        <td></td>
        <td></td>
        <td>{{ $grand_total_cutting - $grand_total_cutting_rejection }}</td>
        <td style="width: 5%">{{ $grand_total_cutting - $reportData->sum('po_quantity') - $grand_total_cutting_rejection }}</td>

        <td>{{ $grand_total_sent }}</td>
        <td>{{ $grand_total_received }}</td>
        <td>{{ $grand_total_received  - $grand_total_sent }}</td>
        <td></td>
        <td>{{ $grand_total_input }}</td>
        <td>{{ $grand_total_sewing_output }}</td>
        <td>{{ $grand_total_finishing }}</td>
        <td></td>
        <td>{{ $grand_total_inspection }}</td>
        <td>{{ $grand_total_inspection_rejection }}</td>
        <td>{{ $grand_total_cutting_rejection }}</td>
        <td>{{ $grand_total_print_rejection }}</td>
        <td>{{ $grand_total_sewing_rejection }}</td>
        <td>{{ $grand_total_finishing_rejection }}</td>

        <td>{{ $grand_total_cutting_rejection + $grand_total_print_rejection + $grand_total_sewing_rejection + $grand_total_finishing_rejection }}</td>

        <td>{{ $grand_total_shipment_qty }}</td>
        <td>{{ $grand_total_shipment_qty - $reportData->sum('total_quantity') }}</td>
        <td></td>
        
      </tr>
  @endif
</tbody>