<!DOCTYPE html>
<html lang="en">

<head>
    @includeIf('inventory::vue_common_header')
    @includeIf('inventory::form-style')
</head>

<body style="overflow-x: hidden">
<div class="app" id="app">
    <!-- content -->
    <div id="content" class="app-content box-shadow-z3" role="main">
        <div class="app-header white box-shadow">
            <div>
                @include('commercial::partials/vue-header')
                <div class="box-tool">
                    <a href="/inventory/yarn-receive-return" class="btn btn-sm btn-danger">BACK</a>
                </div>
            </div>
        </div>
        <div class="app-footer">
            <div>
                @include('skeleton::partials/footer')
            </div>
        </div>
        <div ui-view class="app-body" id="view">
            <div class="padding">
                <div id="yarn-receive-return">
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ mix('js/inventory/yarn-receive-return.js') }}"></script>
</body>
</html>
