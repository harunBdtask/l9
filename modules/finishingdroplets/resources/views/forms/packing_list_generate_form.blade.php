@if($sizes)
    @foreach($sizes as $size)
        <tr style="height: 36px" class="text-center">
            <td>{{ $size->size->name }}</td>
            <td>{{ $size->quantity }}</td>
            <td>{{ $size->packing_qty }}</td>
            <td><input class="form-control form-control-sm" type="number" name="quantity[]" required="required"></td>
        </tr>
        <input type="hidden" name="size_id[]" value="{{ $size->size_id }}">
    @endforeach
@endif
