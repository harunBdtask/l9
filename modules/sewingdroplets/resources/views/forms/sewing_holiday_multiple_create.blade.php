<div class="modal-header">
  <h5 class="modal-title" id="exampleModalLongTitle">Holiday Entry</h5>
</div>
{!! Form::open(['url' => '/sewing-holidays', 'method' => 'POST', 'id' => 'holidayCreateForm']) !!}
<div class="modal-body">
  <div class="holiday-flash-message">
  </div>
  <div class="table-responsive" style="max-height: 400px;">
    <table class="reportTable">
      <thead>
      <tr>
        <th width="50%">Dates</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody class="holidayCreateTableBody">
      <tr class="holidayCreateTableRow">
        <td>
          {!! Form::date('holiday[]', null, ['class' => 'form-control form-control-sm', 'size' => '20']) !!}
          <span class="text-danger holiday"></span>
        </td>
        <td>
          <div>
            <button type="button" class="add-new-holiday-row btn btn-sm btn-success"><i
                  class="fa fa-plus"></i></button>
            <button type="button" class="remove-holiday-row btn btn-sm btn-danger hide"><i
                  class="fa fa-times"></i></button>
          </div>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</div>
<div class="modal-footer">
  <button type="submit" class="btn btn-success pull-left">Save Changes</button>
  <button type="button" class="btn btn-sm white m-b" data-dismiss="modal">Close</button>
</div>
{!! Form::close() !!}
