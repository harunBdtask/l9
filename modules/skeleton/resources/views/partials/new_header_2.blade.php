<div class="navbar">
    <a data-toggle="modal" data-target="#aside" class="navbar-item pull-left hidden-lg-up">
        <i class="material-icons"></i>
    </a>
    <a data-toggle="collapse" data-target="#navbar-3" class="navbar-item pull-right hidden-md-up m-a-0 m-l">
        <i class="material-icons"></i>
    </a>
    <!-- brand -->
    <a class="navbar-brand">
        @php
            $company_logo = asset('flatkit/assets/images/company-image.png');
            if (session()->get('getCompanyLogo') && Storage::disk('public')->exists('company/'.session()->get('getCompanyLogo'))) {
              $company_logo = asset('storage/company/'.session()->get('getCompanyLogo'));
            }
        @endphp
        <img src="{{ $company_logo }}">
    </a>
    <!-- / brand -->
@php
    if(\Auth::check() && \Auth::user()->profile_image == null){
        $imageHtml = asset('modules/skeleton/flatkit/assets/images/avatar2.png');
    } elseif (\Auth::check() && Storage::disk('public')->exists('profile_image/'.auth()->user()->profile_image)){
        $imageHtml = asset('storage/profile_image/'.auth()->user()->profile_image);
    } else{
        $imageHtml = asset('modules/skeleton/flatkit/assets/images/avatar2.png');
    }
@endphp
<!-- nabar right -->
    <ul class="nav navbar-nav pull-right">
        <li class="nav-item dropdown">
            <a href="{{ url('management-dashboard') }}" class="nav-link clear">
                All Reports
            </a>
        </li>
        <li class="nav-item dropdown pos-stc-xs">
            <a class="nav-link" href="" data-toggle="dropdown" onclick="activeNotifyBell()">
                <i class="material-icons"></i>
                <span class="label label-sm up danger">
                    {{ auth()->check() ? auth()->user()->unreadNotifications()->count() : 0 }}
                </span>
            </a>

            <!-- dropdown -->
            <div class="dropdown-menu pull-right w-xl animated fadeInUp no-bg no-border no-shadow">
                <div class="scrollable" style="max-height: 282px">
                    <ul class="list-group list-group-gap m-a-0"
                        id="notification-view">
                        <li class="list-group-item dark text-white box-shadow-z0 b">
                            <span class="clear block"> Openning... </span>
                        </li>
                    </ul>
                </div>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a href="" class="nav-link dropdown-toggle clear" data-toggle="dropdown">
                  <span class="hidden-md-down nav-text m-r-sm text-right">
                        <span class="block l-h-1x _500">{{ factoryName() }}</span>
                        <small class="block l-h-1x text-muted">
                            <i class="material-icons text-md"></i> {{ substr(factoryAddress(), 0, 55) }}
                        </small>
                  </span>
                  <span class="avatar w-32">
                        <img src="{{ $imageHtml }}" alt="...">
                        <i class="away b-white left"></i>
                  </span>
            </a>
            @include('skeleton::partials.settings-dropdown')
        </li>
    </ul>
    <!-- / navbar right -->
    <!-- navbar collapse -->
    <div class="collapse navbar-toggleable-sm" id="navbar-3">

        <!-- search form -->
        @if((getRole() !== 'user'))
            <form class="navbar-form form-inline pull-right pull-none-sm navbar-item v-m" role="search">
                <div class="form-group l-h m-a-0">
                    <div class="input-group input-group-sm">
                        <input type="text" id="autocomplete_id"
                               class="form-control form-control-sm p-x b-a rounded top_search"
                               placeholder="Search...">
                        <span class="input-group-btn">
                        <button type="submit" class="btn white b-a rounded no-shadow"><i
                                    class="fa fa-search"></i>
                        </button>
                    </span>
                    </div>
                    <div id="__searchitWrapper2">
                        <div id="listBox2">

                        </div>
                    </div>
                </div>
            </form>
            <!-- / search form -->

            <!-- link and dropdown -->
            <ul class="nav navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="" data-toggle="dropdown">
                        <span>{{ sessionFactoryName() ?? factoryName() }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-scale text-color" role="menu">
                        @php
                            $factories = Cache::get('factories') ?? [];
                        @endphp

                        @foreach($factories as $key => $factory)
                            <a onclick="submitHeaderFactory({{$key}})"
                               class="dropdown-item" href="javascript:;">{{ $factory }}</a>
                        @endforeach
                    </div>

                    <form action="{{ url('factory-dashboard') }}" id="header_factory_form"
                          style="display: none"
                          autocomplete="off">
                        <input type="hidden" name="factory_id" id="header_factory_id">
                    </form>
                </li>
            </ul>
    @endif
    <!-- / link and dropdown -->
    </div>
    <!-- / navbar collapse -->
</div>