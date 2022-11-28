<!-- .modal -->
<div id="confirmationModal" class="modal fade animate" data-backdrop="true">
  <div class="modal-dialog" style="width: 30%;" id="animate">
    <div class="modal-content">
      <div class="modal-header">
      	<h5 class="modal-title">Confirmation</h5>
      </div>
      <div class="modal-body text-center">
        <p>Are you sure to execute this action?</p>
      </div>
      <div class="modal-footer">
        {!! Form::open(['url' => '', 'method' => 'DELETE', 'id' => 'confirmationForm']) !!}
          <button type="button" class="btn p-x-md" style="background-color: #B2BEB5;" data-dismiss="modal">No</button>
          <button type="submit" class="btn btn-danger p-x-md">Yes</button>
        {!! Form::close() !!}
      </div>
    </div><!-- /.modal-content -->
  </div>
</div>
<!-- / .modal -->