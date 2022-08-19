<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0">
            <li class="breadcrumb-item"><a href="#">{{ get_phrases(['dashboard']) }}</a></li>
            <li class="breadcrumb-item"><a href="#">{{ get_phrases(['application', 'settings']) }}</a></li>
            <li class="breadcrumb-item active">{{ $title }}</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success"><i class="typcn typcn-puzzle-outline"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">{{ $title }}</h1>
                <small>{{ $title }}</small>
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
                    <h6 class="fs-17 font-weight-600 mb-0">{{ $title }}</h6>
                </div>
                <div class="text-right">
                    <div class="actions">
                        <a href="#" class="action-item reload"><i class="ti-reload"></i></a>
                        <div class="dropdown action-item" data-toggle="dropdown">
                            <a href="#" class="action-item"><i class="ti-more-alt"></i></a>
                            <div class="dropdown-menu">
                                <a href="#" class="dropdown-item reload">{{ get_phrases(['refresh']) }}</a>
                                <a href="#" class="dropdown-item">{{ get_phrases(['manage', 'widgets']) }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header py-2">
                            <!-- l -->
                        </div>

                        <div class="card-body">
                            <div class="box-divider m-a-0"></div>
                            <ul class="list-group list-group-gap m-a-0" id="notificationView"
                                style="overflow: auto; height: 600px; padding: 14px;">
                                <li class="list-group-item dark text-white box-shadow-z0 b">
                                    <span class="clear block"> Loading... </span>
                                </li>
                            </ul>
                            <div id="visibleElementEnd" class="text-center"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/.body content-->

@push('scripts')
    <script>
        $(function() {

            var page = 1;
            var lastPage = 1;
            var currentPage = 1;
            var loadingStatus = false

            const visibleElementEnd = $('#visibleElementEnd');
            const notificationBody = $('#notificationView');

            function getNotification() {
                loadingStatus = true;
                $.ajax(`/get-notifications?page=${page}`, {
                    type: 'GET',
                    success(response) {
                        if (page === 1) {
                            notificationBody.empty();
                        }

                        lastPage = response.data.last_page;
                        currentPage = response.data.current_page;

                        visibleElementEnd.text('');
                        notificationBody.append(response.view);

                        page += 1;
                        loadingStatus = false;

                        if (currentPage === lastPage && response.data.total !== 0) {
                            notificationBody.append(`
                                <li class="list-group-item dark-white text-color box-shadow-z0 b">
                                    <span class="clear block text-center"> No more notification ! </span>
                                </li>
                            `)
                        }
                    }
                })
            }
            getNotification();

            notificationBody.scroll(function() {
                if (isVisible(visibleElementEnd) && lastPage !== currentPage && !loadingStatus) {
                    visibleElementEnd.text('Loading...');
                    getNotification();
                }
            });
        });

        $('document').ready(function() {
            "use strict";

            //reload
            $('.reload').click(function(e) {
                e.preventDefault();
                location.reload();
            });

        });
    </script>
@endpush
