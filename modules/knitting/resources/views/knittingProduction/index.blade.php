<!DOCTYPE html>
<html lang="en">

<head>
    <title>Knitting Production</title>
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
        .box {
            box-shadow: 0 2px 6px rgb(0 0 0 / 34%), 0 -1px 0px rgb(0 0 0 / 2%);
        }
        .box, .box-color {
            border-radius: 8px;
        }
        .input-bg {
            background: #5bc0de26;
        }
        .reportTable td {
            border: 1px solid #909ac8 !important;
            padding: 0px 3px;
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
                <div id="knitting-production"></div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('/js/knitting-production.js') }}"></script>
</body>
</html>
