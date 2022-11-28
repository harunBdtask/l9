<!DOCTYPE html>
<html lang="en">

<head>
    @includeIf('commercial::vue_common_header')
    @includeIf('commercial::form-style')
</head>

<body>
<div class="app" id="app">
    <!-- content -->
    <div id="content" class="app-content box-shadow-z3" role="main">
        <div class="app-header white box-shadow">
            <div>
                @include('commercial::partials/vue-header')
            </div>
        </div>
        <div class="app-footer">
            <div>
                @include('skeleton::partials/footer')
            </div>
        </div>
        <div ui-view class="app-body" id="view">
            <div class="padding">
                <div id="export-proceds-realization">

                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('/js/commercial/export-proceds-realization.js') }}"></script>
</body>
</html>
