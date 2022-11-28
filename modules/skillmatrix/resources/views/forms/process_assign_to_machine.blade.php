@extends('skillmatrix::layout')

@section('title', 'Process Assigned To Machine')
@section('styles')
  <style type="text/css">
    .reportTable {
      margin-bottom: 150px !important;
    }
    td {
      padding: 10px !important;    
    }
    .tr-design {
      height: 50px;
    }
  </style>
@endsection
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h2>Process Assigned To Machine</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">           
            <div class="js-response-message text-center"></div>
            {!! Form::open(['id' => 'assignProcessToMachine', 'method' => 'post']) !!}          
              <table class="reportTable">
                <tbody class="tableRow">              
                  <tr>
                    <th style="width:30%;">Sewing Machine</th>
                    <th style="width:70%;">Process</th>                   
                  </tr>
                    <tr class="tr-design">
                      <td>
                        {!! Form::select('sewing_machine_id', $sewingMachines, null, ['id' => 'machine-id', 'class' => 'form-control select2-input']) !!}
                        <span class="sewing_machine_id"></span>
                      </td>
                      <td>
                        <div class="row process-row" style="padding-bottom: 5px;">
                          <div class="col-md-12">
                            <div class="col-md-9">
                              {!! Form::select('sewing_process_id[]', $sewingProcesses, null, ['id' => 'process-id', 'class' => 'form-control select2-input process']) !!}
                              <span class="sewing_process_id"></span>
                            </div>  
                            <div class="col-md-3">
                              <span class="btn btn-sm add-more-btn btn-success"><i class="fa fa-plus"></i> </span>
                              <span class="btn btn-sm del-row btn-danger"><i class="fa fa-times"></i> </span>
                            </div>
                          </div>
                        </div>
                      </td>
                    </tr>               
                    <tr style="height:50px">
                      <td colspan="6">
                        {!! Form::button('Submit', ['class' => 'btn btn-primary btn-sm skill-save-btn', 'type' => 'button']) !!}
                      </td>
                    </tr>
                </tbody>
              </table>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script type="text/javascript">
    function getMessage(message, type) {
      return '<div class="alert alert-' + type + '">' + message + '</div>';
    }
    $(document).on('click', '.add-more-btn', function (e) {      
      $(this).parents('tr').find('.select2-input').each(function () {         
        $(this).select2('destroy');
      });
      let skillRow = $(this).closest('.row');
      let skillRowClone = skillRow.clone();
      skillRowClone.find("select[name='sewing_process_id[]']").val('');
      skillRow.after(skillRowClone);
      $('.select2-input').select2();
    });

    $(document).on('click', '.del-row', function() {        
      let procesRow = $('.process-row').length;
      if (procesRow >= 2) {
        if (confirm('Do you want to delete')) {
          $(this).closest('.process-row').remove();
          $('.process-row').find('.del-skill-row').addClass('hide');
        }
      }
    });    

    $(document).on('click', '.skill-save-btn', function() {
      $('.sewing_machine_id, .sewing_process_id').empty();
      let formData = $('#assignProcessToMachine').serialize();
      showLoader()
      $.ajax({
        type: 'POST',
        url: '/process-assign-to-machines',
        data: formData,        
        success: function (response) {
          hideLoader()
          $('.js-response-message')
            .html(getMessage(response.message, 'success'))
            .fadeIn()
            .delay(2000)
            .fadeOut(2000);
          setTimeout(() => {
            window.location.href = '/process-assign-to-machines'; 
          }, 2300);
        },
        error: function (error) {
          hideLoader()
          if(error.status === 422) {
            $.each(error.responseJSON.errors, function(key, index) {            
              let inputFieldAndRow = key.split(".");  
              if(inputFieldAndRow.length == 1) {
                $('.sewing_machine_id').addClass('text-danger').html(index);
              }          
              let inputKeyName = "."+inputFieldAndRow[0];
              let rowNo = parseInt(inputFieldAndRow[1]) + 1;         
              $( "div.row:eq("+rowNo+")" ).find(inputKeyName).addClass('text-danger').html(index);
            });
          } else {
            toastr.error('Given data is invalid!');
          }
        } 
      });
    });
  </script>
@endsection