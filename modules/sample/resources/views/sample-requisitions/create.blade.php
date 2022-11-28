<!DOCTYPE html>
<html lang="en">

<head>
    @includeIf('sample::vue_common_header')
    @includeIf('sample::form-style')
</head>

<body>
<div class="app" id="app">
    <!-- content -->
    <div id="content" class="app-content box-shadow-z3" role="main">
        <div class="app-header white box-shadow">
            <div>
                @include('sample::partials/header')
            </div>
        </div>
        <div class="app-footer">
            <div>
                @include('skeleton::partials/footer')
            </div>
        </div>
        <div ui-view class="app-body" id="view">
            <div class="padding">
                <div id="sample-requisition-form">
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('/js/sample-requisition.js') }}"></script>
</body>
</html>
