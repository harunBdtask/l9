@extends('skillmatrix::layout')

@section('title', 'Add Sewing Operator Skill')
@section('styles')
  <style type="text/css">
    .reportTable {
      margin-bottom: 150px !important;
    }
    td {
      padding-left: 10px !important;
      padding-right: 10px !important;    
    }
    .number-right {
      text-align: right;
    }
    .b-none {
      border: 1px solid transparent;
    }
  </style>
@endsection
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h2>Add Sewing Operator Skill</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <div class="text-center js-response-message"></div>
            <div class="text-center">
              <b>Operator Name:</b> {{ $sewingOperator->name }}<br/>
              <b>Operator ID:</b> {{ $sewingOperator->operator_id }}
            </div>
            <br/>
            {!! Form::open(['id' => 'addOperatorSkill', 'method' => 'post']) !!}
              {!! Form::hidden('operator_id', $sewingOperator->id, null) !!}

              <div class="col-md-4 col-md-offset-4 m-b-1">
                {!! Form::select('sewing_machine_id', $sewingMachines, $opSkill->sewing_machine_id ?? null, ['class' => 'form-control select2-input sewing-machine']) !!}
                <span class="sewing_machine_id"></span>
              </div>

              <table class="reportTable">
                <thead class="tableRow">              
                  <tr style="background-color: #5CB85C;">
                    <th width="5%">Check to Save</th>
                    <th width="25%">Process</th>
                    <th width="10%">Standard Capacity</th>
                    <th width="8%">Capacity</th>
                    <th width="8%">Efficiency(&#37;)</th>                  
                  </tr>
                </thead>
                <tbody class="formAssignForm">
                  
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
    $(document).on('change', '.sewing-machine', function (e) {
      let machineId = $(this).val();
      if (!machineId) {
        $('.formAssignForm').empty();
        return false;
      }
      let sewingOperatorId = {{ $sewingOperator->id }};    
      $.ajax({
        url: '/processes-by-machine-id/'+machineId+'/'+sewingOperatorId,
        method: "GET",
      }).done(function(response) {
        $('.formAssignForm').html(response);
      });
    });

    $(document).on('click', '.operator-skill-save-btn', function() {   
      let formData = $('#addOperatorSkill').serialize();
      showLoader()

      $.ajax({
        type: 'POST',
        url: '/sewing-operators/add-skills',
        data: formData,        
        success: function (response) {
          hideLoader()
          if (response.status == 200) {
            $('.js-response-message')
              .html(getMessage(response.message, 'success'))
              .fadeIn()
              .delay(2000)
              .fadeOut(2000);
              setTimeout(() => {
                window.location.href = '/sewing-operators';
              }, 2300);

          } else if(response.status == 403) {
            $('.js-response-message')
              .html(getMessage(response.message, 'danger'))
              .fadeIn()
              .delay(2000)
              .fadeOut(2000);
          } else {
            $('.js-response-message')
              .html(getMessage(response.message, 'danger'))
              .fadeIn()
              .delay(2000)
              .fadeOut(2000);
          }
        },
        error: function (error) {
          hideLoader()
          $.each(error.responseJSON.errors, function(key, index) {            
            let inputFieldAndRow = key.split(".");            
            let inputKeyName = "."+inputFieldAndRow[0];
            let rowNo = parseInt(inputFieldAndRow[1]) + 1;

            $( "tr:eq("+rowNo+")" ).find(inputKeyName).addClass('text-danger').html(index);
          });
        } 
      });
    });

    $(document).on('keyup change', '.s-capacity', function () {
      var capacity = $(this).val();
      var thisTr = $(this).closest('tr');
      if (isNaN(Number(capacity))) {
        alert('Capacity must be a number!!');
        $(this).val('');
        return false;
      }

      calculateEfficiency(thisTr, capacity);
    });

    function calculateEfficiency(thisTr, capacity) {
      var standardCapacity = thisTr.find('.standard-capacity').val();   
      var efficiency = 0;
      if (Number(standardCapacity) > 0) {
          efficiency = precise_round(((Number(capacity) * 100) / Number(standardCapacity)), 1);
      }       
      thisTr.find('.efficiency').html(efficiency);
    }

    function precise_round(num, dec){
      if ((typeof num !== 'number') || (typeof dec !== 'number')) {
         return false;
       }         

      var num_sign = num >= 0 ? 1 : -1;
      return (Math.round((num*Math.pow(10,dec))+(num_sign*0.0001))/Math.pow(10,dec)).toFixed(dec);
    }
  </script>
@endsection