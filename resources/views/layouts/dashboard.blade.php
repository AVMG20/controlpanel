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

    <!-- Custom Headerscripts -->
    <script src="{{ asset('js/custom.js') }}"></script>

    <!-- Datatables -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/v/bs5/dt-1.11.5/datatables.min.css"/>
    <script type="text/javascript" src="//cdn.datatables.net/v/bs5/dt-1.11.5/datatables.min.js"></script>

    <!-- include ckeditor -->
    <script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>

    <!-- Sweet alert 2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @php
        $settings = new \App\Settings\CustomizationSettings;
        $primarycolor = $settings->primary_color;
        $secondarycolor = $settings->secondary_color;
        $tertiarycolor = $settings->tertiary_color;
        $textcolor = $settings->text_color;

        $primarycolorrgb = $settings->convert_hex_to_rgb($settings->primary_color);
        $secondarycolorrgb = $settings->convert_hex_to_rgb($settings->secondary_color);
        $tertiarycolorrgb = $settings->convert_hex_to_rgb($settings->tertiary_color);
        $textcolorrgb = $settings->convert_hex_to_rgb($settings->text_color);
    @endphp
    <style>
        :root {
            --bs-white: {{$secondarycolor}};
            --bs-gray-dark: {{$tertiarycolor}};
            --bs-gray-100: {{$primarycolor}};
            --bs-gray-700: {{$textcolor}};
            --bs-gray-800: {{$tertiarycolor}};
            --bs-gray-900: {{$textcolor}};
            --bs-primary: {{$tertiarycolor}};
            --bs-white: {{$secondarycolor}};
            --bs-gray-100: {{$primarycolor}};
            --bs-gray-700: {{$textcolor}};
            --bs-gray-800: {{$tertiarycolor}};
            --bs-gray-900: {{$textcolor}};
            --bs-primary-rgb: {{$primarycolorrgb}};
            --bs-white-rgb: {{$secondarycolorrgb}};
            --bs-red-100-rgb: {{$primarycolorrgb}};
            --bs-gray-100-rgb: {{$primarycolorrgb}};
            --bs-gray-800-rgb: {{$tertiarycolorrgb}};
            --bs-white-rgb: {{$secondarycolorrgb}};
            --bs-body-bg-rgb: {{$primarycolorrgb}};
            --bs-body-color: {{$textcolor}};
            --bs-body-bg: {{$primarycolor}};
        }

        .w-100px {
            width: 100px !important;
        }
    </style>


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
