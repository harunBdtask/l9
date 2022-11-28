<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <title>goRMG | An Ultimate ERP Solutions For Garments</title>
  <meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- for ios 7 style, multi-resolution icon of 152x152 -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
  <link rel="apple-touch-icon" href="{{ asset('flatkit/assets/images/gormg_fav_ico.png') }}">
  <meta name="apple-mobile-web-app-title" content="Flatkit">
  <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="shortcut icon" sizes="196x196" href="{{ asset('flatkit/assets/images/gormg_fav_ico.png') }}">

  <link rel='stylesheet' type='text/css' href='{{ asset('dhtmlx_scheduler/css/dhtmlxscheduler-responsive.css') }}'>

  <script src='{{ asset('dhtmlx_scheduler/js/dhtmlxscheduler-responsive.js') }}' type="text/javascript"
          charset="utf-8"></script>

  <script src='{{ asset('dhtmlx_scheduler/js/dhtmlxscheduler.js') }}' type="text/javascript"
          charset="utf-8"></script>
  <script src='{{ asset('dhtmlx_scheduler/js/dhtmlxscheduler_limit.js') }}' type="text/javascript"
          charset="utf-8"></script>
  <script src='{{ asset('dhtmlx_scheduler/js/dhtmlxscheduler_timeline.js') }}' type="text/javascript"
          charset="utf-8"></script>
  <script src='{{ asset('dhtmlx_scheduler/js/dhtmlxscheduler_tooltip.js') }}' type="text/javascript"
          charset="utf-8"></script>

  <link rel='stylesheet' type='text/css' href='{{ asset('dhtmlx_scheduler/css/dhtmlxscheduler_material.css') }}'>
  <script src='{{ asset('dhtmlx_scheduler/js/dhtmlx.js') }}' type="text/javascript"
          charset="utf-8"></script>
  <link rel='stylesheet' type='text/css' href='{{ asset('dhtmlx_scheduler/css/dhtmlx.css') }}'>

  <!-- style -->
  <link rel="stylesheet" href="{{ asset('flatkit/assets/animate.css/animate.min.css') }}" type="text/css"/>
  <link rel="stylesheet" href="{{ asset('flatkit/assets/glyphicons/glyphicons.css') }}" type="text/css"/>
  <link rel="stylesheet" href="{{ asset('flatkit/assets/font-awesome/css/font-awesome.min.css') }}" type="text/css"/>
  <link rel="stylesheet" href="{{ asset('flatkit/assets/material-design-icons/material-design-icons.css') }}"
        type="text/css"/>

  <link rel="stylesheet" href="{{ asset('flatkit/assets/bootstrap/dist/css/bootstrap.min.css') }}" type="text/css"/>
  <link rel="stylesheet"
        href="{{ asset('libs/jquery/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css') }}"
        type="text/css"/>
  <link rel="stylesheet" href="{{ asset('css/animate.css') }}" type="text/css"/>
  <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i&display=swap"
        rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet"/>

  <link rel="stylesheet" href="{{ asset('css/sewing-plan.css') }}" type="text/css"/>

  <style type="text/css">
    /* table style */
    .reportTable {
      margin-bottom: 1rem;
      width: 100%;
      max-width: 100%;
    }

    .reportTable thead,
    .reportTable tbody,
    .reportTable th {
      padding: 3px;
      font-size: 12px;
      text-align: center;
    }

    .reportTable th,
    .reportTable td {
      border: 1px solid rgba(3, 3, 31, 0.89);
    }

    #working-hour-modal-loader,
    #holiday-modal-loader,
    #load-list-modal-loader,
    #plan-create-modal-loader,
    #change-line-modal-loader,
    #split-qty-modal-loader,
    #note-entry-modal-loader,
    #lock-unlock-modal-loader,
    #loader {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(226, 226, 226, 0.75) no-repeat center center;
      color: #335CA2;
      width: 100%;
      z-index: 1000;
    }

    .spin-loader {
      position: relative;
      top: 46%;
      left: 46%;
    }

    .dhx_zoom_in_button {
      background-position: 0 0;
    }

    .dhx_zoom_in_button, .dhx_zoom_out_button {
      color: #2f5fbc;
      height: 30px;
      line-height: 30px;
      background: 0 0;
      border: 1px solid #CECECE;
      cursor: pointer;
    }

    .dhx_zoom_in_button, .dhx_zoom_out_button {
      left: auto;
      width: 46px;
      right: 61px;
      -webkit-border-top-left-radius: 5px;
      -webkit-border-bottom-left-radius: 5px;
      -moz-border-radius-topleft: 5px;
      -moz-border-radius-bottomleft: 5px;
      border-top-left-radius: 5px;
      border-bottom-left-radius: 5px;
    }

    .select2-container {
      width: 100% !important;
    }

    .select2-container .select2-selection--single {
      height: 40px;
      border-radius: 0px;
      line-height: 50px;
      border: 1px solid #e7e7e7;
    }

    .reportTable .select2-container .select2-selection--single {
      border: 1px solid #e7e7e7;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 40px;
      width: 100%;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
      top: 8px;
    }

    .error + .select2-container .select2-selection--single {
      border: 1px solid red;
    }

    .select2-container--default .select2-selection--multiple {
      min-height: 40px !important;
      border-radius: 0px;
      width: 100%;
    }

    .parentTableFixed {
      height: 150px !important;
    }

    .width-5p {
      width: 5% !important;
    }

    .width-10p {
      width: 10% !important;
    }

    .width-15p {
      width: 15% !important;
    }

    .width-20p {
      width: 20% !important;
    }

    .width-25p {
      width: 25% !important;
    }

    .width-30p {
      width: 30% !important;
    }

    .width-33p {
      width: 33% !important;
    }

    .width-40p {
      width: 40% !important;
    }

    .width-50p {
      width: 50% !important;
    }

    .width-75p {
      width: 75% !important;
    }

    .width-80p {
      width: 80% !important;
    }

    .width-90p {
      width: 90% !important;
    }

    .width-100p {
      width: 100% !important;
    }

  </style>

</head>
<body class="body">
<div class="header">
  <div class="sub-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <a href="{{ url('/') }}" class="btn btn-sm logo">
            <img src="{{ asset('flatkit/assets/images/gormg_fav_ico.png') }}" alt="cut plan board"
                 style="height: 30px;">
            <span class="p app-header" style="font-weight: 400; word-spacing: 0.2em; letter-spacing: 1px">SEWING <span
                  class="p" style="font-weight: 700;">PLANNING</span> BOARD</span>
          </a>
        </div>
        <div class="col-sm-6">
          <div class="pull-right lock-buttons-div" style="padding-top: 0.23rem;">
            <button type="button" class="instructionBtn btn btn-sm btn-primary"
                    title="Instruction" data-toggle="modal" data-target="#instructionModal">
              Instruction
            </button>
            <button type="button" class="workingHourUpdateBtn btn btn-sm btn-primary"
                    title="Working Hour Update" data-toggle="modal" data-target="#workingHourUpdateModal">
              W.H Update
            </button>
            <button type="button" class="holidayUpdateBtn btn btn-sm btn-primary" title="Holiday">Holiday
            </button>
            <button type="button" class="loadListBtn btn btn-sm btn-primary" title="Load List">Load List
            </button>
            <button type="button" class="undo-btn btn btn-sm btn-primary" title="Undo"><i
                  class="fa fa-undo"></i></button>
            <button type="button" class="redo-btn btn btn-sm btn-primary" title="Redo"><i
                  class="fa fa-repeat"></i></button>
            {{--<button type="button" class="reports-btn btn btn-sm btn-primary" title="Reports">Reports</button>--}}
            <button type="button" class="lock-btn btn btn-sm btn-success hide" title="Lock Board"><i
                  class="fa fa-unlock"></i></button>
            <button type="button" class="unlock-btn btn btn-sm btn-danger hide" title="Unlock Board"><i
                  class="fa fa-lock"></i></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="main-section">
  <div id="scheduler_here" class="dhx_cal_container" style="width: 100%; height: 100%;">
    <div class="dhx_cal_navline">
      <div class="dhx_zoom_in_button" style="right: 269px !important; left:auto !important" onclick="zoomIn()">
        &nbsp;<i style="font-size: 15px;padding-left: .7rem;" class="fa fa-search-plus"></i>&nbsp;
      </div>
      <div class="dhx_zoom_out_button" style="right: 222px !important; left:auto !important" onclick="zoomOut()">
        &nbsp;<i style="font-size: 15px;padding-left: .7rem;" class="fa fa-search-minus"></i>&nbsp;
      </div>
      <div class="dhx_cal_prev_button">&nbsp;</div>
      <div class="dhx_cal_next_button">&nbsp;</div>
      <div class="dhx_cal_today_button"></div>
      <div class="dhx_cal_date"></div>
      {{--<div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
      <div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
      <div class="dhx_cal_tab" name="timeline_tab" style="right:280px;"></div>
      <div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>--}}
    </div>
    <div class="dhx_cal_header">
    </div>
    <div class="dhx_cal_data">
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="workingHourUpdateModal" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Working Hour Update</h5>
      </div>
      {!! Form::open(['url' => 'sewing-section-working-hour-update', 'id' => 'working_hour_update_form', 'method' => 'POST']) !!}
      <div class="modal-body">
        <div class="wh-flash-message">
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-sm-4">
              <label>Year</label>
              {!! Form::selectYear('year', 2100, 1970, null, ['class' => 'form-control form-control-sm', 'id' => 'wh_year', 'placeholder' => 'Select a Year']) !!}
              <span class="text-danger year"></span>
            </div>
            <div class="col-sm-4">
              <label>Month</label>
              {!! Form::selectMonth('month', null, ['class' => 'form-control form-control-sm', 'id' => 'wh_month', 'placeholder' => 'Select a Month']) !!}
              <span class="text-danger month"></span>
            </div>
          </div>
          <div class="row table-responsive" style="max-height: 300px; margin-top: 20px;">
            <div class="col-md-12 workingHourUpdateTable">

            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm white m-b" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      {!! Form::close() !!}
      <div id="working-hour-modal-loader">
        <div class="text-center spin-loader"><i
              class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-lg" id="holidayUpdateModal" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content holidayUpdateModalContent">

    </div>
    <div id="holiday-modal-loader">
      <div class="text-center spin-loader"><i
            class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-lg" id="loadListModal" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content loadListModalContent">

    </div>
    <div id="load-list-modal-loader">
      <div class="text-center spin-loader"><i
            class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-lg" id="planCreateModal" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content planCreateModalContent">

    </div>
    <div id="plan-create-modal-loader">
      <div class="text-center spin-loader"><i
            class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
    </div>
  </div>
</div>

<div class="modal fade" id="orderDetailsModal" role="dialog"
     aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content orderDetailsModalContent">

    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-lg" id="splitQtyModal" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content splitQtyModalContent">

    </div>
    <div id="split-qty-modal-loader">
      <div class="text-center spin-loader"><i
            class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-lg" id="sewingPlanChangeLineModal" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content sewingPlanChangeLineModalContent">

    </div>
    <div id="change-line-modal-loader">
      <div class="text-center spin-loader"><i
            class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-lg" id="productDetailsModal" aria-labelledby="myLargeModalLabel"
     role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content productDetailsModalContent">
      <div class="modal-header">
        <h5 class="modal-title">Product Details</h5>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-8">
            <div class="row">
              <div class="col-sm-6">
                <img src="" alt="Product" class="img-thumbnail" style="width: 100%; height: auto;">
                <p>Product</p>
              </div>
              <div class="col-sm-6">
                <div class="col-sm-12">
                  <img src="" alt="Sample" class="img-thumbnail" style="width: 100%; height: auto;">
                  <p>Sample</p>
                </div>
                <div class="col-sm-12">
                  <img src="" alt="Thread Sample" class="img-thumbnail"
                       style="width: 100%; height: auto;">
                  <p>Thread Sample</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <h5>List of Trims &amp; Accessories</h5>
            <ol>
              <li>No Data found</li>
            </ol>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm white m-b" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="sewingPlanNoteModal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Note Entry</h5>
      </div>
      {!! Form::open(['url' => '' , 'method' => 'POST', 'id' => 'planNoteEntryForm']) !!}
      <div class="modal-body">
        <div class="note-entry-flash-message">
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-sm-12">
              <label for="notes">Notes</label>
              {!! Form::textarea('notes', null, ['class' => 'form-control form-control-sm', 'id' => 'notes', 'placeholder' => 'Enter your notes', 'rows' => 4, 'maxlength' => '191']) !!}
              <span class="text-danger notes"></span>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary pull-left">Update</button>
        <button type="button" class="btn btn-sm white m-b" data-dismiss="modal">Close</button>
      </div>
      {!! Form::close() !!}
      <div id="note-entry-modal-loader">
        <div class="text-center spin-loader"><i
              class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="lockUnlockModal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content lockUnlockModalContent">

    </div>
    <div id="lock-unlock-modal-loader">
      <div class="text-center spin-loader"><i
            class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
    </div>
  </div>
