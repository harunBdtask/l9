<!DOCTYPE html>
<html lang="en">

<head>
    <title>Yarn Allocation</title>
    @includeIf('inventory::vue_common_header')
    @includeIf('inventory::form-style')

    <style>
        .badge-danger {
            color: #fff;
            background-color: #dc3545;
        }
        .badge {
            display: inline-block;
            padding: .25em 0.4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }
    </style>
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
                <div id="yarn-allocation">
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ mix('/js/yarn-allocation.js') }}"></script>
</body>
</html>
