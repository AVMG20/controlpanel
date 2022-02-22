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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.4/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.4/datatables.min.js"></script>

    <title>{{ config('app.name', 'Laravel') }} </title>
</head>

<body>
    @include('layouts.components.nav')
    @include('layouts.components.sidenav')
    <main class="content">
        {{-- TopBar --}}
        @include('layouts.components.topbar')
        @yield('content')
        {{-- Footer --}}
        @include('layouts.components.footer')
    </main>

    @include('layouts.components.scripts')
    @yield('scripts')
</body>
