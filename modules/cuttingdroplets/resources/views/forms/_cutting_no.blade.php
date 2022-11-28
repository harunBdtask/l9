<div class="row">
  <div class="col-md-4">
    <label for="color">Color</label>
  </div>
  <div class="col-md-4">
    <label for="color">Last Cutting No</label>
  </div>
  <div class="col-md-4">
    <label for="color">Running Cutting No</label>
  </div>
</div>

@foreach($cuttingNos as $cuttingNo)
  <div class="row">
    <div class="col-md-4">
      <div class="form-group">
        <input class="form-control form-control-sm" type="text" placeholder="" value="{{ $cuttingNo['color_name'] }}" readonly>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <input class="form-control form-control-sm" type="text" placeholder="" value="{{ $cuttingNo['last_cutting_no'] }}" readonly>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <input class="form-control form-control-sm" name="cutting_nos[{{$cuttingNo['color_id']}}]" type="text" placeholder="" value="{{ $cuttingNo['cutting_no'] }}">
      </div>
    </div>
  </div>
@endforeach
