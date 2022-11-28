<div class="modal-header">
  <h5 class="modal-title" id="exampleModalLongTitle">Holiday Update</h5>
</div>
{!! Form::open(['url' => '/sewing-holidays/'.$sewing_holiday->id, 'method' => 'PUT', 'id' => 'holidayUpdateForm']) !!}
<div class="modal-body">
  <div class="holiday-flash-message">
  </div>
  <div class="form-group">
    <div class="row">
      <div class="col-sm-offset-3 col-sm-6">
        <label>Holiday</label>
        {!! Form::date('holiday', $sewing_holiday->holiday ?? null, ['class' => 'form-control form-control-sm', 'id' => 'holiday']) !!}
        <span class="text-danger holiday"></span>
      </div>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="submit" class="btn btn-success pull-left">Save Changes</button>
  <button type="button" class="btn btn-sm white m-b" data-dismiss="modal">Close</button>
</div>
{!! Form::close() !!}
