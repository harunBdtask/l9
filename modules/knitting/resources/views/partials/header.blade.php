<div class="navbar">
    <!-- Open side - Naviation on mobile -->
    <a data-toggle="modal" data-target="#aside" class="navbar-item pull-left hidden-lg-up">
        <i class="material-icons">&#xe5d2;</i>
    </a>
    <!-- / -->

    <!-- Page title - Bind to $state's title -->
    <div class="navbar-item pull-left h5" id="pageTitle"></div>

    <!-- navbar right -->
    <ul class="nav navbar-nav pull-right">
        <li class="nav-item dropdown pos-stc-xs">
            <a class="nav-link" href="" data-toggle="dropdown" aria-expanded="true">
                <i class="material-icons"></i>
                <span class="label label-md up warn notification-count">
                    {{ auth()->check() ? auth()->user()->unreadNotifications()->count() : 0 }}
                </span>
            </a>
            @include('skeleton::partials.notification')
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link clear" href data-toggle="dropdown">
                <span class="avatar w-32">
                    @php
                        if(\Auth::check() && \Auth::user()->profile_image == null){
                            $imageHtml = asset('modules/skeleton/flatkit/assets/images/avatar2.png');
                        } elseif (\Auth::check() && Storage::disk('public')->exists('profile_image/'.auth()->user()->profile_image)){
                            $imageHtml = asset('storage/profile_image/'.auth()->user()->profile_image);
                        } else {
                            $imageHtml = asset('modules/skeleton/flatkit/assets/images/avatar2.png');
                        }
                    @endphp
                    <img src="{{ $imageHtml }}" alt="">
                    <i class="on b-white bottom"></i>
                </span>
            </a>
            @include('skeleton::partials.settings-dropdown')
        </li>
        <li class="nav-item hidden-md-up">
            <a class="nav-link" data-toggle="collapse" data-target="#collapse">
                <i class="material-icons">&#xe5d4;</i>
            </a>
        </li>
    </ul>
    <!-- / navbar right -->

    <!-- navbar collapse -->
    <div class="navbar-toggleable-sm" id="collapse">
        <!-- link and dropdown -->
        <ul class="nav navbar-nav">
            <li class="nav-item dropdown">
                <h6 class="nav-link" style="margin: 0;">
{{--                    UNI GEARS LTD.--}} {{ factoryName() }}
                </h6>
                {{--<form action="{{ url('factory-dashboard') }}" style="padding-top: 10px" autocomplete="off">
                    @php
                      $factories = Cache::get('factories') ?? [];
                    @endphp
                    @if((getRole() == 'user'))
                        <a class="nav-link" href data-toggle="dropdown">
                            <span><strong>{{ factoryName() ?? 'Dashboard'}}</strong></span>
                        </a>
                    @else
                        {!! Form::select('factory_id', $factories, Session::get('factoryId'), ['class' => 'form-control form-control-sm c-select', 'onchange' => "this.form.submit()"]) !!}
                    @endif
                </form>--}}
            </li>
        </ul>
        <!-- / -->
    </div>
    <!-- / navbar collapse -->
</div>
