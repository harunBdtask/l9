@if($orders)
  @foreach($orders as $order)
    <tr class="odd gradeX">
        <td>{{ $order->po_no }}</td>
        <td>{{ $order->po_quantity }}</td>
        <td><input type="number" name="smv" class="order-smv text-right" value="{{ $order->smv }}"></td>
        <td><button type="button" value="{{ $order->id }}" class="btn white smv-update-btn">Update</button></td>
    </tr>    
  @endforeach       
@endif   
  
        