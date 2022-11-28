<!DOCTYPE html>
<html lang="en">

<head>
    <title>Knitting QC</title>
    @includeIf('inventory::vue_common_header')
    @includeIf('inventory::form-style')
    <link
        rel="stylesheet"
        href="https://unpkg.com/mathlive/dist/mathlive-static.css"
    />
</head>

<body style="overflow-x: hidden">
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
                <div id="knitting-qc">
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ mix('js/knitting/knitting-qc.js') }}"></script>
</body>
</html>
