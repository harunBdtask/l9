<!DOCTYPE html>
<html lang="en">

<head>
    <title>Program</title>
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
                <div id="program"></div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('/js/program.js') }}"></script>
</body>
</html>
