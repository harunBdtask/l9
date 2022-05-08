<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
        <meta name="author" content="Bdtask">
        <title>{{$title}} Bhulua - Bootstrap 4 Admin Template Deshboard</title>
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
        <!--<link href="{{asset('assets')}}/dist/css/style.rtl.css" rel="stylesheet">-->
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
                </div><!--/.sidebar header-->
                <div class="sidebar-body">
                    <nav class="sidebar-nav">
                        <ul class="metismenu">
                            <li class="nav-label">
                                <span class="nav-label_text">Main Menu</span>
                                <small class="ti-more-alt nav-label_ellipsis"></small>
                            </li>
                            
                            <li><a href="#"><i class="typcn typcn-messages"></i> Chat</a></li>
                            <li>
                                <a class="has-arrow material-ripple" href="#">
                                    <i class="typcn typcn-mail"></i>
                                    Mailbox
                                </a>
                                <ul class="nav-second-level">
                                    <li><a href="#">Mailbox</a></li>
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
                        </div><!--/.sidebar toggle icon-->
                    </nav><!--/.navbar-->
                    <!--Content Header (Page header)-->
                    @include($content)
                </div><!--/.main content-->
                <footer class="footer-content">
                    <div class="footer-text d-flex align-items-center justify-content-between">
                        <div class="copy">© 2018 Bdtask Responsive Bootstrap 4 Dashboard Template</div>
                        <div class="credit">Designed by: <a href="#">Bdtask</a></div>
                    </div>
                </footer><!--/.footer content-->
                <div class="overlay"></div>
            </div><!--/.wrapper-->
        </div>
        <!--Global script(used by all pages)-->
        <script src="{{asset('assets')}}/plugins/jQuery/jquery.min.js"></script>
        <script src="{{asset('assets')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="{{asset('assets')}}/plugins/metisMenu/metisMenu.min.js"></script>
        <script src="{{asset('assets')}}/plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
        <!-- Third Party Scripts(used by this page)-->
        <script src="{{asset('assets')}}/plugins/vakata-jstree/dist/jstree.min.js"></script>
        <!--Page Active Scripts(used by this page)-->
        <script src="{{asset('assets')}}/dist/js/pages/tree-view.active.js"></script>
        <!--Page Scripts(used by all page)-->
        <script src="{{asset('assets')}}/dist/js/sidebar.js"></script>
        @stack('scripts')
        <!--end::Page Scripts-->
    </body>
</html>