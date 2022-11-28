@if($sizes)
  @foreach($sizes as $size)
    <tr style="height: 35px;" class="text-center">
      <td>{{ $size->size->name }}</td>
      <td>{{ $size->quantity }}</td>
      <td>{{ $size->getup_qty }}</td>    
      <td><input type="number" name="quantity[]" required="required"></td>
    </tr>
    <input type="hidden" name="size_id[]" value="{{ $size->size_id }}">      
  @endforeach
@endif