<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>World Clock Map- Admin</title>
    @include('admin.layouts.head')
</head>
<body class="toggle-sidebar">
    @include('admin.layouts.header')

    <div id="app">
        @yield('content')
    </div>
    @include('admin.layouts.footer')
</body>
</html>