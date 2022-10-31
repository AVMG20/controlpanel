<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>


    <!-- Datatables -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/v/bs5/dt-1.11.5/datatables.min.css"/>
    <script type="text/javascript" src="//cdn.datatables.net/v/bs5/dt-1.11.5/datatables.min.js"></script>

    <!-- include ckeditor -->
    <script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>

    <!-- Sweet alert 2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!--Include customization options -->
    @include('layouts.parts.customization')


    <title>{{ config('app.name', 'Laravel') }}</title>
</head>

<body>
@include('layouts.parts.nav')
@include('layouts.parts.sidenav')
<main class="content">
    {{-- TopBar --}}
    @include('layouts.parts.topbar')
    @yield('content')
    {{-- Footer --}}
    @include('layouts.parts.footer')
</main>

@include('layouts.parts.scripts')
@yield('scripts')
</body>