</div>

<div class="modal fade" id="sewingPlanUnloadModal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Unload Plan</h5>
      </div>
      {!! Form::open(['url' => '' , 'method' => 'delete', 'id' => 'deleteSewingPlanForm']) !!}
      <div class="modal-body">
        <p>Are you sure you want to unload this plan?</p>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger pull-left">Yes</button>
        <button type="button" class="btn btn-sm white m-b" data-dismiss="modal">Close</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-lg" id="instructionModal" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content instructionModalContent">
      <div class="modal-header">
        <h5 class="modal-title">Instruction</h5>
      </div>
      <div class="modal-body table-responsive" style="height: 415px;">
        <div class="row">
          <div class="col-sm-12">
            <h6>Plan Strip Color</h6>
          </div>
          <div class="col-sm-12">
            <table class="reportTable">
              <thead>
              <tr>
                <th class="width-15p">Conditions</th>
                <th class="width-15p">Strip Color</th>
              </tr>
              </thead>
              <tbody>
              <tr>
                <td>Progress &#8804;50&#37;<sup class="text-danger">*</sup></td>
                <td><span class="instruction-plan-color" style="background-color: #8E0000;" title="&#8804;50&#37;"></span></td>
              </tr>
              <tr>
                <td>Progress >50&#37; to &#8804;70&#37;<sup class="text-danger">*</sup></td>
                <td><span class="instruction-plan-color" style="background-color: #e7b52e;" title=">50&#37; to &#8804;70&#37;"></span></td>
              </tr>
              <tr>
                <td>Progress >70&#37;<sup class="text-danger">*</sup></td>
                <td><span class="instruction-plan-color" style="background-color: #08ab09;" title=">70&#37;"></span></td>
              </tr>
              <tr>
                <td>Sewing end date excced.</td>
                <td><span class="instruction-plan-color" style="background-color: #df0000;" title="Shuipment Date over"></span></td>
              </tr>
              <tr>
                <td>If no other condition matches</td>
                <td><span class="instruction-plan-color" style="background-color: #78909c;" title="Default"></span></td>
              </tr>
              </tbody>
              <tfoot>
              <tr>
                <th colspan="2"><sup class="text-danger">*</sup> Progress related colors will be shown on the day of plan start date</th>
              </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm white m-b" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- jQuery -->
<script src="{{ asset('libs/jquery/jquery/dist/jquery.js') }}"></script>
<script src="{{ asset('libs/jquery/tether/dist/js/tether.min.js') }}"></script>
<script src="{{ asset('libs/jquery/bootstrap/dist/js/bootstrap.js') }}"></script>
<script src="{{ asset('libs/jquery/moment/moment.js') }}"></script>
<script
    src="{{ asset('libs/jquery/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('libs/jquery/select2/dist/js/select2.js')  }}"></script>
<!-- ajax -->
<script src="{{ asset('flatkit/scripts/ajax.js') }}"></script>
<!-- Table Head Fixer -->
<script src="{{ asset('modules/skeleton/flatkit/assets/table_head_fixer/tableHeadFixer.js') }}"></script>

