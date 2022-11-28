@extends('skeleton::layout')
@section('title', 'Approval | Print Send Gatepass Challan(Cut Manager)')
@section('styles')
<style>
  .form-control {
      border: 1px solid #909ac8 !important;
      border-radius: 10px 0 0 0;
  }

  input, select {
      min-height: 30px !important;
  }

  .form-control:focus {
      border: 1px solid #ad0ce7 !important;
      border-radius: 8px 0 0 0;
  }

  .req {
      font-size: 1rem;
  }

  .mainForm td, .mainForm th {
      border: none !important;
      padding: .3rem !important;
  }

  li.parsley-required {
      color: red;
      list-style: none;
      text-align: left;
  }

  input.parsley-error,
  select.parsley-error,
  textarea.parsley-error {
      border-color: #843534;
      box-shadow: none;
  }


  input.parsley-error:focus,
  select.parsley-error:focus,
  textarea.parsley-error:focus {
      border-color: #843534;
      box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 6px #ce8483
  }

  .remove-po {
      border: none;
      display: block;
      width: 100%;
      background-color: #843534;
      color: whitesmoke;
  }

  .close-po {
      border: none;
      display: block;
      width: 100%;
      background-color: #6cc788;
      color: whitesmoke;
  }

  /* select2 */
  .select2-container .select2-selection--single {
      height: 35px !important;
      border-radius: 10px 0 0 0 !important;
      line-height: 1.5rem !important;
      border: 1px solid #909ac8 !important;
  }


  .reportTable .select2-container .select2-selection--single {
      border: 1px solid #e7e7e7;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 40px;
      width: 100%;
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow {
      top: 5px !important;
  }

  .error + .select2-container .select2-selection--single {
      border: 1px solid red;
  }

  .select2-container--default .select2-selection--multiple {
      min-height: 35px !important;
      border-radius: 0px;
      width: 100%;
  }

  #vue-loader {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      width: 100%;
      height: 100vh;
      background: #e3dadabf url('/SLS_LOADER.GIF') no-repeat center;
      z-index: 999;
  }

  #loader {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      width: 100%;
      height: 100vh;
      background: #e3dadabf url('/SLS_LOADER.GIF') no-repeat center;
      z-index: 999;
  }

  .spin-loader {
      position: relative;
      top: 46%;
      left: 0;
  }
</style>
@endsection
@section('content')
    <div class="padding">
        <div class="box" style="min-height: 610px">
            <div class="box-header btn-info">
                <h2>
                  Print Send Gatepass Challan(Cut Manager)
                </h2>
            </div>

            <div class="box-body">
                <div class="box-body" id="element">

                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
  var loader;

  function loadNow(opacity) {
      if (opacity <= 0) {
          displayContent();
      } else {
          loader.style.opacity = opacity;
          window.setTimeout(function () {
              loadNow(opacity - 0.05);
          }, 5);
      }
  }

  function displayContent() {
      loader.style.display = 'none';
      document.getElementById('content').style.display = 'block';
  }

  document.addEventListener("DOMContentLoaded", function () {
      loader = document.getElementById('loader');
      loadNow(5);
  });
</script>
<script src="{{ asset('/js/print-send-challan-cut-manager-approval.js') }}"></script>
@endsection
