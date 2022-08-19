<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
    <meta name="author" content="Bhulua">
    <title>{{$title}} Bhulua - Bootstrap 4 {{ get_phrases(['admin', 'template', 'dashboard']) }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets')}}/dist/img/favicon.png">
    <!--Global Styles(used by all pages)-->
    <link href="{{asset('assets')}}/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('assets')}}/plugins/metisMenu/metisMenu.css" rel="stylesheet">
    <link href="{{asset('assets')}}/plugins/fontawesome/css/all.min.css" rel="stylesheet">
    <link href="{{asset('assets')}}/plugins/typicons/src/typicons.min.css" rel="stylesheet">
    <link href="{{asset('assets')}}/plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
    <!--Third party Styles(used by this page)-->
    <link href="{{asset('assets')}}/plugins/vakata-jstree/dist/themes/default/style.min.css" rel="stylesheet">
    <!--Start Your Custom Style Now-->
    <link href="{{asset('assets')}}/dist/css/style.css" rel="stylesheet">
</head>

<body class="fixed">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-green">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <div class="wrapper">
        <!-- Sidebar  -->
        <nav class="sidebar sidebar-bunker">
            <div class="sidebar-header">
                <a href="#" class="sidebar-brand">
                    <img class="sidebar-brand_icon" src="{{asset('assets')}}/dist/img/mini-logo.png" alt="">
                    <span class="sidebar-brand_text">Bhu<span>lua</span></span>
                </a>
            </div>
            <!--/.sidebar header-->
            <div class="sidebar-body">
                <nav class="sidebar-nav">
                    <ul class="metismenu">
                        <li class="nav-label">
                            <span class="nav-label_text">{{ get_phrases(['main', 'menu']) }}</span>
                            <small class="ti-more-alt nav-label_ellipsis"></small>
                        </li>

                        <li class="{{ Request::segment(1)==''?'mm-active':null }}" ><a href="{{ url('/') }}"><i class="typcn typcn-tree"></i> {{ get_phrases(['tree', 'view']) }}</a></li>
                        <li><a href="{{ url('/posts') }}"><i class="typcn typcn-ticket"></i> {{ get_phrases(['post', 'vue']) }}</a></li>
                        <li>
                            <a class="has-arrow material-ripple" href="#">
                                <i class="typcn typcn-cog"></i>
                                {{ get_phrases(['application', 'settings']) }}
                            </a>
                            <ul class="nav-second-level">
                                <li class="{{ Request::segment(1)=='language_settings'?'mm-active':null }}"><a href="{{ url('/language_settings') }}">{{ get_phrases(['language']) }}</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div><!-- sidebar-body -->
        </nav>
        <!-- Page Content  -->
        <div class="content-wrapper">
            <div class="main-content">
                <!--Navbar-->
                <nav class="navbar-custom-menu navbar navbar-expand-xl m-0">
                    <div class="sidebar-toggle-icon" id="sidebarCollapse">
                        sidebar toggle<span></span>
                    </div>
                    <!--/.sidebar toggle icon-->
                    <div class="navbar-icon d-flex">
                        <ul class="navbar-nav flex-row align-items-center">
                            {{-- notification start --}}
                            <li class="nav-item dropdown notification">
                                <a class="nav-link dropdown-toggle material-ripple badge-dot" href="#"
                                    data-toggle="dropdown">
                                    <i class="typcn typcn-bell"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <h6 class="notification-title">Notifications</h6>
                                    <p class="notification-text">You have 2 unread notification</p>
                                    <div class="notification-list">
                                        <div class="media new">
                                            <div class="img-user"><img src="{{asset('assets')}}/dist/img/avatar.png" alt=""></div>
                                            <div class="media-body">
                                                <h6>Congratulate <strong>Socrates Itumay</strong> for work anniversaries
                                                </h6>
                                                <span>Mar 15 12:32pm</span>
                                            </div>
                                        </div>
                                        <div class="media">
                                            <div class="img-user"><img src="{{asset('assets')}}/dist/img/avatar4.png" alt=""></div>
                                            <div class="media-body">
                                                <h6><strong>Adrian Monino</strong> added new comment on your photo</h6>
                                                <span>Mar 12 10:40pm</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown-footer"><a href="{{ url('/show-notifications') }}">View All Notifications</a></div>
                                </div>
                            </li>
                            {{-- notification end --}}
                            <li class="nav-item dropdown user-menu">
                                <a class="nav-link dropdown-toggle material-ripple" href="#" data-toggle="dropdown">
                                    <i class="typcn typcn-user-add-outline"></i>
                                </a>
                                <div class="dropdown-menu" >
                                    <div class="dropdown-header d-sm-none">
                                        <a href="" class="header-arrow"><i class="icon ion-md-arrow-back"></i></a>
                                    </div>
                                    <div class="user-header">
                                        <div class="img-user">
                                            <img src="{{asset('assets')}}/dist/img/avatar-1.jpg" alt="">
                                        </div><!-- img-user -->
                                        <h6>{{ Auth::user()->name }}</h6>
                                        <span>{{ Auth::user()->email }}</span>
                                    </div><!-- user-header -->
                                    <a href="" class="dropdown-item"><i class="typcn typcn-user-outline"></i> My Profile</a>
                                    <a href="{{ route('logout') }}" 
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();" 
                                        class="dropdown-item"><i class="typcn typcn-key-outline"></i> Sign Out</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                </div><!--/.dropdown-menu -->
                            </li>
                        </ul>
                    </div>
                </nav>
                <!--/.navbar-->
                <!--Content Header (Page header)-->
                @include($content)
            </div>
            <!--/.main content-->
            <footer class="footer-content">
                <div class="footer-text d-flex align-items-center justify-content-between">
                    <div class="copy">© 2018 {{ get_phrases(['admin', 'template', 'dashboard']) }}</div>
                    <div class="credit">{{ get_phrases(['designed', 'by']) }} : <a href="#">Bhulua</a></div>
                </div>
            </footer>
            <!--/.footer content-->
            <div class="overlay"></div>
        </div>
        <!--/.wrapper-->
    </div>
    <!--Global script(used by all pages)-->
    <script src="{{asset('assets')}}/plugins/jQuery/jquery.min.js"></script>
    <script src="{{asset('assets')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('assets')}}/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- dataTables -->
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <!-- dataTables -->
    <script src="{{asset('assets')}}/plugins/metisMenu/metisMenu.min.js"></script>
    <script src="{{asset('assets')}}/plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
    <!-- Third Party Scripts(used by this page)-->
    <script src="{{asset('assets')}}/plugins/vakata-jstree/dist/jstree.min.js"></script>
    <!--Page Active Scripts(used by this page)-->
    <script src="{{asset('assets')}}/dist/js/pages/tree-view.active.js"></script>
    <!--Page Scripts(used by all page)-->
    <script src="{{asset('assets')}}/dist/js/sidebar.js"></script>
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>

    @stack('scripts')
    <!--end::Page Scripts-->
</body>

</html>