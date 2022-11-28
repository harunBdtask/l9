<tbody>
@if(!empty($orders) && count($orders) > 0)
  @foreach($orders as $order)                   
    @if($order->extra > 0)
    <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $order->buyer }}</td>
      <td>{{ $order->style }}</td>
      <td>{{ $order->order_no }}</td>
      <td>{{ $order->total_quantity }}</td>                   
      <td>{{ $order->todays_cutting }}</td>
      <td>{{ $order->cutting_qtys }}</td>                      
      <td>{{ $order->left_qty }}</td>
      <td>{{ abs($order->extra).'%' ?? '' }}</td>    
    </tr>
    @endif
  @endforeach
    <tr style="font-weight: bold;">
      @if($order->extra > 0)
       <td colspan="4">Total</td>
       <td>{{ $orders->sum('order_qty') }}</td>
       <td>{{ $orders->sum('todays_cutting') }}</td>
       <td>{{ $orders->sum('cutting_qtys') }}</td>
       <td>{{ $orders->sum('left_qty') }}</td>
       <td>{{ '' }}</td>
      @endif
    </tr>
@else
    <tr>
      <td colspan="9" class="text-danger text-center">Not found<td>
    </tr>
@endif
</tbody>
<tfoot>
  @if($orders->total() > 15)
    <tr>
      <td colspan="9" align="center">{{ $orders->appends(request()->except('page'))->links() }}</td>
    </tr>
  @endif
</tfoot>