<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>goRMG | An Ultimate ERP Solutions For Garments</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" sizes="196x196" href="{{ asset('modules/skeleton/flatkit/assets/images/gormg_fav_ico.png') }}">

  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/bootstrap/dist/bootstrap4/bootstrap4.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/font-awesome/css/font-awesome.min.css') }}" type="text/css" />
  <!-- {{-- <link rel="stylesheet" href="assets/css/login.css">  --}} -->
  <link rel="stylesheet" href="{{ asset('modules/skeleton/css/login.css') }}" type="text/css" />

</head>

<body>
  <main>

    <div class="row d-none d-sm-block ">
      <div class="col-4 topBar"></div>
      <div class="col-4 bottomBar"></div>
    </div>

    <div class="row justify-content-center align-items-center text-center" style="height: 100vh;">
      <div class="card m-2 p-2 m-sm-4 p-sm-5 py-4 border border-0 login_card">
        <div class="row justify-content-center align-items-center">
          <div class="col-md-6 d-none d-md-block">
            <div class="col-md-12">
              <img class="img-fluid" src="{{ asset('modules/skeleton/img/'.(env('APP_LOGO') ?: 'login2').'.png') }}" alt="login" class="login-card-img">
            </div>
          </div>
          <div class="col-md-6">
            <div class="col-md-10 col-sm-12">
              <div class="d-flex justify-content-between align-items-center pb-4">
                @php
                $company_logo = asset('flatkit/assets/images/company-image.png');
                if (session()->get('getCompanyLogo') && Storage::disk('public')->exists('company/'.session()->get('getCompanyLogo'))) {
                $company_logo = asset('storage/company/'.session()->get('getCompanyLogo'));
                }
                @endphp
                <div style="cursor: pointer" id="gormg-logo">
                  <img class="logo_img img-fluid" src="{{ asset('modules/skeleton/img/logo/'.(env('APP_LOGO') ?: 'erp').'.png') }}">
                </div>
                <div>
                  <hr class="straightBar">
                </div>
                <div>
                  <img class="logo_img img-fluid" src="{{ $company_logo }}">
                </div>
              </div>

              @if(Session::has('error'))
              <div class="text-danger text-center">{{ Session::get('error') }}</div>
              @endif
              <form action="{{ url('/post-login') }}" method="post" class="text-center" name="form">
                {!! csrf_field() !!}
                <div class="form-group">
                  <label for="email" class="sr-only">Email</label>
                  <input type="email" id="email" class="form-control py-4" name="email" ng-model="user.email" placeholder="Email address">
                  @if($errors->has('email'))
                  <span class="text-danger">{{ $errors->first('email') }}</span>
                  @endif
                </div>
                <div class="form-group mb-4">
                  <label for="password" class="sr-only">Password</label>
                  <input type="password" name="password" ng-model="user.password" id="password" class="form-control py-4" placeholder="Password">
                  @if($errors->has('password'))
                  <span class="text-danger">{{ $errors->first('password') }}</span>
                  @endif
                </div>

                <div class="custom-control custom-checkbox mb-4">
                  <input type="checkbox" class="custom-control-input" id="customCheck1">
                  <label class="custom-control-label" for="customCheck1">Remember me</label>
                </div>

                <input name="login" id="login" class="w-100 login_btn mb-4 text-white" type="submit" value="Login">
              </form>
              <!-- <a class="forgetPassword d-flex justify-content-center" href="">Forgot Password?</a> -->
              <p class="text-center">Social Engagement</p>
              <div class="row justify-content-center">
                <div class="facebook social_icon text-center" style="background-color: #3b5998;">
                  <div class="fa fa-facebook"></div>
                </div>
                <div class="twitter social_icon text-center" style="background-color: #1da1f2;">
                  <div class="fa fa-twitter"></div>
                </div>
                <div class="linkedin social_icon text-center" style="background-color: #2867b2;">
                  <div class="fa fa-linkedin"></div>
                </div>
                <div class="youtube social_icon text-center" style="background-color: #5f92b1;">
                  <div class="fa fa-youtube"></div>
                </div>
                <div class="website social_icon text-center" style="background-color: #1da1f2;">
                  <div class="fa fa-globe"></div>
                </div>
              </div>

              <div class="d-flex justify-content-center py-4 other_logos">
                <img class="img-fluid pr-3 first_img" src="{{ asset('/images/protracker_logo.png') }}" alt="Protracker" id="protracker-logo">
                <!-- <img class="img-fluid d-none d-lg-block" src="{{ asset('/images/knitracker_logo.png') }}" alt="Knitracker" id="knitracker-logo"> -->
                <img class="img-fluid pl-3 second_img" src="{{ asset('/images/vatax_logo.png') }}" alt="Vatax" id="vatax-logo">
              </div>

              <div class="d-flex justify-content-center align-items-center" style="height: auto;">
                <span>Product&nbsp;By &nbsp;</span>
                <img class="img-fluid footer_logo" src="{{ asset('modules/skeleton/img/'.(env('APP_LOGO') ?: 'Skylarksoft').'.png') }}" alt="">
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>


  </main>
  <script src="{{ asset('modules/skeleton/lib/jquery/jquery.js') }}"></script>
  <script src="{{ asset('modules/skeleton/lib/bootstrap/bootstrap.js') }}"></script>
  <script>
    $(document).on('click', '#gormg-logo', function(e) {
      e.preventDefault();
      window.open('https://www.skylarksoft.com/garments-erp-software-gormg-erp/', '_blank')
    });
    $(document).on('click', '#protracker-logo', function(e) {
      e.preventDefault();
      window.open('https://www.skylarksoft.com/protracker-realtime-automated-production-tracking-system-for-garments/', '_blank')
    });
    $(document).on('click', '#knitracker-logo', function(e) {
      e.preventDefault();
      window.open('https://www.skylarksoft.com/knitracker-barcode-enabled-knitting-fabric-roll-tracking-system/', '_blank')
    });
    $(document).on('click', '#vatax-logo', function(e) {
      e.preventDefault();
      window.open('https://www.skylarksoft.com/vat-management-software-vatax/', '_blank')
    });
    $(document).on('click', '.facebook', function(e) {
      e.preventDefault();
      window.open('https://www.facebook.com/skylarksoft/', '_blank')
    });
    $(document).on('click', '.twitter', function(e) {
      e.preventDefault();
      window.open('https://twitter.com/SkylarkSoftLtd', '_blank')
    });
    $(document).on('click', '.linkedin', function(e) {
      e.preventDefault();
      window.open('https://www.linkedin.com/company/skylark-soft-limited/', '_blank')
    });
    $(document).on('click', '.youtube', function(e) {
      e.preventDefault();
      window.open('https://www.youtube.com/channel/UCkjmSyy_B58KKmVmb9sjF7Q', '_blank')
    });
    $(document).on('click', '.website', function(e) {
      e.preventDefault();
      window.open('https://www.skylarksoft.com/', '_blank')
    });
  </script>

</body>

</html>