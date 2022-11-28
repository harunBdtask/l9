<div class="modal-header">
  <h5 class="modal-title">Lock/Unlock Plan Strip</h5>
</div>
{!! Form::open(['url' => '/sewing-plan-strip-lock-unlock/'. $sewing_plan->id , 'method' => 'post', 'id' => 'lockUnlockSewingPlanStripForm']) !!}
<div class="modal-body">
  <div class="lock-unlock-modal-flash-message">
  </div>
  {!! Form::hidden('is_locked', ($sewing_plan->is_locked ? 0 : 1)) !!}
  @if($sewing_plan->is_locked)
    <p>Are you sure you want to unlock this plan strip?</p>
  @else
    <p>Are you sure you want to lock this plan strip?</p>
  @endif
</div>
<div class="modal-footer">
  <button type="submit" class="btn btn-success pull-left">Yes</button>
  <button type="button" class="btn btn-sm white m-b" data-dismiss="modal">Close</button>
</div>
{!! Form::close() !!}
