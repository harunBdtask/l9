<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Forms</a></li>
            <li class="breadcrumb-item active">{{$title}}</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success"><i class="typcn typcn-puzzle-outline"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">{{$title}}</h1>
                <small>{{$title}}</small>
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
                    <h6 class="fs-17 font-weight-600 mb-0">{{$title}}</h6>
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
                <div class="col-sm-6">
                    <div class="card card-body shadow-none mb-4">
                        <div id="html" class="demo">
                            <ul>
                                <li data-jstree='{ "opened" : true }'>Root node
                                    <ul>
                                        <li data-jstree='{ "selected" : true }'>Child node 1</li>
                                        <li>Child node 2</li>
                                    </ul>
                                </li>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/.body content-->