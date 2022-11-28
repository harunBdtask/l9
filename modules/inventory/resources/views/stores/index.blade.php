<!DOCTYPE html>
<html lang="en">

<head>
    <title>Store Managements</title>
    @includeIf('merchandising::vue_common_header')
    @includeIf('merchandising::form-style')
</head>

<body>
<div class="app" id="app">
    <div id="loader"></div>
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
                <div id="store-managements">

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var loader;

    function loadNow(opacity) {
        if (opacity <= 0) {
            displayContent();
        } else {
            loader.style.opacity = opacity;
            window.setTimeout(function () {
                loadNow(opacity - 0.05);
            }, 5);
        }
    }

    function displayContent() {
        loader.style.display = 'none';
        document.getElementById('content').style.display = 'block';
    }

    document.addEventListener("DOMContentLoaded", function () {
        loader = document.getElementById('loader');
        loadNow(5);
    });
</script>
<script src="{{ mix('js/inventory/stores-management.js') }}"></script>
</body>
</html>




