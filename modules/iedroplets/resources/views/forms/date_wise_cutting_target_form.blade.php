@if($cutting_tables)
  @forelse($cutting_tables as $target)
    <tr style="height: 38px">
      <input type="hidden" name="cutting_table_id" value="{{ $target->table_id ?? '' }}">
      <td><input type="text" class="form-control form-control-sm" disabled="disabled" value="{{ $target->table_no ?? '' }}"></td>
      <td><input type="number" class="form-control form-control-sm" name="target[]" value="{{ $target->target ?? '' }}"></td>
      <td><input type="number" class="form-control form-control-sm" name="mp[]" value="{{ $target->mp ?? '' }}"></td>
      <td><input type="number" class="form-control form-control-sm" name="wh[]" value="{{ $target->wh ?? '' }}"></td>
      <td><input type="number" class="form-control form-control-sm" name="npt[]" value="{{ $target->npt ?? '' }}"></td>
      <input type="hidden" name="table_id[]" value="{{ $target->table_id }}">
      <input type="hidden" name="cutting_floor_id[]" value="{{ $target->cutting_floor_id }}">
  </tr>
  @empty
    <tr>
      <tr style="height: 38px">
        <td colspan="5" class="text-danger">Table not found </td>
      </tr>
    </tr>
  @endforelse
  @if($cutting_tables->count())
  <tr style="height: 45px">
    <td colspan="5">
        <button type="submit" class="btn btn-sm btn-success cutting-target-btn text-center">Submit</button>
    </td>
  </tr>
  @endif
@endif
