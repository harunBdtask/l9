@extends('iedroplets::layout')
@section('title', 'Table Wise Cutting Target Plan ')
@section('styles')
<style type="text/css">
  /* @media screen and (-webkit-min-device-pixel-ratio: 0) {
    input[type=date].form-control form-control-sm {
      height: 33px !important;
      line-height: 1;
    }
  }

  .select2-container .select2-selection--single {
    height: 33px;
    padding-top: 3px !important;
  }

  .form-control form-control-sm {
    line-height: 1;
    min-height: 1rem !important;
  }

  .tr-height > td {
    padding-left: 15px !important;
    font-size: 12px !important;
  } */
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
<div class="padding sewing-line-target-page">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div id="cutting-target-plan-v2">

        </div>
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
  <script src="{{ mix('/js/protracker/iedroplets/cutting_target_plan_v2.js') }}"></script>
@endsection