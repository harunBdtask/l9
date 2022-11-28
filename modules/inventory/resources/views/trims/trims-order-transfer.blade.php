<!DOCTYPE html>
<html lang="en">

<head>
    @includeIf('inventory::vue_common_header')
    @includeIf('inventory::form-style')
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
                <div id="trims-order-transfer">
                    <h1>Loading...</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('/js/inventory/trims-order-transfer.js') }}"></script>
</body>
</html>

