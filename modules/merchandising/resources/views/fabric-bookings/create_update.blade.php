<!DOCTYPE html>
<html lang="en">

<head>
    @includeIf('merchandising::vue_common_header')
    @includeIf('merchandising::form-style')
</head>

<body>
<div class="app">
    <!-- content -->
    <div id="content" class="app-content box-shadow-z3" role="main">
        <div class="app-header white box-shadow">
            <div>
                @include('merchandising::partials/header')
            </div>
        </div>
        <div class="app-footer">
            <div>
                @include('skeleton::partials/footer')
            </div>
        </div>
        <div ui-view class="app-body" id="view">
            <div class="padding">
                <div id="app">

                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ mix('/js/fabric_bookings/fabric_booking_vue.js') }}"></script>
</body>
</html>