<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
</head>
<body>
<header>
    <h2 style="margin: 0" class="center">{{ factoryName() }}</h2>
    <p style="margin: 0" class="center">{{ factoryAddress() }}</p>
    <p class="center">@yield('title')</p>
</header>
{{--<footer>footer on each page</footer>--}}

<div style="margin-top: 20px">
    @yield('content')
</div>
</body>
<style>
    .center {
        text-align: center;
    }

    table, td, th {
        border: 1px solid black;
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    table {
        margin-top: 20px;
    }
    @page { margin: 100px 25px; }
    header { position: fixed; top: -60px; left: 0; right: 0; }
    footer { position: fixed; bottom: -60px; left: 0; right: 0; }
</style>
</html>
