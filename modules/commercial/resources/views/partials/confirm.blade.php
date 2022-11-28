<!-- .modal -->
<div id="confirmationModal" class="modal fade animate" data-backdrop="true">
  <div class="modal-dialog" id="animate">
    <div class="modal-content">
      <div class="modal-header">
      	<h5 class="modal-title">Confirmation</h5>
      </div>
      <div class="modal-body text-center p-lg">
        <p>Are you sure to execute this action?</p>
      </div>
      <div class="modal-footer">
        {!! Form::open(['url' => '', 'method' => 'DELETE', 'id' => 'confirmationForm']) !!}
          <button type="button" class="btn dark-white p-x-md" data-dismiss="modal">No</button>
          <button type="submit" class="btn danger p-x-md">Yes</button>
        {!! Form::close() !!}
      </div>
    </div><!-- /.modal-content -->
  </div>
</div>
<!-- / .modal -->