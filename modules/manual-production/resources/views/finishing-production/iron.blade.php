<!DOCTYPE html>
<html lang="en">

<head>
    @includeIf('manual-production::vue_common_header')
    @includeIf('manual-production::form-style')
    @include('manual-production::loader_style')
</head>

<body>
<div class="app" id="app">
    <!-- content -->
    <div id="content" class="app-content box-shadow-z3" role="main">
        <div class="app-header white box-shadow">
            <div>
                @include('manual-production::partials/vue-header')
            </div>
        </div>
        <div class="app-footer">
            <div>
                @include('skeleton::partials/footer')
            </div>
        </div>
        <div ui-view class="app-body" id="view">
            <div class="padding">
                <div id="finishing-iron">

                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ mix('/js/manual-production/finishing-iron.js') }}"></script>
</body>
</html>