<script type="text/javascript">

  var factoryId = '{{ factoryId() }}';
  var userId = '{{ userId() }}';


  $(function () {
    tableHeadFixer();
    getLinesData();
    setSelect2();
  });

  function tableHeadFixer() {
    $(document).find(".fixTable").tableHeadFixer();
  }

  function setSelect2() {
    $('select').select2();

    $('#wh_year,#wh_month').select2({
      dropdownParent: $('#workingHourUpdateModal')
    });

    $('#load-list-buyer-id,#load-list-order-id,#load-list-garments-item-id').select2({
      dropdownParent: $('#loadListModal')
    });

    $('#sewing-plan-floor-id,#sewing-plan-line-id').select2({
      dropdownParent: $('#planCreateModal')
    });

    $('#split-sewing-plan-floor-id,#split-sewing-plan-line-id').select2({
      dropdownParent: $('#splitQtyModal')
    });

    $('#change-line-sewing-plan-floor-id,#change-line-sewing-plan-line-id').select2({
      dropdownParent: $('#sewingPlanChangeLineModal')
    });
  }

  function scrollToTopModalBody() {
    var modalBody = $('.modal-body');
    modalBody.animate({scrollTop: 0}, "slow");
  }

  function getLinesData() {
    $.ajax({
      url: '/get-lines-for-sewing-plan',
      type: 'GET'
    }).done(function (response) {
      createPlanningBoardView(response);
    });
  }

  /* Working Hour Update related js start */
  $('#workingHourUpdateModal').modal({
    keyboard: false,
    backdrop: false,
    show: false
  });

  $(document).on('click', '.workingHourUpdateBtn', function () {
    $('#wh_month').val('');
    $('#wh_year').val('');
    $('.workingHourUpdateTable').html('');
    $('#workingHourUpdateModal').modal({
      keyboard: false,
      backdrop: false,
      show: true
    });
  });

  $(document).on('submit', '#working_hour_update_form', function (e) {
    e.preventDefault();
    $('.text-danger').html('');
    var wh_month = $('#wh_month').val();
    var wh_year = $('#wh_year').val();
    let flashMessageDom = $('.wh-flash-message');
    var loader = $('#working-hour-modal-loader');
    //flashMessageDom.html('');
    if (wh_month && wh_year) {
      var form = $(this);
      loader.show();
      $.ajax({
        type: form.attr('method'),
        url: form.attr('action'),
        data: form.serialize(),
        success: function (response) {
          loader.hide();
          if (response.status === 'error') {
            $.each(response.errors, function (errorIndex, errorValue) {
              let errorDomElement, error_index, errorMessage;
              errorDomElement = '' + errorIndex;
              errorDomIndexArray = errorDomElement.split(".");
              errorDomElement = '.' + errorDomIndexArray[0];
              error_index = errorDomIndexArray[1];
              errorMessage = errorValue[0];
              if (errorDomIndexArray.length == 1) {
                $(errorDomElement).html(errorMessage);
              } else {
                $(".wh-table-body tr:eq(" + error_index + ")").find(errorDomElement).html(errorMessage);
              }
            });
          }
          if (response.status === 'success') {
            flashMessageDom.html(response.message);
            flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
            closeWorkingHourUpdateModal();
          }

          if (response.status === 'danger') {
            flashMessageDom.html(response.message);
            flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
          }
        }
      });
    } else {
      alert("Please Select Year and Month");
      return false;
    }
  });

  function closeWorkingHourUpdateModal() {
    setTimeout(function () {
      $('#workingHourUpdateModal').modal('hide');
      $('#wh_month').val('').change();
      $('#wh_year').val('').change();
    }, 3000);
  }

  $(document).on('change', '#wh_year', function () {
    var wh_year = $(this).val();
    var wh_month = $('#wh_month').val();
    var whDateHtml = $('.workingHourUpdateTable');
    var loader = $('#working-hour-modal-loader');
    whDateHtml.html('');
    if (wh_year && wh_month) {
      loader.show();
      $.ajax({
        url: '/get-sewing-working-hours-date-form/' + wh_year + '/' + wh_month,
        type: 'GET'
      }).done(function (response) {
        setTimeout(function () {
          loader.hide();
          whDateHtml.html(response.html);
        }, 3000);
      });
    } else {
      return false;
    }
  });

  $(document).on('change', '#wh_month', function () {
    var wh_month = $(this).val();
    var wh_year = $('#wh_year').val();
    var whDateHtml = $('.workingHourUpdateTable');
    var loader = $('#working-hour-modal-loader');
    whDateHtml.html('');
    if (wh_year && wh_month) {
      loader.show();
      $.ajax({
        url: '/get-sewing-working-hours-date-form/' + wh_year + '/' + wh_month,
        type: 'GET'
      }).done(function (response) {
        loader.hide();
        whDateHtml.html(response.html);
      });
    } else {
      $(this).val('');
      return false;
    }
  });
  /* Working Hour Update related js end */

  /* Holiday related Js Start */
  $(document).on('click', '.holidayUpdateBtn', function () {
    var holidayUpdateModalContentDom = $('.holidayUpdateModalContent');
    var holidayLoader = $('#holiday-modal-loader');
    holidayUpdateModalContentDom.html('');
    getHolidaysList(holidayUpdateModalContentDom);
    setTimeout(openHolidayUpdateModal(), 2000);
  });

  function getHolidaysList(holidayUpdateModalContentDom) {
    $.ajax({
      type: 'GET',
      url: '/sewing-holidays'
    }).done(function (response) {
      holidayUpdateModalContentDom.html(response.html);
    });
  }

  function openHolidayUpdateModal() {
    $("#holidayUpdateModal").modal({
      keyboard: false,
      backdrop: false,
      show: true
    });
  }

  $('#holidayUpdateModal').on('hidden.bs.modal', function (e) {
    $.ajax({
      type: "GET",
      url: "/get-sewing-holidays",
    }).done(function (response) {
      scheduler.deleteMarkedTimespan();
      updateHolidaysInScheduler(response.sewing_holidays);
    });
  });

  function updateHolidaysInScheduler(sewing_holidays) {
    setHolidayTimeSpanCss(sewing_holidays);
    scheduler.updateView();
  }

  $(document).on('click', '.holidayListTableBody .pagination a', function (event) {
    event.preventDefault();

    $('li').removeClass('active');
    $(this).parent('li').addClass('active');

    var myurl = $(this).attr('href');
    var page = $(this).attr('href').split('page=')[1];

    getHolidayPaginationData(page);
  });

  function getHolidayPaginationData(page) {
    var holidayUpdateModalContentDom = $('.holidayUpdateModalContent');
    var holidayLoader = $('#holiday-modal-loader');
    holidayLoader.show();
    $.ajax({
      url: '/sewing-holidays?page=' + page,
      type: "get",
      datatype: "html"
    }).done(function (response) {
      holidayLoader.hide();
      holidayUpdateModalContentDom.html('');
      holidayUpdateModalContentDom.html(response.html);
    }).fail(function (jqXHR, ajaxOptions, thrownError) {
      holidayLoader.hide();
      alert('No response from server');
    });
  }

  $(document).on('click', '.newHolidayEntryBtn', function () {
    var holidayUpdateModalContentDom = $('.holidayUpdateModalContent');
    var holidayLoader = $('#holiday-modal-loader');
    holidayLoader.show();
    $.ajax({
      type: 'GET',
      url: '/sewing-holidays/create'
    }).done(function (response) {
      holidayLoader.hide();
      holidayUpdateModalContentDom.html('');
      holidayUpdateModalContentDom.html(response.html);
    });
  });

  $(document).on('click', '.add-new-holiday-row', function () {
    var thisHtml = $(this);
    var tableBodyDom = $('.holidayCreateTableBody');
    var thisTrClone = thisHtml.parents('tr').clone();
    var table_row = $('.holidayCreateTableRow');
    tableBodyDom.append(thisTrClone);

    if (table_row.length > 0) {
      thisHtml.parents('tr').find('.add-new-holiday-row').addClass('hide');
      thisHtml.parents('tr').find('.remove-holiday-row').removeClass('hide');
      thisHtml.parents('tr').next('tr').find('.remove-holiday-row').removeClass('hide');
    }
  });

  $(document).on('click', '.remove-holiday-row', function () {
    var thisHtml = $(this);
    var table_row = $('.holidayCreateTableRow');

    thisHtml.parents('.holidayCreateTableRow').remove();
    $(".holidayCreateTableBody tr:eq(" + (table_row.length - 2) + ")").find('.add-new-holiday-row').removeClass('hide');
    if (table_row.length <= 2) {
      $(".add-new-holiday-row").removeClass('hide');
      $(".remove-holiday-row").addClass('hide');
    }
  });

  $(document).on('submit', '#holidayCreateForm', function (e) {
    e.preventDefault();
    var form = $(this);
    var flashMessageDom = $('.holiday-flash-message');
    var holidayUpdateModalContentDom = $('.holidayUpdateModalContent');
    $('.text-danger').html('');
    var holidayLoader = $('#holiday-modal-loader');
    holidayLoader.show();
    $.ajax({
      type: form.attr('method'),
      url: form.attr('action'),
      data: form.serialize()
    }).done(function (response) {
      holidayLoader.hide();
      if (response.status === 'error') {
        $.each(response.errors, function (errorIndex, errorValue) {
          let errorDomElement, error_index, errorMessage;
          errorDomElement = '' + errorIndex;
          errorDomIndexArray = errorDomElement.split(".");
          errorDomElement = '.' + errorDomIndexArray[0];
          error_index = errorDomIndexArray[1];
          errorMessage = errorValue[0];
          if (errorDomIndexArray.length == 1) {
            $(errorDomElement).html(errorMessage);
          } else {
            $(".holidayCreateTableBody tr:eq(" + error_index + ")").find(errorDomElement).html(errorMessage);
          }
        });
      }
      if (response.status === 'success') {
        flashMessageDom.html(response.message);
        flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
        getHolidaysList(holidayUpdateModalContentDom);
      }

      if (response.status === 'danger') {
        flashMessageDom.html(response.message);
        flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
      }
    });
  });

  $(document).on('click', '.holidayEditBtn', function () {
    var id = $(this).attr('data-id');
    var holidayUpdateModalContentDom = $('.holidayUpdateModalContent');
    var holidayLoader = $('#holiday-modal-loader');
    if (id) {
      holidayLoader.show();
      $.ajax({
        type: 'GET',
        url: '/sewing-holidays/' + id + '/update'
      }).done(function (response) {
        holidayLoader.hide();
        holidayUpdateModalContentDom.html('');
        holidayUpdateModalContentDom.html(response.html);
      });
    }
  });

  $(document).on('submit', '#holidayUpdateForm', function (e) {
    e.preventDefault();
    var form = $(this);
    var flashMessageDom = $('.holiday-flash-message');
    var holidayUpdateModalContentDom = $('.holidayUpdateModalContent');
    $('.text-danger').html('');
    var holidayLoader = $('#holiday-modal-loader');
    holidayLoader.show();
    $.ajax({
      type: form.attr('method'),
      url: form.attr('action'),
      data: form.serialize()
    }).done(function (response) {
      holidayLoader.hide();
      if (response.status === 'error') {
        $.each(response.errors, function (errorIndex, errorValue) {
          let errorDomElement, error_index, errorMessage;
          errorDomElement = '' + errorIndex;
          errorDomIndexArray = errorDomElement.split(".");
          errorDomElement = '.' + errorDomIndexArray[0];
          error_index = errorDomIndexArray[1];
          errorMessage = errorValue[0];
          if (errorDomIndexArray.length == 1) {
            $(errorDomElement).html(errorMessage);
          } else {
            $(".holidayCreateTableBody tr:eq(" + error_index + ")").find(errorDomElement).html(errorMessage);
          }
        });
      }
      if (response.status === 'success') {
        flashMessageDom.html(response.message);
        flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
        getHolidaysList(holidayUpdateModalContentDom);
      }

      if (response.status === 'danger') {
        flashMessageDom.html(response.message);
        flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
      }
    });
  });

  $(document).on('click', '.holidayDeleteBtn', function () {
    var id = $(this).attr('data-id');
    var token = $('meta[name="csrf-token"]').attr('content');
    var holidayUpdateModalContentDom = $('.holidayUpdateModalContent');
    var holidayLoader = $('#holiday-modal-loader');
    var flashMessageDom = $('.holiday-flash-message');
    var confirmDelete = confirm('Are you sure you want to delete?');
    if (id && confirmDelete) {
      holidayLoader.show();
      $.ajax({
        type: 'DELETE',
        url: '/sewing-holidays/' + id,
        data: {
          _token: token,
          id: id
        }
      }).done(function (response) {
        holidayLoader.hide();
        if (response.status === 'success') {
          flashMessageDom.html(response.message);
          flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
          setTimeout(function () {
            holidayUpdateModalContentDom.html('');
            getHolidaysList(holidayUpdateModalContentDom);
          }, 2000)
        }

        if (response.status === 'danger') {
          flashMessageDom.html(response.message);
          flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
        }
      });
    }
  });

  $(document).on('submit', '#holidaySearchForm', function (e) {
    e.preventDefault();
    var form = $(this);
    var holidayUpdateModalContentDom = $('.holidayUpdateModalContent');
    var holidayLoader = $('#holiday-modal-loader');
    holidayLoader.show();
    $.ajax({
      type: form.attr('method'),
      url: form.attr('action'),
      data: form.serialize()
    }).done(function (response) {
      holidayLoader.hide();
      holidayUpdateModalContentDom.html('');
      holidayUpdateModalContentDom.html(response.html);
    });
  });
  /* Holiday related Js End */

  /* Load List Related Js Start */
  $(document).on('click', '.loadListBtn', function () {
    $.ajax({
      type: 'GET',
      url: '/get-load-list-modal-content'
    }).done(function (response) {
      $('.loadListModalContent').html(response.html);
      openLoadListModal();
      setSelect2();
    });
  });

  function openLoadListModal() {
    $('#loadListModal').modal({
      keyboard: false,
      backdrop: false,
      show: true
    });
  }

  function closeLoadListModal() {
    $('#loadListModal').modal('hide');
  }

  $(document).on('change', '.loadListModalContent select[name="buyer_id"]', function () {
    var buyer_id = $(this).val();
    var styleDropdownDom = $('#load-list-order-id');
    var garmentsItemDropdownDom = $('#load-list-garments-item-id');
    garmentsItemDropdownDom.empty();
    var itemDropdown = '<option value="">Select Item</option>';
    garmentsItemDropdownDom.html(itemDropdown);
    garmentsItemDropdownDom.val('').select2();
    var loadListTableDom = $('.loadListTable');
    loadListTableDom.html('');
    if (buyer_id) {
      $.ajax({
        type: 'GET',
        url: '/utility/get-styles-by-buyer/' + buyer_id,
      }).done(function (response) {
        var styleNameDropdown = '<option value="">Select Style/Order</option>';
        if (Object.keys(response.data).length > 0) {
          $.each(response.data, function (index, data) {
            styleNameDropdown += '<option value="' + index + '">' + data + '</option>';
          });
        }
        styleDropdownDom.html(styleNameDropdown);
        styleDropdownDom.val('').select2();
      });
    }
  });

  $(document).on('change', '.loadListModalContent select[name="order_id"]', function () {
    var order_id = $(this).val();
    var loadListTableDom = $('.loadListTable');
    loadListTableDom.html('');
    var garmentsItemDropdownDom = $('#load-list-garments-item-id');
    garmentsItemDropdownDom.empty();

    if (order_id) {
      $.ajax({
        type: 'GET',
        url: '/utility/get-items-by-order/' + order_id,
      }).done(function (response) {
        var itemDropdown = '<option value="">Select Item</option>';
        if (Object.keys(response.data).length > 0) {
          $.each(response.data, function (index, data) {
            itemDropdown += '<option value="' + index + '">' + data + '</option>';
          });
        }
        garmentsItemDropdownDom.html(itemDropdown);
        garmentsItemDropdownDom.val('').select2();
      });
    }
  });

  $(document).on('submit', '#loadListForm', function (e) {
    e.preventDefault();
    var form = $(this);
    var flashMessageDom = $('.load-list-flash-message');
    var loadListTableDom = $('.loadListTable');
    loadListTableDom.html('');
    $('.text-danger').html('');
    var loadListLoader = $('#load-list-modal-loader');
    loadListLoader.show();
    $.ajax({
      type: form.attr('method'),
      url: form.attr('action'),
      data: form.serialize()
    }).done(function (response) {
      loadListLoader.hide();
      if (response.status === 'error') {
        $.each(response.errors, function (errorIndex, errorValue) {
          let errorDomElement, error_index, errorMessage;
          errorDomElement = '' + errorIndex;
          errorDomIndexArray = errorDomElement.split(".");
          errorDomElement = '.' + errorDomIndexArray[0];
          error_index = errorDomIndexArray[1];
          errorMessage = errorValue[0];
          if (errorDomIndexArray.length == 1) {
            $(errorDomElement).html(errorMessage);
          }
        });
      }
      if (response.status === 'success') {
        loadListTableDom.html(response.html);
        tableHeadFixer();
      }

      if (response.status === 'danger') {
        flashMessageDom.html(response.message);
        flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
      }
    });
  });
  /* Load List Related Js End */

  /* Create Plan Start */
  $(document).on('submit', '#sewing-plan-po-selection-form', function (e) {
    e.preventDefault();
    var form = $(this);
    var flashMessageDom = $('.load-list-flash-message');
    var planCreateModalContentDom = $('.planCreateModalContent');
    var loadListLoader = $('#load-list-modal-loader');

    $('.text-danger').html('');
    loadListLoader.show();
    $.ajax({
      type: form.attr('method'),
      url: form.attr('action'),
      data: form.serialize()
    }).done(function (response) {
      loadListLoader.hide();

      if (response.status == 'success') {
        planCreateModalContentDom.html('');
        planCreateModalContentDom.html(response.html);
        closeLoadListModal();
        setTimeout(openCreatePlanModal(), 3000);
        tableHeadFixer();
      }

      if (response.status == 'danger') {
        flashMessageDom.html(response.message);
        flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
      }
    }).fail(function (response) {
      loadListLoader.hide();
      $.each(response.responseJSON.errors, function (errorIndex, errorValue) {
        let errorDomElement, error_index, errorMessage;
        errorDomElement = '' + errorIndex;
        errorDomIndexArray = errorDomElement.split(".");
        errorDomElement = '.' + errorDomIndexArray[0];
        error_index = errorDomIndexArray[1];
        errorMessage = errorValue[0];
        if (errorDomIndexArray.length == 1) {
          $(errorDomElement).html(errorMessage);
        } else {
          $("tbody tr:eq(" + error_index + ")").find(errorDomElement).html(errorMessage);
        }
      });
    });
  });

  function openCreatePlanModal() {
    $('#planCreateModal').modal({
      keyboard: false,
      backdrop: false,
      show: true
    });
    setSelect2();
  }

  function closeCreatePlanModal() {
    $('#planCreateModal').modal('hide');
  }

  $(document).on('change', '#planCreateModal select[name="floor_id"]', function () {
    var floor_id = $(this).val();
    $('span.floor_id').html('');
    var lineDropdownDom = $('#planCreateModal select[name="line_id"]');
    lineDropdownDom.empty();
    var planManpowerDom = $('#sewing-plan-manpower');
    var planWorkingHourDom = $('#sewing-plan-working-hour');
    var planCapacityDom = $('#sewing-plan-capacity');
    var planCapacityInputDom = $('#planCreateModal input[name="capacity_pcs"]');
    var planEfficiencyDom = $('#sewing-plan-efficiency');
    var allocatedQtyDom = $('#planCreateModal input[name="allocated_qty"]');
    var startDateDom = $('#planCreateModal input[name="start_date"]');
    var startDateTimeDom = $('#planCreateModal input[name="start_time"]');
    var endDateDom = $('#planCreateModal input[name="end_date"]');
    var endDateTimeDom = $('#planCreateModal input[name="end_time"]');
    planManpowerDom.html('');
    planWorkingHourDom.html('');
    planCapacityDom.html('');
    planCapacityInputDom.val('');
    planEfficiencyDom.html('');
    allocatedQtyDom.val('');
    startDateDom.val('');
    startDateTimeDom.val('');
    endDateDom.val('');
    endDateTimeDom.val('');
    if (floor_id) {
      $.ajax({
        type: 'GET',
        url: '/get-lines/' + floor_id,
      }).done(function (response) {
        var linesDropdown = '<option value="">Select Line</option>';
        if (Object.keys(response.data).length > 0) {
          $.each(response.data, function (index, line) {
            linesDropdown += '<option value="' + line.id + '">' + line.line_no + '</option>';
          });
        }
        lineDropdownDom.html(linesDropdown);
        lineDropdownDom.val('').select2();
      });
    }
  });

  $(document).on('change', '#planCreateModal select[name="line_id"]', function () {
    var line_id = $(this).val();
    $('span.line_id').html('');
    var smv = $('#planCreateModal input[name="smv"]').val();
    var planManpowerDom = $('#sewing-plan-manpower');
    var planWorkingHourDom = $('#sewing-plan-working-hour');
    var planCapacityDom = $('#sewing-plan-capacity');
    var planCapacityMinDom = $('#sewing-plan-capacity-min');
    var planCapacityInputDom = $('#planCreateModal input[name="capacity_pcs"]');
    var planEfficiencyDom = $('#sewing-plan-efficiency');
    var masterAllocatedQtyDom = $('#planCreateModal input[name="master_allocated_qty"]');
    var startDateDom = $('#planCreateModal input[name="start_date"]');
    var startDateTimeDom = $('#planCreateModal input[name="start_time"]');
    var endDateDom = $('#planCreateModal input[name="end_date"]');
    var endDateTimeDom = $('#planCreateModal input[name="end_time"]');
    planManpowerDom.html('');
    planWorkingHourDom.html('');
    planCapacityDom.html('');
    planCapacityInputDom.val('');
    planEfficiencyDom.html('');
    //masterAllocatedQtyDom.val('');
    startDateDom.val('');
    startDateTimeDom.val('');
    endDateDom.val('');
    endDateTimeDom.val('');
    if (line_id && smv) {
      $.ajax({
        type: 'GET',
        url: '/get-line-capacity-information/' + line_id + '/' + smv,
      }).done(function (response) {
        planManpowerDom.html(response.manpower);
        planWorkingHourDom.html(response.working_hour);
        planCapacityMinDom.html(response.line_capacity_min);
        planCapacityDom.html(response.line_capacity);
        planCapacityInputDom.val(response.line_capacity);
        planEfficiencyDom.html(response.line_efficiency);
      });
    }
  });

  $(document).on('keyup', '#planCreateModal input[name="allocated_qty[]"]', function () {
    var allocated_qty = Number($(this).val());
    $(this).parents('tr').find('span.allocated_qty').html('');
    var remaining_plan_qty = Number($(this).parents('tr').find('input[name="remaining_plan_qty[]"]').val());
    var remainingPlanQtyDom = $(this).parents('tr').find('span.remaining_plan_qty');
    var po_qty = Number($(this).parents('tr').find('input[name="po_quantity[]"]').val());

    var master_allocated_qty = $('#planCreateModal input[name="master_allocated_qty"]');
    var remaining_master_plan_qty = $('#planCreateModal input[name="remaining_master_plan_qty"]').val();
    var remainingMasterPlanQtyDom = $('#planCreateModal span.remaining_master_plan_qty');
    var sub_total_po_qty = $('#planCreateModal input[name="sub_total_po_qty"]').val();
    $('#planCreateModal span.master_allocated_qty').html('');

    if (/^[0-9]*$/.test($(this).val()) == false) {
      alert("You have used the illegal character!");
      $(this).val(remaining_plan_qty);
      remainingPlanQtyDom.html(0);
      master_allocated_qty.val(remaining_master_plan_qty);
      remainingMasterPlanQtyDom.html(0);
      return false;
    }
    if (allocated_qty < 0) {
      alert("Allocated quantity cannot be negative!");
      $(this).val(remaining_plan_qty);
      remainingPlanQtyDom.html(0);
      master_allocated_qty.val(remaining_master_plan_qty);
      remainingMasterPlanQtyDom.html(0);
      return false;
    }
    if (!Number.isInteger(allocated_qty)) {
      alert("Allocated quantity must be of integer type!!");
      $(this).val(remaining_plan_qty);
      remainingPlanQtyDom.html(0);
      master_allocated_qty.val(remaining_master_plan_qty);
      remainingMasterPlanQtyDom.html(0);
      return false;
    }

    if (allocated_qty > remaining_plan_qty) {
      alert("Allocated quantity cannot be greater than remaining PO Qty!");
      $(this).val(remaining_plan_qty);
      remainingPlanQtyDom.html(0);
      master_allocated_qty.val(remaining_master_plan_qty);
      remainingMasterPlanQtyDom.html(0);
      return false;
    }

    var temp_sub_total_allocated_qty = 0;
    $('#plan-create-po-details-table-body tr').each(function () {
      var po_wise_allocated_qty = $(this).find('td input[name="allocated_qty[]"]')[0].value;
      if (!po_wise_allocated_qty) {
        po_wise_allocated_qty = 0;
      }
      temp_sub_total_allocated_qty += Number(po_wise_allocated_qty);
    });
    master_allocated_qty.val(temp_sub_total_allocated_qty);
    remainingMasterPlanQtyDom.html(remaining_master_plan_qty - temp_sub_total_allocated_qty);

    var startDateDom = $('#planCreateModal input[name="start_date"]');
    var startDateTimeDom = $('#planCreateModal input[name="start_time"]');
    var endDateDom = $('#planCreateModal input[name="end_date"]');
    var endDateTimeDom = $('#planCreateModal input[name="end_time"]');

    startDateDom.val('');
    startDateTimeDom.val('');
    endDateDom.val('');
    endDateTimeDom.val('');

    remainingPlanQtyDom.html(remaining_plan_qty - allocated_qty);
    return false;

  });

  $(document).on('keyup change', '#planCreateModal input[name="start_date"]', function () {
    var start_date = $(this).val();
    var date_format = new Date(start_date);
    var smv = $('#planCreateModal input[name="smv"]').val();
    var line_id = $('#planCreateModal select[name="line_id"]').val();
    var allocated_qty = $('#planCreateModal input[name="master_allocated_qty"]').val();
    var startTimeDom = $('#planCreateModal input[name="start_time"]');
    var endDateDom = $('#planCreateModal input[name="end_date"]');
    var endTimeDom = $('#planCreateModal input[name="end_time"]');
    var requiredSecondsDom = $('#planCreateModal input[name="required_seconds"]');
    $('.text-danger').html('');
    if (!line_id) {
      alert("Please Select Line!!");
      $(this).val('');
      return false;
    }
    if (!allocated_qty || allocated_qty <= 0) {
      alert("Please enter allocated qty!!");
      $(this).val('');
      return false;
    }
    startTimeDom.val('');
    endDateDom.val('');
    endTimeDom.val('');
    requiredSecondsDom.val('');
    // check for valid date && year above 2018
    if (date_format && date_format.getFullYear() > 2018) {
      $.ajax({
        type: "GET",
        url: "/get-end-date-time-for-plan/" + start_date + '/' + smv + '/' + line_id + '/' + allocated_qty
      }).done(function (response) {
        if (response.status === 'error') {
          $.each(response.errors, function (errorIndex, errorValue) {
            let errorDomElement, error_index, errorMessage;
            errorDomElement = '' + errorIndex;
            errorDomIndexArray = errorDomElement.split(".");
            errorDomElement = '.' + errorDomIndexArray[0];
            error_index = errorDomIndexArray[1];
            errorMessage = errorValue[0];
            if (errorDomIndexArray.length == 1) {
              $(errorDomElement).html(errorMessage);
            } else {
              //$(".holidayCreateTableBody tr:eq(" + error_index + ")").find(errorDomElement).html(errorMessage);
            }
          });
        }
        else if (response.status === 'success') {
          startTimeDom.val(response.start_time);
          endDateDom.val(response.end_date);
          endTimeDom.val(response.end_time);
          requiredSecondsDom.val(response.total_required_seconds);
        }
        else if (response.status === 'danger') {
          alert(response.message);
        }
      }).fail(function (response) {
        console.log(response.responseJSON);
      });
    } else {
      console.log("Sorry!! Invalid Date or Year may be lower than 2019!!");
    }
  });

  $(document).on('submit', '#sewingPlanCreateForm', function (e) {
    e.preventDefault();
    var flashMessageDom = $('.crate-plan-flash-message');
    $('.text-danger').html('');
    var loader = $('#plan-create-modal-loader');
    loader.show();
    var form = $(this);
    $.ajax({
      type: form.attr('method'),
      url: form.attr('action'),
      data: form.serialize()
    }).done(function (response) {
      loader.hide();
      if (response.status === 'error') {
        $.each(response.errors, function (errorIndex, errorValue) {
          let errorDomElement, error_index, errorMessage;
          errorDomElement = '' + errorIndex;
          errorDomIndexArray = errorDomElement.split(".");
          errorDomElement = '.' + errorDomIndexArray[0];
          error_index = errorDomIndexArray[1];
          errorMessage = errorValue[0];
          if (errorDomIndexArray.length == 1) {
            $(errorDomElement).html(errorMessage);
          } else {
            $("#plan-create-po-details-table-body tr:eq(" + error_index + ")").find(errorDomElement).html(errorMessage);
          }
        });
      }
      if (response.status === 'success') {
        flashMessageDom.html(response.message);
        flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
        closeCreatePlanModal();
        loadScheduler(getDate(scheduler.getState().date));
      }

      if (response.status === 'danger') {
        flashMessageDom.html(response.message);
        flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
      }
    });
  });

  function loadScheduler(date) {
    scheduler.clearAll();
    if (date) {
      scheduler.load("/api/sewing-plans/" + userId + "/" + factoryId + '?set_date=' + date, 'REST');
    } else {
      let current_date = new Date();
      scheduler.load("/api/sewing-plans/" + userId + "/" + factoryId + '?set_date=' + getDate(current_date), 'REST');
    }
    scheduler.updateView();
  }

  // For Undo Feature Start
  $(document).on('click', '.undo-btn', function (e) {
    e.preventDefault();
    var confirmMessage = confirm('Are you sure to undo last event?');
    if (confirmMessage) {
      $.ajax({
        type: "POST",
        url: '/undo-sewing-plan',
        data: {
          _token: $('meta[name="csrf-token"]').attr('content')
        }
      }).done(function (response) {
        if (response.status === 'error') {
          dhtmlx.message({
            text: "<span style='float: left;'><i class='fa fa-times-circle' style='font-size: 39px;'></i></span><span style='float: right;'>" + response.message + "</span>",
            type: 'dangerMsg'
          });
        } else if (response.status === 'success') {
          loadScheduler();
          dhtmlx.message({
            text: "<span style='float: left;'><i class='fa fa-check-circle' style='font-size: 39px;'></i></span><span style='float: right;'>" + response.message + "</span>",
            type: 'successMsg'
          });
        }
      }).fail(function (jqXHR, textStatus, errorThrown) {
        dhtmlx.message({
          text: "<span style='float: left;'><i class='fa fa-times-circle' style='font-size: 39px;'></i></span><span style='float: right;'>Something Went Wrong!</span>",
          type: 'dangerMsg'
        });
      });
    }
  });
  // For Undo Feature End

  // For Redo Feature Start
  $(document).on('click', '.redo-btn', function (e) {
    e.preventDefault();
    var confirmMessage = confirm('Are you sure to redo last event?');
    if (confirmMessage) {
      $.ajax({
        type: "POST",
        url: '/redo-sewing-plan',
        data: {
          _token: $('meta[name="csrf-token"]').attr('content')
        }
      }).done(function (response) {
        if (response.status === 'error') {
          dhtmlx.message({
            text: "<span style='float: left;'><i class='fa fa-times-circle' style='font-size: 39px;'></i></span><span style='float: right;'>" + response.message + "</span>",
            type: 'dangerMsg'
          });
        } else if (response.status === 'success') {
          loadScheduler();
          dhtmlx.message({
            text: "<span style='float: left;'><i class='fa fa-check-circle' style='font-size: 39px;'></i></span><span style='float: right;'>" + response.message + "</span>",
            type: 'successMsg'
          });
        }
      }).fail(function (jqXHR, textStatus, errorThrown) {
        dhtmlx.message({
          text: "<span style='float: left;'><i class='fa fa-times-circle' style='font-size: 39px;'></i></span><span style='float: right;'>Something Went Wrong!</span>",
          type: 'dangerMsg'
        });
      });
    }
  });
  // For Redo Feature End

  // For Database processing
  var dp = new dataProcessor("/api/sewing-plans/" + userId + "/" + factoryId + "/");
  dp.init(scheduler);
  dp.setTransactionMode("REST");
  dp.attachEvent("onAfterUpdate", function (id, action, tid, response) {
    if (response.status == 'success') {
      dhtmlx.message({
        text: "<span style='float: left;'><i class='fa fa-exclamation-circle' style='font-size: 39px;'></i></span><span style='float: right;'>" + response.message + "</span>",
        type: 'updateMsg'
      });
    } else {
      dhtmlx.message({
        text: "<span style='float: left;'><i class='fa fa-times-circle' style='font-size: 39px;'></i></span><span style='float: right;'>" + response.message + "</span>",
        type: 'dangerMsg'
      });
    }
  });
  /* Create Plan End */
  scheduler.config.serverLists = {};

  function createPlanningBoardView(data) {
    scheduler.load("/api/sewing-plans/" + userId + "/" + factoryId + "/");
    scheduler.serverList("lines", data.lines);
    scheduler.serverList("sewing_holidays", data.sewing_holidays);

    scheduler.locale.labels.timeline_tab = "Timeline";
    scheduler.locale.labels.section_custom = "Section";
    scheduler.config.details_on_create = true;
    scheduler.config.details_on_dblclick = true;
    scheduler.templates.tooltip_date_format = scheduler.date.date_to_str("%Y-%m-%d");
    scheduler.templates.tooltip_time_format = scheduler.date.date_to_str("%H:%i");
    scheduler.skin = "material";
    scheduler.config.time_step = 1;

    scheduler.createTimelineView({
      fit_events: true,
      name: "Timeline",
      render: "bar",
      x_unit: "day",
      x_date: "%D",
      x_size: 45,
      dy: 51,
//            section_autoheight: false,
      event_dy: "full",
      second_scale: {x_unit: "week", x_date: "%d /%m /%Y"},
      y_property: "section_id",
      y_unit: scheduler.serverList("lines"),
    });
    scheduler.config.dblclick_create = false;
    scheduler.config.details_on_create = true;
    scheduler.config.details_on_dblclick = true;
    scheduler.config.prevent_cache = true;
    scheduler.config.show_loading = true;
    scheduler.config.drag_resize = false;

    pre_init();

    setHolidayTimeSpanCss(scheduler.serverList("sewing_holidays"));

    scheduler.init('scheduler_here', null, 'Timeline');
  }

  function setHolidayTimeSpanCss(sewing_holidays) {
    scheduler.deleteMarkedTimespan();
    // Friday Holiday
    // scheduler.addMarkedTimespan({days: 5, zones: "fullday", css: "timeline_weekend"});
    // User Defined Holiday
    $.each(sewing_holidays, function (key, val) {
      scheduler.addMarkedTimespan({
        start_date: new Date(val.start_date),
        end_date: new Date(val.end_date),
        css: "timeline_weekend"
      });
    });
  }

  function pre_init() {

    function formatDateForEventBox(date) {
      var formatFunc = scheduler.date.date_to_str("%d %M %Y");
      return formatFunc(date);
    }

    function addTemplate() {
      var element = document.getElementById("scheduler_here");
      var top = scheduler.xy.nav_height + 1 + 1; // first +1 -- blank space upper border, second +1 -- hardcoded border length
      var height = scheduler.xy.scale_height * 2;
      var width = scheduler.matrix.Timeline.dx - 1;
      var factoryName = '{{ $factory_name ?? null }}';
      var factoryNameHtml = factoryName ? "<span class='small' style='font-size: 9px;'>(" + factoryName + ")</span>" : "";
      var descriptionHTML = "<div class='timeline_template'><h5 style='font-size: 1rem;'>Sewing Plan "+ factoryNameHtml +"</h5></div>";
      descriptionHTML = "<div id='template-container'>" + descriptionHTML + "</div><div style='clear: both;'></div>";
      element.innerHTML += descriptionHTML;

      var templateCont = document.getElementById("template-container");
      templateCont.style.height = height + "px";
      templateCont.style.top = top + "px";
      templateCont.style.width = width + "px";
      templateCont.style.lineHeight = height + "px";
    }

    scheduler.templates.Timeline_scale_label = function (key, label, section) {
      let template =
          "<div class='timeline_item_separator'></div>" +
          "<div class='timeline_item_cell'>" + section.line.floor_no + "</div>" +
          "<div class='timeline_item_separator'></div>" +
          "<div class='timeline_item_cell'>" + label + "</div>";

      return template;
    };

    scheduler.date.Timeline_start = function (date) {

      var top = scheduler.xy.nav_height + 1 + 1; // first +1 -- blank space upper border, second +1 -- hardcoded border length
      var height = scheduler.xy.scale_height;
      var width = scheduler.matrix.Timeline.dx - 1;

      var templateCont = document.getElementById("template-container");
      templateCont.style.height = height + "px";
      templateCont.style.top = top + "px";
      templateCont.style.width = width + "px";
      templateCont.style.lineHeight = height + "px";

      var elem = document.querySelectorAll(".timeline_template .timeline_item_cell");
      for (var j = 0; j < elem.length; j++) {
        elem[j].style.lineHeight = height + "px";
      }

      var ndate = new Date(date.valueOf());
      if (window.innerWidth < 450) {
        ndate.setDate(Math.floor(date.getDate() / 7) * 7 + 1);
        return this.date_part(ndate);

      } else if (window.innerWidth >= 450 && window.innerWidth < 768) {
        ndate.setDate(Math.floor(date.getDate() / 14) * 14 + 1);
        return this.date_part(ndate);
      } else {
        return scheduler.date.month_start(new Date(date));
      }
    };

    scheduler.date.add_Timeline = function (date, step) {
      if (window.innerWidth < 450) {
        return scheduler.date.add(date, 7 * step, "day");
      } else if (window.innerWidth >= 450 && window.innerWidth < 768) {
        return scheduler.date.add(date, 14 * step, "day");
      } else {
        return scheduler.date.add(date, step, "month");
      }
    };

    scheduler.attachEvent("onClick", function (id, e) {
      return false;
    });

    scheduler.attachEvent("onDblClick", function (id, e) {
      return false;
    });

    scheduler.attachEvent("onEmptyClick", function (date, native_event) {
      return false;
    });

    scheduler.attachEvent("onBeforeEventCreated", function (e) {
      return false;
    });

    scheduler.attachEvent("onBeforeDrag", function (id, mode, e) {
      var eventObj;
      if (id !== null) {
        eventObj = scheduler.getEvent(id);
      }
      if (eventObj && eventObj.is_locked) {
        dhtmlx.message({
          text: "<span style='float: left;'><i class='fa fa-exclamation-circle' style='font-size: 39px;'></i></span><span style='float: right;'>Sorry this Plan is locked! Unlock the plan first!</span>",
          type: 'dangerMsg'
        });
        return false;
      }
      return true;
    });

    scheduler.attachEvent("onEventChanged", function (id, ev) {
      loadScheduler(getDate(ev.start_date));
    });

    scheduler.attachEvent("onBeforeLightbox", function (id) {
      return false;
    });

    var sewingPlanId = null;

    myMenu = new dhtmlXMenuObject({
      context: true,
      items: [
        {id: "plan-details-context-menu", text: "Plan Details"},
        {id: "split-context-menu", text: "Split"},
        {id: "change-line-context-menu", text: "Change Line"},
        {id: "unload-context-menu", text: "Unload"},
        {id: "notes-context-menu", text: "Notes"},
        {id: "push-context-menu", text: "Push"},
        {id: "pull-context-menu", text: "Pull"},
        {id: "lock-strip-context-menu", text: "Lock/Unlock"},
        //{id: "product-details-context-menu", text: "Product Details"},
      ],
      iconset: "awesome",
    });
    myMenu.setIconset("awesome");
    myMenu.attachEvent("onClick", function (id, zoneId, cas) {
      switch (id) {
        case "plan-details-context-menu" :
          showOrderDetails(sewingPlanId);
          break;
        case "split-context-menu" :
          showSplitQtyModal(sewingPlanId);
          break;
        case "change-line-context-menu" :
          sewingPlanChangeLine(sewingPlanId);
          break;
        case "unload-context-menu" :
          openUnloadPlanModal(sewingPlanId);
          break;
        case "notes-context-menu" :
          openNotesEntryModal(sewingPlanId);
          break;
        case "push-context-menu" :
          pushStrip(sewingPlanId);
          break;
        case "pull-context-menu" :
          pullStrip(sewingPlanId);
          break;
        case "lock-strip-context-menu" :
          showLockUnlockModal(sewingPlanId);
          break;
        case "product-details-context-menu" :
          openProductDetailsModal(sewingPlanId);
          break;
        default :
          break;
      }
    });

    scheduler.attachEvent("onContextMenu", function (event_id, native_event_object) {
      if (event_id) {
        scheduler.tooltip.hide();
        var posx = 0;
        var posy = 0;
        if (native_event_object.pageX || native_event_object.pageY) {
          posx = native_event_object.pageX;
          posy = native_event_object.pageY;
        } else if (native_event_object.clientX || native_event_object.clientY) {
          posx = native_event_object.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
          posy = native_event_object.clientY + document.body.scrollTop + document.documentElement.scrollTop;
        }
        sewingPlanId = event_id;
        myMenu.showContextMenu(posx, posy);
        let sewingPlanInfo = scheduler.getEvent(event_id);

        if (sewingPlanInfo.is_locked) {
          myMenu.setItemDisabled("split-context-menu");
          myMenu.setItemDisabled("change-line-context-menu");
          myMenu.setItemDisabled("unload-context-menu");
          myMenu.setItemDisabled("notes-context-menu");
          myMenu.setItemDisabled("push-context-menu");
          myMenu.setItemDisabled("pull-context-menu");
        } else {
          myMenu.setItemEnabled("split-context-menu");
          myMenu.setItemEnabled("change-line-context-menu");
          myMenu.setItemEnabled("unload-context-menu");
          myMenu.setItemEnabled("notes-context-menu");
          myMenu.setItemEnabled("push-context-menu");
          myMenu.setItemEnabled("pull-context-menu");
        }
        myMenu.setItemImage("plan-details-context-menu", "fa fa-info", "fa fa-info");
        myMenu.setItemImage("change-line-context-menu", "fa fa-arrows", "fa fa-arrows");
        myMenu.setItemImage("split-context-menu", "fa fa-chain-broken", "fa fa-chain-broken");
        myMenu.setItemImage("unload-context-menu", "fa fa-trash", "fa fa-trash");
        myMenu.setItemImage("notes-context-menu", "fa fa-clipboard", "fa fa-clipboard");
        myMenu.setItemImage("push-context-menu", "fa fa-arrow-right", "fa fa-arrow-right");
        myMenu.setItemImage("pull-context-menu", "fa fa-arrow-left", "fa fa-arrow-left");
        myMenu.setItemImage("lock-strip-context-menu", "fa fa-key", "fa fa-key");
        //myMenu.setItemImage("product-details-context-menu", "fa fa-info", "fa fa-info");
        return false; // prevent default action and propagation
      }
      return false;
    });

    /* Change Line Feature Start */
    function sewingPlanChangeLine(sewingPlanId) {
      if (sewingPlanId) {
        $.ajax({
          type: 'GET',
          url: '/get-sewing-plan-order-details/' + sewingPlanId
        }).done(function (response) {
          $('.sewingPlanChangeLineModalContent').html(response.change_line_html);
          openChangeLineModal();
        }).fail(function (response) {
          console.log(response.responseJSON);
        });
      }
    }

    function openChangeLineModal() {
      $('#sewingPlanChangeLineModal').modal({
        keyboard: false,
        backdrop: false,
        show: true,
      });
      setSelect2();
    }

    function closeChangeLineModal() {
      $('#sewingPlanChangeLineModal').modal('hide');
      $('.sewingPlanChangeLineModalContent').html('');
    }

    $(document).on('change', '#sewingPlanChangeLineModal select[name="floor_id"]', function () {
      var floor_id = $(this).val();
      $('span.floor_id').html('');
      var lineDropdownDom = $('#sewingPlanChangeLineModal select[name="line_id"]');
      lineDropdownDom.empty();
      var planManpowerDom = $('#change-line-sewing-plan-manpower');
      var planWorkingHourDom = $('#change-line-sewing-plan-working-hour');
      var planCapacityDom = $('#change-line-sewing-plan-capacity');
      var planCapacityMinDom = $('#change-line-sewing-plan-capacity-min');
      var planCapacityInputDom = $('#sewingPlanChangeLineModal input[name="capacity_pcs"]');
      var planEfficiencyDom = $('#change-line-sewing-plan-efficiency');
      var startDateDom = $('#sewingPlanChangeLineModal input[name="start_date"]');
      var startDateTimeDom = $('#sewingPlanChangeLineModal input[name="start_time"]');
      var endDateDom = $('#sewingPlanChangeLineModal input[name="end_date"]');
      var endDateTimeDom = $('#sewingPlanChangeLineModal input[name="end_time"]');
      planManpowerDom.html('');
      planWorkingHourDom.html('');
      planCapacityDom.html('');
      planCapacityMinDom.html('');
      planCapacityInputDom.val('');
      planEfficiencyDom.html('');
      startDateDom.val('');
      startDateTimeDom.val('');
      endDateDom.val('');
      endDateTimeDom.val('');
      if (floor_id) {
        $.ajax({
          type: 'GET',
          url: '/get-lines/' + floor_id,
        }).done(function (response) {
          var linesDropdown = '<option value="">Select Line</option>';
          if (Object.keys(response.data).length > 0) {
            $.each(response.data, function (index, line) {
              linesDropdown += '<option value="' + line.id + '">' + line.line_no + '</option>';
            });
          }
          lineDropdownDom.html(linesDropdown);
          lineDropdownDom.val('').select2();
          setSelect2();
        });
      }
    });

    $(document).on('change', '#sewingPlanChangeLineModal select[name="line_id"]', function () {
      var line_id = $(this).val();
      $('span.line_id').html('');
      var smv = $('#sewingPlanChangeLineModal input[name="smv"]').val();
      var planManpowerDom = $('#change-line-sewing-plan-manpower');
      var planWorkingHourDom = $('#change-line-sewing-plan-working-hour');
      var planCapacityDom = $('#change-line-sewing-plan-capacity');
      var planCapacityMinDom = $('#change-line-sewing-plan-capacity-min');
      var planCapacityInputDom = $('#sewingPlanChangeLineModal input[name="capacity_pcs"]');
      var planEfficiencyDom = $('#change-line-sewing-plan-efficiency');
      var startDateDom = $('#sewingPlanChangeLineModal input[name="start_date"]');
      var startDateTimeDom = $('#sewingPlanChangeLineModal input[name="start_time"]');
      var endDateDom = $('#sewingPlanChangeLineModal input[name="end_date"]');
      var endDateTimeDom = $('#sewingPlanChangeLineModal input[name="end_time"]');
      planManpowerDom.html('');
      planWorkingHourDom.html('');
      planCapacityDom.html('');
      planCapacityMinDom.html('');
      planCapacityInputDom.val('');
      planEfficiencyDom.html('');
      startDateDom.val('');
      startDateTimeDom.val('');
      endDateDom.val('');
      endDateTimeDom.val('');
      if (line_id && smv) {
        $.ajax({
          type: 'GET',
          url: '/get-line-capacity-information/' + line_id + '/' + smv,
        }).done(function (response) {
          planManpowerDom.html(response.manpower);
          planWorkingHourDom.html(response.working_hour);
          planCapacityMinDom.html(response.line_capacity_min);
          planCapacityDom.html(response.line_capacity);
          planCapacityInputDom.val(response.line_capacity);
          planEfficiencyDom.html(response.line_efficiency);
        });
      }
    });

    $(document).on('keyup change', '#sewingPlanChangeLineModal input[name="start_date"]', function () {
      var start_date = $(this).val();
      var date_format = new Date(start_date);
      var smv = $('#sewingPlanChangeLineModal input[name="smv"]').val();
      var line_id = $('#sewingPlanChangeLineModal select[name="line_id"]').val();
      var allocated_qty = $('#sewingPlanChangeLineModal input[name="master_allocated_qty"]').val();
      var startTimeDom = $('#sewingPlanChangeLineModal input[name="start_time"]');
      var endDateDom = $('#sewingPlanChangeLineModal input[name="end_date"]');
      var endTimeDom = $('#sewingPlanChangeLineModal input[name="end_time"]');
      var requiredSecondsDom = $('#sewingPlanChangeLineModal input[name="required_seconds"]');
      $('.text-danger').html('');
      if (!line_id) {
        alert("Please Select Line!!");
        $(this).val('');
        return false;
      }
      if (!allocated_qty || allocated_qty <= 0) {
        alert("Please enter allocated qty!!");
        $(this).val('');
        return false;
      }
      startTimeDom.val('');
      endDateDom.val('');
      endTimeDom.val('');
      requiredSecondsDom.val('');
      // check for valid date && year above 2018
      if (date_format && date_format.getFullYear() > 2018) {
        $.ajax({
          type: "GET",
          url: "/get-end-date-time-for-plan/" + start_date + '/' + smv + '/' + line_id + '/' + allocated_qty
        }).done(function (response) {
          if (response.status === 'error') {
            $.each(response.errors, function (errorIndex, errorValue) {
              let errorDomElement, error_index, errorMessage;
              errorDomElement = '' + errorIndex;
              errorDomIndexArray = errorDomElement.split(".");
              errorDomElement = '.' + errorDomIndexArray[0];
              error_index = errorDomIndexArray[1];
              errorMessage = errorValue[0];
              if (errorDomIndexArray.length == 1) {
                $(errorDomElement).html(errorMessage);
              } else {
                //$(".holidayCreateTableBody tr:eq(" + error_index + ")").find(errorDomElement).html(errorMessage);
              }
            });
          }
          else if (response.status === 'success') {
            startTimeDom.val(response.start_time);
            endDateDom.val(response.end_date);
            endTimeDom.val(response.end_time);
            requiredSecondsDom.val(response.total_required_seconds);
          }
          else if (response.status === 'danger') {
            alert(response.message);
          }
        }).fail(function (response) {
          console.log(response.responseJSON);
        });
      } else {
        console.log("Sorry!! Invalid Date or Year may be lower than 2019!!");
      }
    });

    $(document).on('submit', '#sewingPlanChangeLineForm', function (e) {
      e.preventDefault();
      var form = $(this);
      var flashMessageDom = $('.change-line-plan-flash-message');
      var loader = $('#change-line-qty-modal-loader');
      loader.show();
      $('.text-danger').html('');
      $.ajax({
        type: form.attr('method'),
        url: form.attr('action'),
        data: form.serialize()
      }).done(function (response) {
        loader.hide();
        if (response.status === 'error') {
          $.each(response.errors, function (errorIndex, errorValue) {
            let errorDomElement, error_index, errorMessage;
            errorDomElement = '' + errorIndex;
            errorDomIndexArray = errorDomElement.split(".");
            errorDomElement = 'span.' + errorDomIndexArray[0];
            error_index = errorDomIndexArray[1];
            errorMessage = errorValue[0];
            if (errorDomIndexArray.length == 1) {
              $(errorDomElement).html(errorMessage);
            } else {
              $("#change-line-plan-qty-table-body tr:eq(" + error_index + ")").find(errorDomElement).html(errorMessage);
            }
          });
        }
        if (response.status === 'success') {
          scrollToTopModalBody();
          flashMessageDom.html(response.message);
          flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
          loadScheduler(getDate(scheduler.getState().date));
          setTimeout(closeChangeLineModal(), 3000);
        }

        if (response.status === 'danger') {
          scrollToTopModalBody();
          flashMessageDom.html(response.message);
          flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
        }
      }).fail(function (response) {
        loader.hide();
        console.log(response.responseJSON);
      });

    });
    /* Change Line Feature End */

    /* Push Strip Start */
    function pushStrip(sewing_plan_id) {
      var confirmMessage = confirm("Are you sure?");
      var token = $('meta[name="csrf-token"]').attr('content');
      if (confirmMessage) {
        $.ajax({
          type: 'POST',
          url: '/push-strip',
          data: {
            id: sewing_plan_id,
            _token: token
          }
        }).done(function (response) {
          switch (response.status) {
            case 'success':
              dhtmlx.message({
                text: "<span style='float: left;'><i class='fa fa-check-circle' style='font-size: 39px;'></i></span><span style='float: right;'><b>" + response.message + "</b></span>",
                type: 'successMsg'
              });
              break;
            case 'error' :
            case 'danger' :
              dhtmlx.message({
                text: "<span style='float: left;'><i class='fa fa-exclamation-circle' style='font-size: 39px;'></i></span><span style='float: right;'>" + response.message + "</span>",
                type: 'dangerMsg'
              });
              break;
            default:
              break;
          }
          loadScheduler(getDate(scheduler.getState().date));
        });
      }
    }

    /* Push Strip End */

    /* Pull Strip Start */
    function pullStrip(sewing_plan_id) {
      var confirmMessage = confirm("Are you sure?");
      var token = $('meta[name="csrf-token"]').attr('content');
      if (confirmMessage) {
        $.ajax({
          type: 'POST',
          url: '/pull-strip',
          data: {
            id: sewing_plan_id,
            _token: token
          }
        }).done(function (response) {
          switch (response.status) {
            case 'success':
              dhtmlx.message({
                text: "<span style='float: left;'><i class='fa fa-check-circle' style='font-size: 39px;'></i></span><span style='float: right;'><b>" + response.message + "</b></span>",
                type: 'successMsg'
              });
              break;
            case 'error' :
            case 'danger' :
              dhtmlx.message({
                text: "<span style='float: left;'><i class='fa fa-exclamation-circle' style='font-size: 39px;'></i></span><span style='float: right;'>" + response.message + "</span>",
                type: 'dangerMsg'
              });
              break;
          }
          loadScheduler(getDate(scheduler.getState().date));
        });
      }
    }

    /* Pull Strip End */

    /* Plan Unload Feature Start */
    function openUnloadPlanModal(sewing_plan_id) {
      $('#sewingPlanUnloadModal').modal({
        keyboard: false,
        backdrop: false,
        show: true,
      });
      $('#deleteSewingPlanForm').attr('action', '/sewing-plan/' + sewing_plan_id + '/delete');
    }

    function closePlanUnloadModal() {
      $('#sewingPlanUnloadModal').modal('hide');
      loadScheduler(getDate(scheduler.getState().date));
    }

    $(document).on('submit', '#deleteSewingPlanForm', function (e) {
      e.preventDefault();
      var form = $(this);

      $.ajax({
        type: form.attr('method'),
        url: form.attr('action'),
        data: form.serialize()
      }).done(function (response) {
        closePlanUnloadModal();
        if (response.status === 'success') {
          dhtmlx.message({
            text: "<span style='float: left;'><i class='fa fa-check-circle' style='font-size: 39px;'></i></span><span style='float: right;'><b>" + response.message + "</b></span>",
            type: 'successMsg'
          });
        } else if (response.status === 'danger') {
          dhtmlx.message({
            text: "<span style='float: left;'><i class='fa fa-check-circle' style='font-size: 39px;'></i></span><span style='float: right;'><b>" + response.message + "</b></span>",
            type: 'dangerMsg'
          });
        }
        scheduler.clearAll();
      })
    });
    /* Plan Unload Feature End */

    /* Lock Unlock Strip  Start */
    function showLockUnlockModal(sewing_plan_id) {
      $.ajax({
        type: "GET",
        url: "/get-sewing-plan-order-details/" + sewing_plan_id
      }).done(function (response) {
        $('.lockUnlockModalContent').html(response.lock_unlock_html);
        setTimeout(openLockUnlockModal(), 2000);
      });
    }

    $(document).on('submit', '#lockUnlockSewingPlanStripForm', function (e) {
      e.preventDefault();
      var form = $(this);
      var flashMessageDom = $('.lock-unlock-modal-flash-message');
      var loader = $('#lock-unlock-modal-loader');
      loader.show();
      $('.text-danger').html('');
      $.ajax({
        type: form.attr('method'),
        url: form.attr('action'),
        data: form.serialize()
      }).done(function (response) {
        loader.hide();
        if (response.status === 'error') {
          $.each(response.errors, function (errorIndex, errorValue) {
            let errorDomElement, error_index, errorMessage;
            errorDomElement = '' + errorIndex;
            errorDomIndexArray = errorDomElement.split(".");
            errorDomElement = 'span.' + errorDomIndexArray[0];
            error_index = errorDomIndexArray[1];
            errorMessage = errorValue[0];
            if (errorDomIndexArray.length == 1) {
              $(errorDomElement).html(errorMessage);
            } else {
              //$(".holidayCreateTableBody tr:eq(" + error_index + ")").find(errorDomElement).html(errorMessage);
            }
          });
        }
        if (response.status === 'success') {
          flashMessageDom.html(response.message);
          flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
          loadScheduler(getDate(scheduler.getState().date));
          setTimeout(closeLockUnlockModal(), 3000);
        }

        if (response.status === 'danger') {
          flashMessageDom.html(response.message);
          flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
        }
      });
    });

    function openLockUnlockModal() {
      $('#lockUnlockModal').modal({
        keyboard: false,
        backdrop: false,
        show: true,
      });
    }

    function closeLockUnlockModal() {
      $('#lockUnlockModal').modal('hide');
    }

    /* Lock Unlock Strip  End */

    /* Order Details Feature Start */
    function showOrderDetails(sewing_plan_id) {
      $.ajax({
        type: "GET",
        url: "/get-sewing-plan-order-details/" + sewing_plan_id
      }).done(function (response) {
        $('.orderDetailsModalContent').html(response.html);
        setTimeout(openOrderDetailsModal(), 2000);
      });
    }

    function openOrderDetailsModal() {
      $('#orderDetailsModal').modal({
        keyboard: false,
        backdrop: false,
        show: true,
      });
    }

    function closeOrderDetailsModal() {
      $('#orderDetailsModal').modal('hide');
    }

    /* Order Details Feature End */

    /* Product Details Feature Start */
    function openProductDetailsModal(sewingPlanId) {
      $('#productDetailsModal').modal({
        keyboard: false,
        backdrop: false,
        show: true,
      });
    }

    function closeProductDetailsModal() {
      $('#productDetailsModal').modal('hide');
    }

    /* Product Details Feature End */

    /* Split Qty Feature Start */
    function showSplitQtyModal(sewing_plan_id) {
      $.ajax({
        type: "GET",
        url: "/get-sewing-plan-order-details/" + sewing_plan_id
      }).done(function (response) {
        $('.splitQtyModalContent').html(response.split_qty_html);
        setSelect2();
        setTimeout(openSplitQtyModal(), 2000);
      });
    }

    function openSplitQtyModal() {
      $('#splitQtyModal').modal({
        keyboard: false,
        backdrop: false,
        show: true,
      });
      setSelect2();
    }

    function closeSplitQtyModal() {
      $('#splitQtyModal').modal('hide');
    }

    $(document).on('submit', '#splitPlanForm', function (e) {
      e.preventDefault();
      var form = $(this);

      let temp_sub_total_allocated_qty = 0;
      $('#split-plan-qty-table-body tr').each(function () {
        var po_wise_allocated_qty = $(this).find('td input[name="split_qty[]"]')[0].value;
        if (!po_wise_allocated_qty || po_wise_allocated_qty <= 0) {
          po_wise_allocated_qty = 0;
        }
        temp_sub_total_allocated_qty += Number(po_wise_allocated_qty);
      });

      if (temp_sub_total_allocated_qty <= 0) {
        alert("Please enter at least one split qty");
        return false;
      }

      var flashMessageDom = $('.split-plan-flash-message');
      var loader = $('#split-qty-modal-loader');
      loader.show();
      $('.text-danger').html('');
      $.ajax({
        type: form.attr('method'),
        url: form.attr('action'),
        data: form.serialize()
      }).done(function (response) {
        loader.hide();
        if (response.status === 'error') {
          $.each(response.errors, function (errorIndex, errorValue) {
            let errorDomElement, error_index, errorMessage;
            errorDomElement = '' + errorIndex;
            errorDomIndexArray = errorDomElement.split(".");
            errorDomElement = 'span.' + errorDomIndexArray[0];
            error_index = errorDomIndexArray[1];
            errorMessage = errorValue[0];
            if (errorDomIndexArray.length == 1) {
              $(errorDomElement).html(errorMessage);
            } else {
              $("#split-plan-qty-table-body tr:eq(" + error_index + ")").find(errorDomElement).html(errorMessage);
            }
          });
        }
        if (response.status === 'success') {
          flashMessageDom.html(response.message);
          flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
          loadScheduler(getDate(scheduler.getState().date));
          setTimeout(closeSplitQtyModal(), 3000);
        }

        if (response.status === 'danger') {
          flashMessageDom.html(response.message);
          flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
        }
      }).fail(function (response) {
        loader.hide();
        console.log(response.responseJSON);
      });

    });

    $(document).on('keyup', '#splitPlanForm input[name="split_qty[]"]', function () {
      var split_qty = Number($(this).val());
      var previousAllocatedQtyDom = $(this).parents('tr').find('input[name="previous_allocated_qty[]"]');
      var totalAllocatedQtyDom = $(this).parents('tr').find('input[name="total_allocated_qty[]"]');
      var total_allocated_qty = Number(totalAllocatedQtyDom.val());

      var master_split_qty_input = $('input[name="master_split_qty"]');
      var remaining_master_plan_qty = $('input[name="remaining_master_plan_qty"]').val();
      var remaining_master_plan_qty_dom = $('.remaining_master_plan_qty');

      let new_prev_allocated_qty;

      if (/^[0-9]*$/.test($(this).val()) == false) {
        alert("You have used the illegal character!");
        $(this).val(0);
        previousAllocatedQtyDom.val(total_allocated_qty);

        let temp_sub_total_allocated_qty = 0;
        $('#split-plan-qty-table-body tr').each(function () {
          var po_wise_allocated_qty = $(this).find('td input[name="split_qty[]"]')[0].value;
          if (!po_wise_allocated_qty) {
            po_wise_allocated_qty = 0;
          }
          temp_sub_total_allocated_qty += Number(po_wise_allocated_qty);
        });
        master_split_qty_input.val(temp_sub_total_allocated_qty);
        remaining_master_plan_qty_dom.html(remaining_master_plan_qty - temp_sub_total_allocated_qty);
        return false;
      }

      if (!Number.isInteger(split_qty)) {
        alert('Split quantity must be an integer!');
        $(this).val(0);
        previousAllocatedQtyDom.val(total_allocated_qty);

        let temp_sub_total_allocated_qty = 0;
        $('#split-plan-qty-table-body tr').each(function () {
          var po_wise_allocated_qty = $(this).find('td input[name="split_qty[]"]')[0].value;
          if (!po_wise_allocated_qty) {
            po_wise_allocated_qty = 0;
          }
          temp_sub_total_allocated_qty += Number(po_wise_allocated_qty);
        });
        master_split_qty_input.val(temp_sub_total_allocated_qty);
        remaining_master_plan_qty_dom.html(remaining_master_plan_qty - temp_sub_total_allocated_qty);

        return false;
      } else {
        new_prev_allocated_qty = total_allocated_qty - split_qty;
        if (new_prev_allocated_qty < 0) {
          alert('Split quantity cannot be greater than total allocation!');
          $(this).val(0);
          previousAllocatedQtyDom.val(total_allocated_qty);

          let temp_sub_total_allocated_qty = 0;
          $('#split-plan-qty-table-body tr').each(function () {
            var po_wise_allocated_qty = $(this).find('td input[name="split_qty[]"]')[0].value;
            if (!po_wise_allocated_qty) {
              po_wise_allocated_qty = 0;
            }
            temp_sub_total_allocated_qty += Number(po_wise_allocated_qty);
          });
          master_split_qty_input.val(temp_sub_total_allocated_qty);
          remaining_master_plan_qty_dom.html(remaining_master_plan_qty - temp_sub_total_allocated_qty);

          return false;
        }
        previousAllocatedQtyDom.val(new_prev_allocated_qty);

        let temp_sub_total_allocated_qty = 0;
        $('#split-plan-qty-table-body tr').each(function () {
          var po_wise_allocated_qty = $(this).find('td input[name="split_qty[]"]')[0].value;
          if (!po_wise_allocated_qty) {
            po_wise_allocated_qty = 0;
          }
          temp_sub_total_allocated_qty += Number(po_wise_allocated_qty);
        });
        master_split_qty_input.val(temp_sub_total_allocated_qty);
        remaining_master_plan_qty_dom.html(remaining_master_plan_qty - temp_sub_total_allocated_qty);
      }

    });

    $(document).on('change', '#splitQtyModal select[name="floor_id"]', function () {
      var floor_id = $(this).val();
      $('span.floor_id').html('');
      var lineDropdownDom = $('#splitQtyModal select[name="line_id"]');
      lineDropdownDom.empty();
      var planManpowerDom = $('#split-sewing-plan-manpower');
      var planWorkingHourDom = $('#split-sewing-plan-working-hour');
      var planCapacityDom = $('#split-sewing-plan-capacity');
      var planCapacityMinDom = $('#split-sewing-plan-capacity-min');
      var planCapacityInputDom = $('#splitQtyModal input[name="capacity_pcs"]');
      var planEfficiencyDom = $('#split-sewing-plan-efficiency');
      var startDateDom = $('#splitQtyModal input[name="start_date"]');
      var startDateTimeDom = $('#splitQtyModal input[name="start_time"]');
      var endDateDom = $('#splitQtyModal input[name="end_date"]');
      var endDateTimeDom = $('#splitQtyModal input[name="end_time"]');
      planManpowerDom.html('');
      planWorkingHourDom.html('');
      planCapacityDom.html('');
      planCapacityMinDom.html('');
      planCapacityInputDom.val('');
      planEfficiencyDom.html('');
      startDateDom.val('');
      startDateTimeDom.val('');
      endDateDom.val('');
      endDateTimeDom.val('');
      if (floor_id) {
        $.ajax({
          type: 'GET',
          url: '/get-lines/' + floor_id,
        }).done(function (response) {
          var linesDropdown = '<option value="">Select Line</option>';
          if (Object.keys(response.data).length > 0) {
            $.each(response.data, function (index, line) {
              linesDropdown += '<option value="' + line.id + '">' + line.line_no + '</option>';
            });
          }
          lineDropdownDom.html(linesDropdown);
          lineDropdownDom.val('').select2();
          setSelect2();
        });
      }
    });

    $(document).on('change', '#splitQtyModal select[name="line_id"]', function () {
      var line_id = $(this).val();
      $('span.line_id').html('');
      var smv = $('#splitQtyModal input[name="smv"]').val();
      var planManpowerDom = $('#split-sewing-plan-manpower');
      var planWorkingHourDom = $('#split-sewing-plan-working-hour');
      var planCapacityDom = $('#split-sewing-plan-capacity');
      var planCapacityMinDom = $('#split-sewing-plan-capacity-min');
      var planCapacityInputDom = $('#splitQtyModal input[name="capacity_pcs"]');
      var planEfficiencyDom = $('#split-sewing-plan-efficiency');
      var startDateDom = $('#splitQtyModal input[name="start_date"]');
      var startDateTimeDom = $('#splitQtyModal input[name="start_time"]');
      var endDateDom = $('#splitQtyModal input[name="end_date"]');
      var endDateTimeDom = $('#splitQtyModal input[name="end_time"]');
      planManpowerDom.html('');
      planWorkingHourDom.html('');
      planCapacityDom.html('');
      planCapacityMinDom.html('');
      planCapacityInputDom.val('');
      planEfficiencyDom.html('');
      startDateDom.val('');
      startDateTimeDom.val('');
      endDateDom.val('');
      endDateTimeDom.val('');
      if (line_id && smv) {
        $.ajax({
          type: 'GET',
          url: '/get-line-capacity-information/' + line_id + '/' + smv,
        }).done(function (response) {
          planManpowerDom.html(response.manpower);
          planWorkingHourDom.html(response.working_hour);
          planCapacityMinDom.html(response.line_capacity_min);
          planCapacityDom.html(response.line_capacity);
          planCapacityInputDom.val(response.line_capacity);
          planEfficiencyDom.html(response.line_efficiency);
        });
      }
    });

    $(document).on('keyup change', '#splitQtyModal input[name="start_date"]', function () {
      var start_date = $(this).val();
      var date_format = new Date(start_date);
      var smv = $('#splitQtyModal input[name="smv"]').val();
      var line_id = $('#splitQtyModal select[name="line_id"]').val();
      var allocated_qty = $('#splitQtyModal input[name="master_split_qty"]').val();
      var startTimeDom = $('#splitQtyModal input[name="start_time"]');
      var endDateDom = $('#splitQtyModal input[name="end_date"]');
      var endTimeDom = $('#splitQtyModal input[name="end_time"]');
      var requiredSecondsDom = $('#splitQtyModal input[name="required_seconds"]');
      $('.text-danger').html('');
      if (!line_id) {
        alert("Please Select Line!!");
        $(this).val('');
        return false;
      }
      if (!allocated_qty || allocated_qty <= 0) {
        alert("Please enter split qty!!");
        $(this).val('');
        return false;
      }
      startTimeDom.val('');
      endDateDom.val('');
      endTimeDom.val('');
      requiredSecondsDom.val('');
      // check for valid date && year above 2018
      if (date_format && date_format.getFullYear() > 2018) {
        $.ajax({
          type: "GET",
          url: "/get-end-date-time-for-plan/" + start_date + '/' + smv + '/' + line_id + '/' + allocated_qty
        }).done(function (response) {
          if (response.status === 'error') {
            $.each(response.errors, function (errorIndex, errorValue) {
              let errorDomElement, error_index, errorMessage;
              errorDomElement = '' + errorIndex;
              errorDomIndexArray = errorDomElement.split(".");
              errorDomElement = '.' + errorDomIndexArray[0];
              error_index = errorDomIndexArray[1];
              errorMessage = errorValue[0];
              if (errorDomIndexArray.length == 1) {
                $(errorDomElement).html(errorMessage);
              } else {
                //$(".holidayCreateTableBody tr:eq(" + error_index + ")").find(errorDomElement).html(errorMessage);
              }
            });
          }
          else if (response.status === 'success') {
            startTimeDom.val(response.start_time);
            endDateDom.val(response.end_date);
            endTimeDom.val(response.end_time);
            requiredSecondsDom.val(response.total_required_seconds);
          }
          else if (response.status === 'danger') {
            alert(response.message);
          }
        }).fail(function (response) {
          console.log(response.responseJSON);
        });
      } else {
        console.log("Sorry!! Invalid Date or Year may be lower than 2019!!");
      }
    });
    /* Split Qty Feature End */

    /* Plan Notes Feature Start */
    function openNotesEntryModal(sewing_plan_id) {
      var eventObj = scheduler.getEvent(sewing_plan_id);
      $('#planNoteEntryForm').attr('action', '/sewing-plan-note-update/' + sewing_plan_id);
      $('#planNoteEntryForm textarea[name="notes"]').val(eventObj.notes);
      $('#sewingPlanNoteModal').modal({
        keyboard: false,
        backdrop: false,
        show: true,
      });
    }

    function closeNotesEntryModal() {
      $('#sewingPlanNoteModal').modal('hide');
    }

    $(document).on('submit', '#planNoteEntryForm', function (e) {
      e.preventDefault();
      var form = $(this);
      var flashMessageDom = $('.note-entry-flash-message');
      var loader = $('#note-entry-modal-loader');
      loader.show();
      $('.text-danger').html('');
      $.ajax({
        type: form.attr('method'),
        url: form.attr('action'),
        data: form.serialize()
      }).done(function (response) {
        loader.hide();
        if (response.status === 'error') {
          $.each(response.errors, function (errorIndex, errorValue) {
            let errorDomElement, error_index, errorMessage;
            errorDomElement = '' + errorIndex;
            errorDomIndexArray = errorDomElement.split(".");
            errorDomElement = 'span.' + errorDomIndexArray[0];
            error_index = errorDomIndexArray[1];
            errorMessage = errorValue[0];
            if (errorDomIndexArray.length == 1) {
              $(errorDomElement).html(errorMessage);
            } else {
              //$(".holidayCreateTableBody tr:eq(" + error_index + ")").find(errorDomElement).html(errorMessage);
            }
          });
        }
        if (response.status === 'success') {
          flashMessageDom.html(response.message);
          flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
          loadScheduler(getDate(scheduler.getState().date));
          setTimeout(closeNotesEntryModal(), 3000);
        }

        if (response.status === 'danger') {
          flashMessageDom.html(response.message);
          flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
        }
      });
    });
    /* Plan Notes Feature End */

    scheduler.attachEvent("onBeforeViewChange", function (old_mode, old_date, mode, date) {
      var year = date.getFullYear();
      var month = (date.getMonth() + 1);
      var d = new Date(year, month, 0);
      var daysInMonth = d.getDate();
      try {
        if (old_date && date) {
          if (old_date != date) {
            loadScheduler(getDate(date));
          }
        }

        if (typeof scheduler !== "undefined") {
          /*Multiple resource view*/
          if (scheduler.matrix != undefined) {

            var top = scheduler.xy.nav_height + 1 + 1; // first +1 -- blank space upper border, second +1 -- hardcoded border length
            var height = scheduler.xy.scale_height * 2;
            var width = scheduler.matrix.Timeline.dx - 1;


            if (window.innerWidth < 450) {
              scheduler.matrix["Timeline"].x_step = 1;
              scheduler.matrix["Timeline"].x_size = 7;
            } else if (window.innerWidth >= 450 && window.innerWidth < 768) {
              scheduler.matrix["Timeline"].x_step = 1;
              scheduler.matrix["Timeline"].x_size = 14;
            } else {
              scheduler.matrix["Timeline"].x_step = 1;
              scheduler.matrix["Timeline"].x_start = 0;
              scheduler.matrix["Timeline"].x_size = daysInMonth;
            }

            var templateCont = document.getElementById("template-container");
            templateCont.style.height = height + "px";
            templateCont.style.top = top + "px";
            templateCont.style.width = width + "px";
          }
        }
      } catch (err) {
        console.log(err.message);
        return true;
      }

      return true;
    });

    scheduler.attachEvent("onEventSave", function (id, ev, is_new) {
      if (!ev.text.trim()) {
        dhtmlx.message({
          type: "error",
          text: "Name must not be empty."
        });
        return false;
      }
      return true;
    });

    addTemplate();

    // Tooltip
    scheduler.templates.tooltip_text = function (start, end, ev) {
      var today = getDate(new Date());
      let style_name = ev.order.style_name ? ev.order.style_name : '';
      let production = ev.production ? ev.production : 0;
      let progress_percent = ev.allocated_qty > 0 ? (production / ev.allocated_qty) * 100 : 0;
      let background_color;
      let plan_date_end = getDate(new Date(ev.end_date));
      let plan_date_start = getDate(new Date(ev.start_date));
      let shipment_date = ev.ex_factory_date;
      if (compareDates(new Date(shipment_date), new Date(plan_date_end))) {
        background_color = '#df0000';
      } else if (compareDates(new Date(plan_date_start), new Date(today))) {
        if (progress_percent <= 50) {
          background_color = '#8E0000';
        } else if (progress_percent > 50 && progress_percent <= 70) {
          background_color = '#e7b52e';
        } else if (progress_percent >= 100) {
          progress_percent = 100;
          background_color = '#08ab09';
        } else {
          background_color = '#08ab09';
        }
      } else {
        background_color = ev.board_color;
      }
      var tooltip_text = "<p class='tooltip-elements'>"
          + "<span class='tooltip-tag'>Plan </span>"
          + "<i class='fa fa-caret-right'></i> "
          + "<span class='tooltip-plan-desc '>" + ev.plan_text + "</span> "
          + "<span class='tooltip-plan-color pull-right' style='background-color: " + background_color + "'></span> "
          + "</p>"
          + "<p class='tooltip-elements'>"
          + "<span class='tooltip-tag'>Style/Order </span> "
          + "<i class='fa fa-caret-right'></i> "
          + "<span class='tooltip-progress-percent pull-right' style='padding: inherit;color: #16682d;'>" + style_name + "</span>"
          + "</p>"
          + "<p class='tooltip-elements'>"
          + "<span class='tooltip-tag'>Progress </span> "
          + "<i class='fa fa-caret-right'></i> "
          + "<span class='tooltip-progress-percent pull-right' style='padding: inherit;'>" + Math.round(progress_percent) + "% </span>"
          + "</p>"
          + "<p class='tooltip-elements'>"
          + "<span class='tooltip-tag'>Shipment Date </span> "
          + "<i class='fa fa-caret-right'></i> "
          + "<span class='tooltip-progress-percent pull-right' style='padding: inherit;'>" + ev.ex_factory_date + " </span>"
          + "</p>";
      if (ev.notes) {
        tooltip_text += "<p class='tooltip-elements'>"
            + "<span class='tooltip-tag'>Notes </span> "
            + "<i class='fa fa-caret-right'></i> "
            + "<span class='tooltip-plan-desc'>" + ev.notes + "</span>"
            + "</p>";
      }
      tooltip_text += "<p class='tooltip-elements'>"
          + "<span class='tooltip-date-time-range'>" + scheduler.templates.tooltip_time_format(start) + " - " + scheduler.templates.tooltip_time_format(end) + "</span> "
          + "<span class='tooltip-date-time-range pull-right' style='padding: inherit;'>" + scheduler.templates.tooltip_date_format(start) + " - " + scheduler.templates.tooltip_date_format(end) + "</span>"
          + "</p>";

      return tooltip_text;
    };

    function compareDates(date1, date2) {
      if (date1 <= date2) return 1;
      else return 0;
    }

    // Plan board color change
    scheduler.attachEvent("onLoadEnd", function () {
      // use an arbitrary id for the style element
      var styleId = "dynamicSchedulerStyles";

      // in case you'll be reloading options with colors - reuse previously
      // created style element

      var element = document.getElementById(styleId);
      if (!element) {
        element = document.createElement("style");
        element.id = styleId;
        document.querySelector("head").appendChild(element);
      }
      var html = [];
      var resources = scheduler.serverList("board_colors");

      var today = getDate(new Date());
      // generate css styles for each option and write css into the style element,

      resources.forEach(function (r) {
        progress_percent = r.plan_qty > 0 ? (r.production / r.plan_qty) * 100 : 0;
        let background_color;
        let plan_date_end = getDate(new Date(r.plan_date));
        let plan_date_start= getDate(new Date(r.plan_start_date));
        let shipment_date = r.ex_factory_date;
        if (compareDates(new Date(shipment_date), new Date(plan_date_end))) {
          background_color = '#df0000';
        } else if (compareDates(new Date(plan_date_start), new Date(today))) {
          if (progress_percent <= 50) {
            background_color = '#8E0000';
          } else if (progress_percent > 50 && progress_percent <= 70) {
            background_color = '#e7b52e';
          } else {
            background_color = '#08ab09';
          }
        } else {
          background_color = r.backgroundColor;
        }
        html.push(".dhx_cal_event_line.event_resource_" + r.key + "{" +
            "background-color:" + background_color + "!important; " +
            "}" +
            ".dhx_cal_event_line.event_resource_" + r.key + ">.event-progress{" +
            "background-color: rgba(0, 0, 0, 0.17)!important;" +
            "width:" + progress_percent + "%!important;" +
            "height:inherit;" +
            "}" +
            ".dhx_cal_event.event_resource_" + r.key + ">.dhx_body{" +
            "background-color:" + background_color + "!important;" +
            "}" +
            ".dhx_cal_event.event_resource_" + r.key + ">.dhx_title{" +
            "background-color:" + background_color + "!important;" +
            "}" +
            ".dhx_cal_event_clear.event_resource_" + r.key + "{" +
            "color:" + background_color + "!important;" +
            "}"
        );
      });
      element.innerHTML = html.join("");
    });

    scheduler.templates.event_class = function (start, end, event) {
      var css = [];

      if (event.board_color) {
        css.push("event_resource_" + event.id);
      }

      return css.join(" ");
    };
  }

  // parse date to set plan date in html
  function getDate(today) {
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();

    if (dd < 10) {
      dd = '0' + dd
    }

    if (mm < 10) {
      mm = '0' + mm
    }

    today = yyyy + '-' + mm + '-' + dd;
    return today;
  }

  function zoomIn() {
    if (!scheduler.matrix.Timeline.scrollable && scheduler.matrix.Timeline.column_width == 100) {
      scheduler.matrix.Timeline.column_width = 60;
      scheduler.matrix.Timeline.scrollable = true;
    } else if (scheduler.matrix.Timeline.column_width == 60) {
      scheduler.matrix.Timeline.column_width = 120;
    } else if (scheduler.matrix.Timeline.column_width == 120) {
      scheduler.matrix.Timeline.column_width = 150;
    }

    scheduler.setCurrentView();
  }

  function zoomOut() {
    if (scheduler.matrix.Timeline.scrollable && scheduler.matrix.Timeline.column_width == 150) {
      scheduler.matrix.Timeline.column_width = 120;
    } else if (scheduler.matrix.Timeline.scrollable && scheduler.matrix.Timeline.column_width == 120) {
      scheduler.matrix.Timeline.column_width = 60;
    } else if (scheduler.matrix.Timeline.scrollable && scheduler.matrix.Timeline.column_width == 60) {
      scheduler.matrix.Timeline.column_width = 100;
      scheduler.matrix.Timeline.scrollable = false;
    }
    scheduler.setCurrentView();
  }
</script>
</body>
</html>
