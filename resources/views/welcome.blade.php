<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
        <meta name="author" content="Bdtask">
        <title>Bhulua - Bootstrap 4 Admin Template Deshboard</title>
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('assets')}}/dist/img/favicon.png">
        <!--Global Styles(used by all pages)-->
        <link href="{{asset('assets')}}/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!--<link href="{{asset('assets')}}/plugins/bootstrap/css/rtl/bootstrap-rtl.min.css" rel="stylesheet">-->
        <link href="{{asset('assets')}}/plugins/metisMenu/metisMenu.css" rel="stylesheet">
        <!--<link href="{{asset('assets')}}/plugins/metisMenu/metisMenu-rtl.css" rel="stylesheet">-->
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
                    <a href="index.html" class="sidebar-brand">
                        <img class="sidebar-brand_icon" src="{{asset('assets')}}/dist/img/mini-logo.png" alt="">
                        <span class="sidebar-brand_text">Bhu<span>lua</span></span>
                    </a>
                </div><!--/.sidebar header-->
                <div class="profile-element d-flex align-items-center flex-shrink-0">
                    <div class="avatar online">
                        <img src="{{asset('assets')}}/dist/img/avatar-1.jpg" class="img-fluid rounded-circle" alt="">
                    </div>
                    <div class="profile-text">
                        <h6 class="m-0">Naeem Khan</h6>
                        <span>example@gmail.com</span>
                    </div>
                </div><!--/.profile element-->
                <form class="search sidebar-form" action="#" method="get" >
                    <div class="search__inner">
                        <input type="text" class="search__text" placeholder="Search...">
                        <i class="typcn typcn-zoom-outline search__helper" data-sa-action="search-close"></i>
                    </div>
                </form><!--/.search-->
                <div class="sidebar-body">
                    <nav class="sidebar-nav">
                        <ul class="metismenu">
                            <li class="nav-label">
                                <span class="nav-label_text">Main Menu</span>
                                <small class="ti-more-alt nav-label_ellipsis"></small>
                            </li>
                            <li>
                                <a class="has-arrow material-ripple" href="#">
                                    <i class="typcn typcn-home-outline"></i>
                                    Dashboard
                                </a>
                                <ul class="nav-second-level">
                                    <li><a href="index.html">Default</a></li>
                                    <li><a href="dashboard_two.html">Dashboard Two</a></li>
                                </ul>
                            </li>
                            <li>
                                <a class="has-arrow material-ripple" href="#">
                                    <i class="typcn typcn-chart-pie-outline"></i>
                                    Charts
                                </a>
                                <ul class="nav-second-level">
                                    <li><a href="charts_flot.html">Flot Chart</a></li>
                                    <li><a href="charts_Js.html">Chart js</a></li>
                                    <li><a href="charts_morris.html">Morris Charts</a></li>
                                    <li><a href="charts_sparkline.html">Sparkline Charts</a></li>
                                    <li><a href="charts_am.html">Am Charts</a></li>
                                    <li><a href="charts_apex.html">Chart Apex</a></li>
                                </ul>
                            </li>
                            <li><a href="chat.html"><i class="typcn typcn-messages"></i> Chat</a></li>
                            <li>
                                <a class="has-arrow material-ripple" href="#">
                                    <i class="typcn typcn-mail"></i>
                                    Mailbox
                                </a>
                                <ul class="nav-second-level">
                                    <li><a href="mailbox.html">Mailbox</a></li>
                                    <li><a href="mailbox_details.html">Mailbox Details</a></li>
                                    <li><a href="compose.html">Compose</a></li>
                                </ul>
                            </li>
                            <li>
                                <a class="has-arrow material-ripple" href="#">
                                    <i class="typcn typcn-archive"></i>
                                    Tables
                                </a>
                                <ul class="nav-second-level">
                                    <li><a href="tables_bootstrap.html">Bootstrap tables</a></li>
                                    <li>
                                        <a class="has-arrow" href="#" aria-expanded="false">Data tables</a>
                                        <ul class="nav-third-level">
                                            <li><a href="tables_data_basic.html">Basic initialization</a></li>
                                            <li><a href="tables_data_sources.html">Data sources</a></li>
                                            <li><a href="tables_data_api.html">API</a></li>
                                            <li><a href="tables_data_styling.html">Styling</a></li>
                                            <li><a href="tables_data_advanced.html">Advanced initialization</a></li>
                                            <li><a href="tables_data_bootstrap4.html">Bootstrap4</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="tables_foo.html">FooTable</a></li>
                                    <li><a href="tables_jsgrid.html">Jsgrid table</a></li>
                                </ul>
                            </li>
                            <li>
                                <a class="has-arrow material-ripple" href="#">
                                    <i class="typcn typcn-clipboard"></i>
                                    Forms
                                </a>
                                <ul class="nav-second-level">
                                    <li><a href="forms_basic.html">Basic Forms</a></li>
                                    <li><a href="forms_input_group.html">Input group</a></li>
                                    <li><a href="forms_mask.html">Form Mask</a></li>
                                    <li><a href="forms_touchspin.html">Touchspin</a></li>
                                    <li><a href="forms_select.html">Select</a></li>
                                    <li><a href="forms_cropper.html">Cropper</a></li>
                                    <li><a href="forms_file_upload.html">Forms File Upload</a></li>
                                    <li><a href="forms_editor_ck.html">CK Editor</a></li>
                                    <li><a href="forms_editor_summernote.html">Summernote</a></li>
                                    <li><a href="forms_wizard.html">Form Wizaed</a></li>
                                    <li><a href="forms_editor_markdown.html">Markdown</a></li>
                                    <li><a href="forms_editor_trumbowyg.html">Trumbowyg</a></li>
                                    <li><a href="forms_editor_wysihtml5.html">Wysihtml5</a></li>
                                </ul>
                            </li>
                            <li class="nav-label">
                                <span class="nav-label_text">Components</span>
                                <small class="ti-more-alt nav-label_ellipsis"></small>
                            </li>
                            <li class="mm-active">
                                <a class="has-arrow material-ripple" href="#">
                                    <i class="typcn typcn-coffee"></i>
                                    UI Elements
                                </a>
                                <ul class="nav-second-level">
                                    <li><a href="ui_buttons.html">Buttons</a></li>
                                    <li><a href="ui_badges.html">Badges</a></li>
                                    <li><a href="ui_spinners.html">Spinners</a></li>
                                    <li><a href="ui_tabs.html">Tab</a></li>
                                    <li><a href="ui_notification.html">Notification</a></li>
                                    <li class="mm-active"><a href="ui_tree_view.html">Tree View</a></li>
                                    <li><a href="ui_progressbars.html">Progressber</a></li>
                                    <li><a href="ui_list_view.html">List View</a></li>
                                    <li><a href="ui_ratings.html">Ratings</a></li>
                                    <li><a href="ui_datetime_picker.html">Date & Time Picker</a></li>
                                    <li><a href="ui_typography.html">Typography</a></li>
                                    <li><a href="ui_modals.html">Modals</a></li>
                                    <li><a href="ui_icheck_toggle_pagination.html">iCheck, Toggle, pagination</a></li>
                                </ul>
                            </li>
                            <li>
                                <a class="has-arrow material-ripple" href="#">
                                    <i class="typcn typcn-world-outline"></i>
                                    Maps
                                </a>
                                <ul class="nav-second-level">
                                    <li><a href="maps_amcharts.html">Amcharts Map</a></li>
                                    <li><a href="maps_gmaps.html">gMaps</a></li>
                                    <li><a href="maps_data.html">Data Maps</a></li>
                                    <li><a href="maps_jvector.html">Jvector Maps</a></li>
                                    <li><a href="maps_google.html">Google map</a></li>
                                    <li><a href="maps_snazzy.html">Snazzy Map</a></li>
                                </ul>
                            </li>
                            <li>
                                <a class="has-arrow material-ripple" href="#">
                                    <i class="typcn typcn-info-large-outline"></i>
                                    Icons
                                </a>
                                <ul class="nav-second-level">
                                    <li><a href="icons_bootstrap.html">Bootstrap Icons</a></li>
                                    <li><a href="icons_fontawesome.html">Fontawesome Icon</a></li>
                                    <li><a href="icons_flag.html">Flag Icons</a></li>
                                    <li><a href="icons_material.html">Material Icons</a></li>
                                    <li><a href="icons_weather.html">Weather Icons </a></li>
                                    <li><a href="icons_line.html">Line Icons</a></li>
                                    <li><a href="icons_pe.html">Pe Icons</a></li>
                                    <li><a href="icon_socicon.html">Socicon Icons</a></li>
                                    <li><a href="icons_typicons.html">Typicons Icons</a></li>
                                </ul>
                            </li>
                            <li><a href="widgets.html"><i class="typcn typcn-gift"></i>Widgets</a></li>
                            <li><a href="calender.html"><i class="typcn typcn-calendar-outline"></i>Calendar</a></li>
                            <li class="nav-label">
                                <span class="nav-label_text">Extra</span>
                                <small class="ti-more-alt nav-label_ellipsis"></small>
                            </li>
                            <li>
                                <a class="has-arrow material-ripple" href="#">
                                    <i class="typcn typcn-device-tablet"></i>
                                    App Views
                                </a>
                                <ul class="nav-second-level">
                                    <li><a href="invoice.html">Invoice</a></li>
                                    <li><a href="invoice2.html">Invoice two</a></li>
                                    <li><a href="timeline_horizontal.html">Horizontal timeline</a></li>
                                    <li><a href="timeline_vertical.html">Vertical timeline</a></li>
                                    <li><a href="pricing.html">Pricing Table</a></li>
                                    <li><a href="range_slider.html">Range Slider</a></li>
                                    <li><a href="carousel.html">Carousel</a></li>
                                    <li><a href="code_editor.html">Code editor</a></li>
                                    <li><a href="gridSystem.html">Grid System</a></li>
                                </ul>
                            </li>
                            <li>
                                <a class="has-arrow material-ripple" href="#">
                                    <i class="typcn typcn-book"></i>
                                    Authentication
                                </a>
                                <ul class="nav-second-level">
                                    <li><a href="login.html">Login</a></li>
                                    <li><a href="register.html">Register</a></li>
                                    <li><a href="user_profile.html">Profile</a></li>
                                    <li><a href="forget_password.html">Forget password</a></li>
                                    <li><a href="lockscreen.html">Lockscreen</a></li>
                                    <li><a href="404.html">404 Error</a></li>
                                    <li><a href="505.html">505 Error</a></li>
                                </ul>
                            </li>
                            <li>
                                <a class="has-arrow material-ripple" href="#">
                                    <i class="typcn typcn-flow-merge"></i>
                                    Multi Level Menu
                                </a>
                                <ul class="nav-second-level">
                                    <li><a href="#">Menu Item</a></li>
                                    <li><a href="#">Menu Item - 2</a></li>
                                    <li>
                                        <a class="has-arrow" href="#" aria-expanded="false">Level - 2</a>
                                        <ul class="nav-third-level">
                                            <li><a href="#">Menu Item</a></li>
                                            <li>
                                                <a class="has-arrow" href="#" aria-expanded="false">Level - 3</a>
                                                <ul class="nav-fourth-level">
                                                    <li><a href="#">Level - 4</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="blank-page.html"><i class="typcn typcn-bookmark"></i>Blank page</a></li>
                            <li>
                                <a class="has-arrow material-ripple" href="#">
                                    <i class="typcn typcn-puzzle-outline"></i>
                                    Layouts
                                </a>
                                <ul class="nav-second-level">
                                    <li><a href="layouts_layout.html">Layout</a></li>
                                    <li><a href="layouts_fixed.html">Fixed layout</a></li>
                                    <li><a href="layouts_fixed-without__navbar.html">Fixed layout without navbar</a></li>
                                </ul>
                            </li>
                            <li><a href="changelog.html"><i class="typcn typcn-attachment-outline"></i>Changelog<span class="badge badge-success">v1.1.0</span></a></li>
                            <li><a href="#"><i class="typcn typcn-support"></i>Documentation</a></li>
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
                        <!-- Collapse -->
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <!-- Toggler -->
                            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="true" aria-label="Toggle navigation"><span></span> <span></span></button>
                            <ul class="navbar-nav">
                                <li class="nav-item active">
                                    <a class="nav-link" href="#"><i class="typcn typcn-social-dribbble top-menu-icon"></i>Buyer's & Style <span class="sr-only">(current)</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#"><i class="typcn typcn-point-of-interest-outline top-menu-icon"></i>Management</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#"><i class="typcn typcn-group-outline top-menu-icon"></i>Production</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle material-ripple" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="typcn typcn-weather-stormy top-menu-icon"></i>Salary Rule
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <div class="dropdown-menu-scroll">
                                            <a class="dropdown-item" href="#">Salary Break Down</a>
                                            <a class="dropdown-item" href="#">Salary Head</a>
                                            <a class="dropdown-item" href="#">Bonus Types</a>
                                            <a class="dropdown-item" href="#">Bonus Allocation</a>
                                            <a class="dropdown-item" href="#">Bonus Rule</a>
                                            <a class="dropdown-item" href="#">Bonus Stamp Charge</a>
                                            <a class="dropdown-item" href="#">OverTime Setting</a>
                                            <a class="dropdown-item" href="#">Absent Planing</a>
                                            <a class="dropdown-item" href="#">Salary Head Planning</a>
                                            <a class="dropdown-item" href="#">Salary Benefits</a>
                                            <a class="dropdown-item" href="#">Spot Award</a>
                                            <a class="dropdown-item" href="#">Salary Payment Mode</a>
                                            <a class="dropdown-item" href="#">Attendances Late Planning</a>
                                            <a class="dropdown-item" href="#">Salary Planning</a>
                                            <a class="dropdown-item" href="#">Holiday Allowance Settings</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle material-ripple" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="typcn typcn-lightbulb top-menu-icon"></i>Salary Setting
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <div class="dropdown-menu-scroll">
                                            <a class="dropdown-item" href="#">Salary Break Down</a>
                                            <a class="dropdown-item" href="#">Salary Head</a>
                                            <a class="dropdown-item" href="#">Bonus Types</a>
                                            <a class="dropdown-item" href="#">Bonus Allocation</a>
                                            <a class="dropdown-item" href="#">Bonus Rule</a>
                                            <a class="dropdown-item" href="#">Bonus Stamp Charge</a>
                                            <a class="dropdown-item" href="#">OverTime Setting</a>
                                            <a class="dropdown-item" href="#">Absent Planing</a>
                                            <a class="dropdown-item" href="#">Salary Head Planning</a>
                                            <a class="dropdown-item" href="#">Salary Benefits</a>
                                            <a class="dropdown-item" href="#">Spot Award</a>
                                            <a class="dropdown-item" href="#">Salary Payment Mode</a>
                                            <a class="dropdown-item" href="#">Attendances Late Planning</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">
                                        <i class="typcn typcn-leaf top-menu-icon"></i>Account Manager
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="navbar-icon d-flex">
                            <ul class="navbar-nav flex-row align-items-center">
                                <li class="nav-item dropdown quick-actions">
                                    <a class="nav-link dropdown-toggle material-ripple" href="#" data-toggle="dropdown">
                                        <i class="typcn typcn-th-large-outline"></i>
                                    </a>
                                    <div class="dropdown-menu">
                                        <div class="nav-grid-row row">
                                            <a href="#" class="icon-menu-item col-4">
                                                <i class="typcn typcn-cog-outline d-block"></i>
                                                <span>Settings</span>
                                            </a>
                                            <a href="#" class="icon-menu-item col-4">
                                                <i class="typcn typcn-group-outline d-block"></i>
                                                <span>Users</span>
                                            </a>
                                            <a href="#" class="icon-menu-item col-4">
                                                <i class="typcn typcn-puzzle-outline d-block"></i>
                                                <span>Components</span>
                                            </a>
                                            <a href="#" class="icon-menu-item col-4">
                                                <i class="typcn typcn-chart-bar-outline d-block"></i>
                                                <span>Profits</span>
                                            </a>
                                            <a href="#" class="icon-menu-item col-4">
                                                <i class="typcn typcn-time d-block"></i>
                                                <span>New Event</span>
                                            </a>
                                            <a href="#" class="icon-menu-item col-4">
                                                <i class="typcn typcn-edit d-block"></i>
                                                <span>Tasks</span>
                                            </a>
                                        </div>
                                    </div>
                                </li><!--/.dropdown-->
                                <li class="nav-item">
                                    <a class="nav-link material-ripple" href="#" id="btnFullscreen"><i class="full-screen_icon typcn typcn-arrow-move-outline"></i></a>
                                </li>
                                <li class="nav-item dropdown notification">
                                    <a class="nav-link dropdown-toggle material-ripple badge-dot" href="#" data-toggle="dropdown">
                                        <i class="typcn typcn-bell"></i>
                                    </a>
                                    <div class="dropdown-menu">
                                        <h6 class="notification-title">Notifications</h6>
                                        <p class="notification-text">You have 2 unread notification</p>
                                        <div class="notification-list">
                                            <div class="media new">
                                                <div class="img-user"><img src="{{asset('assets')}}/dist/img/avatar.png" alt=""></div>
                                                <div class="media-body">
                                                    <h6>Congratulate <strong>Socrates Itumay</strong> for work anniversaries</h6>
                                                    <span>Mar 15 12:32pm</span>
                                                </div>
                                            </div><!--/.media -->
                                            <div class="media new">
                                                <div class="img-user online"><img src="{{asset('assets')}}/dist/img/avatar2.png" alt=""></div>
                                                <div class="media-body">
                                                    <h6><strong>Joyce Chua</strong> just created a new blog post</h6>
                                                    <span>Mar 13 04:16am</span>
                                                </div>
                                            </div><!--/.media -->
                                            <div class="media">
                                                <div class="img-user"><img src="{{asset('assets')}}/dist/img/avatar3.png" alt=""></div>
                                                <div class="media-body">
                                                    <h6><strong>Althea Cabardo</strong> just created a new blog post</h6>
                                                    <span>Mar 13 02:56am</span>
                                                </div>
                                            </div><!--/.media -->
                                            <div class="media">
                                                <div class="img-user"><img src="{{asset('assets')}}/dist/img/avatar4.png" alt=""></div>
                                                <div class="media-body">
                                                    <h6><strong>Adrian Monino</strong> added new comment on your photo</h6>
                                                    <span>Mar 12 10:40pm</span>
                                                </div>
                                            </div><!--/.media -->
                                        </div><!--/.notification -->
                                        <div class="dropdown-footer"><a href="">View All Notifications</a></div>
                                    </div><!--/.dropdown-menu -->
                                </li><!--/.dropdown-->
                                <li class="nav-item dropdown user-menu">
                                    <a class="nav-link dropdown-toggle material-ripple" href="#" data-toggle="dropdown">
                                        <!--<img src="{{asset('assets')}}/dist/img/user2-160x160.png" alt="">-->
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
                                            <h6>Naeem Khan</h6>
                                            <span>example@gmail.com</span>
                                        </div><!-- user-header -->
                                        <a href="" class="dropdown-item"><i class="typcn typcn-user-outline"></i> My Profile</a>
                                        <a href="" class="dropdown-item"><i class="typcn typcn-edit"></i> Edit Profile</a>
                                        <a href="" class="dropdown-item"><i class="typcn typcn-arrow-shuffle"></i> Activity Logs</a>
                                        <a href="" class="dropdown-item"><i class="typcn typcn-cog-outline"></i> Account Settings</a>
                                        <a href="page-signin.html" class="dropdown-item"><i class="typcn typcn-key-outline"></i> Sign Out</a>
                                    </div><!--/.dropdown-menu -->
                                </li>
                            </ul><!--/.navbar nav-->
                            <div class="nav-clock">
                                <div class="time">
                                    <span class="time-hours"></span>
                                    <span class="time-min"></span>
                                    <span class="time-sec"></span>
                                </div>
                            </div><!-- nav-clock -->
                        </div>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="typcn typcn-th-menu-outline"></i>
                        </button>
                    </nav><!--/.navbar-->
                    <!--Content Header (Page header)-->
                    <div class="content-header row align-items-center m-0">
                        <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
                            <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="#">Forms</a></li>
                                <li class="breadcrumb-item active">Select</li>
                            </ol>
                        </nav>
                        <div class="col-sm-8 header-title p-0">
                            <div class="media">
                                <div class="header-icon text-success"><i class="typcn typcn-puzzle-outline"></i></div>
                                <div class="media-body">
                                    <h1 class="font-weight-bold">Tree View</h1>
                                    <small>Bootstrap Treeview</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/.Content Header (Page header)--> 
                    <div class="body-content">
                        <div class="card mb-4">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fs-17 font-weight-600 mb-0">Tree View</h6>
                                    </div>
                                    <div class="text-right">
                                        <div class="actions">
                                            <a href="#" class="action-item"><i class="ti-reload"></i></a>
                                            <div class="dropdown action-item" data-toggle="dropdown">
                                                <a href="#" class="action-item"><i class="ti-more-alt"></i></a>
                                                <div class="dropdown-menu">
                                                    <a href="#" class="dropdown-item">Refresh</a>
                                                    <a href="#" class="dropdown-item">Manage Widgets</a>
                                                    <a href="#" class="dropdown-item">Settings</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="card card-body shadow-none border mb-3">
                                            <p class="mb-0"><span class="font-weight-600">Initialization no parameters</span>
                                                <br /> <code>$('#tree1').treed();</code>
                                            </p>
                                        </div>
                                        <ul id="tree1">
                                            <li><a href="#">TECH</a>
                                                <ul>
                                                    <li>Company Maintenance</li>
                                                    <li>Employees
                                                        <ul>
                                                            <li>Reports
                                                                <ul>
                                                                    <li>Report1</li>
                                                                    <li>Report2</li>
                                                                    <li>Report3</li>
                                                                </ul>
                                                            </li>
                                                            <li>Employee Maint.</li>
                                                        </ul>
                                                    </li>
                                                    <li>Human Resources</li>
                                                </ul>
                                            </li>
                                            <li><a href="#">XRP</a>
                                                <ul>
                                                    <li>Company Maintenance</li>
                                                    <li>Employees
                                                        <ul>
                                                            <li>Reports
                                                                <ul>
                                                                    <li>Report1</li>
                                                                    <li>Report2</li>
                                                                    <li>Report3</li>
                                                                </ul>
                                                            </li>
                                                            <li>Employee Maint.</li>
                                                        </ul>
                                                    </li>
                                                    <li>Human Resources</li>
                                                </ul>
                                            </li>
                                            <li><a href="#">Tree View One</a>
                                                <ul>
                                                    <li>Company Maintenance</li>
                                                    <li>Employees
                                                        <ul>
                                                            <li>Reports
                                                                <ul>
                                                                    <li>Report1</li>
                                                                    <li>Report2</li>
                                                                    <li>Report3</li>
                                                                </ul>
                                                            </li>
                                                            <li>Employee Maint.</li>
                                                        </ul>
                                                    </li>
                                                    <li>Human Resources</li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card card-body shadow-none border mb-3">
                                            <p class="mb-0"><span class="font-weight-600">Initialization optional parameters</span>
                                                <br /> <code>$('#tree2').treed({openedClass : 'fa-folder-open', closedClass : 'fa-folder'});</code>
                                            </p>
                                        </div>
                                        <ul id="tree2">
                                            <li><a href="#">TECH</a>
                                                <ul>
                                                    <li>Company Maintenance</li>
                                                    <li>Employees
                                                        <ul>
                                                            <li>Reports
                                                                <ul>
                                                                    <li>Report1</li>
                                                                    <li>Report2</li>
                                                                    <li>Report3</li>
                                                                </ul>
                                                            </li>
                                                            <li>Employee Maint.</li>
                                                        </ul>
                                                    </li>
                                                    <li>Human Resources</li>
                                                </ul>
                                            </li>
                                            <li><a href="#">XRP</a>
                                                <ul>
                                                    <li>Company Maintenance</li>
                                                    <li>Employees
                                                        <ul>
                                                            <li>Reports
                                                                <ul>
                                                                    <li>Report1</li>
                                                                    <li>Report2</li>
                                                                    <li>Report3</li>
                                                                </ul>
                                                            </li>
                                                            <li>Employee Maint.</li>
                                                        </ul>
                                                    </li>
                                                    <li>Human Resources</li>
                                                </ul>
                                            </li>
                                            <li><a href="#">Tree View Four</a>
                                                <ul>
                                                    <li>Company Maintenance</li>
                                                    <li>Employees
                                                        <ul>
                                                            <li>Reports
                                                                <ul>
                                                                    <li>Report1</li>
                                                                    <li>Report2</li>
                                                                    <li>Report3</li>
                                                                </ul>
                                                            </li>
                                                            <li>Employee Maint.</li>
                                                        </ul>
                                                    </li>
                                                    <li>Human Resources</li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card card-body shadow-none border mb-3">
                                            <p class="mb-0"><span class="font-weight-600">Initialization optional parameters</span>
                                                <br /> <code>$('#tree3').treed({openedClass:'fas fa-minus', closedClass:'fas fa-plus'});</code>
                                            </p>
                                        </div>
                                        <ul id="tree3">
                                            <li><a href="#">TECH</a>
                                                <ul>
                                                    <li>Company Maintenance</li>
                                                    <li>Employees
                                                        <ul>
                                                            <li>Reports
                                                                <ul>
                                                                    <li>Report1</li>
                                                                    <li>Report2</li>
                                                                    <li>Report3</li>
                                                                </ul>
                                                            </li>
                                                            <li>Employee Maint.</li>
                                                        </ul>
                                                    </li>
                                                    <li>Human Resources</li>
                                                </ul>
                                            </li>
                                            <li><a href="#">XRP</a>
                                                <ul>
                                                    <li>Company Maintenance</li>
                                                    <li>Employees
                                                        <ul>
                                                            <li>Reports
                                                                <ul>
                                                                    <li>Report1</li>
                                                                    <li>Report2</li>
                                                                    <li>Report3</li>
                                                                </ul>
                                                            </li>
                                                            <li>Employee Maint.</li>
                                                        </ul>
                                                    </li>
                                                    <li>Human Resources</li>
                                                </ul>
                                            </li>
                                            <li><a href="#">Tree View Five</a>
                                                <ul>
                                                    <li>Company Maintenance</li>
                                                    <li>Employees
                                                        <ul>
                                                            <li>Reports
                                                                <ul>
                                                                    <li>Report1</li>
                                                                    <li>Report2</li>
                                                                    <li>Report3</li>
                                                                </ul>
                                                            </li>
                                                            <li>Employee Maint.</li>
                                                        </ul>
                                                    </li>
                                                    <li>Human Resources</li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fs-17 font-weight-600 mb-0">jsTree</h6>
                                    </div>
                                    <div class="text-right">
                                        <div class="actions">
                                            <a href="#" class="action-item"><i class="ti-reload"></i></a>
                                            <div class="dropdown action-item" data-toggle="dropdown">
                                                <a href="#" class="action-item"><i class="ti-more-alt"></i></a>
                                                <div class="dropdown-menu">
                                                    <a href="#" class="dropdown-item">Refresh</a>
                                                    <a href="#" class="dropdown-item">Manage Widgets</a>
                                                    <a href="#" class="dropdown-item">Settings</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!--HTML demo-->
                                    <div class="col-lg-4">
                                        <div class="card card-body shadow-none border mb-4">
                                            <h5 class="font-weight-600 border-bottom pb-2 mb-3">HTML demo</h5>
                                            <div id="html" class="demo">
                                                <ul>
                                                    <li data-jstree='{ "opened" : true }'>Root node
                                                        <ul>
                                                            <li data-jstree='{ "selected" : true }'>Child node 1</li>
                                                            <li>Child node 2</li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Inline data demo-->
                                    <div class="col-lg-4">
                                        <div class="card card-body shadow-none border mb-4">
                                            <h5 class="font-weight-600 border-bottom pb-2 mb-3">Inline data demo</h5>
                                            <div id="data" class="demo"></div>
                                        </div>
                                    </div>
                                    <!--Data format demo-->
                                    <div class="col-lg-4">
                                        <div class="card card-body shadow-none border mb-4">
                                            <h5 class="font-weight-600 border-bottom pb-2 mb-3">Data format demo</h5>
                                            <div id="frmt" class="demo"></div>
                                        </div>
                                    </div>
                                    <!--AJAX demo-->
                                    <div class="col-lg-4">
                                        <div class="card card-body shadow-none border mb-4">
                                            <h5 class="font-weight-600 border-bottom pb-2 mb-3">AJAX demo</h5>
                                            <div id="ajax" class="demo"></div>
                                        </div>
                                    </div>
                                    <!--Lazy loading demo-->
                                    <div class="col-lg-4">
                                        <div class="card card-body shadow-none border mb-4">
                                            <h5 class="font-weight-600 border-bottom pb-2 mb-3">Lazy loading demo</h5>
                                            <div id="lazy" class="demo"></div>
                                        </div>
                                    </div>
                                    <!--Callback function data demo-->
                                    <div class="col-lg-4">
                                        <div class="card card-body shadow-none border mb-4">
                                            <h5 class="font-weight-600 border-bottom pb-2 mb-3">Callback function data demo</h5>
                                            <div id="clbk" class="demo"></div>
                                        </div>
                                    </div>
                                    <!--Interaction and events demo-->
                                    <div class="col-lg-4">
                                        <div class="card card-body shadow-none border mb-4">
                                            <h5 class="font-weight-600 border-bottom pb-2 mb-3">Interaction and events demo</h5>
                                            <button id="evts_button" class="btn btn-success mb-2">select node with id 1</button>
                                            <em class="mb-3">either click the button or a node in the tree</em>
                                            <div id="evts" class="demo"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div><!--/.body content-->
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
    </body>
</html>