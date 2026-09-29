<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('admin.head')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary @yield('body-style', '')">

<div class="app-wrapper">

    @include('admin.header')

    @include('admin.left_sidebar')

    @include('admin.content')

    @include('admin.footer')

</div>

@include('admin.partials.toasts')

@include('admin.foot')

</body>
</html>