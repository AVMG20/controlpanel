<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Main js-->
    <script src="{{ asset('js/app.js') }}"></script>


    @php
        $settings = new \App\Settings\CustomizationSettings;

        $custom_js_filename = $settings->custom_js_filename;

        $primarycolor = $settings->primary_color;
        $secondarycolor = $settings->secondary_color;
        $tertiarycolor = $settings->tertiary_color;
        $textcolor = $settings->text_color;

        $primarycolorrgb = $settings->convert_hex_to_rgb($settings->primary_color);
        $secondarycolorrgb = $settings->convert_hex_to_rgb($settings->secondary_color);
        $tertiarycolorrgb = $settings->convert_hex_to_rgb($settings->tertiary_color);
        $textcolorrgb = $settings->convert_hex_to_rgb($settings->text_color);
    @endphp

        <!-- Custom Headerscripts -->
    <script src="{{ asset("js/".$custom_js_filename) }}"></script>

    <style>
        :root {
            @isset($secondarycolor) --bs-white: {{$secondarycolor}};          @endisset
            @isset($secondarycolor) --bs-white: {{$secondarycolor}};          @endisset
            @isset($tertiarycolor) --bs-gray-dark: {{$tertiarycolor}};        @endisset
            @isset($primarycolor)  --bs-gray-100: {{$primarycolor}};          @endisset
            @isset($textcolor)--bs-gray-700: {{$textcolor}};                  @endisset
            @isset($tertiarycolor) --bs-gray-800: {{$tertiarycolor}};         @endisset
            @isset($textcolor) --bs-gray-900: {{$textcolor}};                 @endisset
            @isset($tertiarycolor) --bs-primary: {{$tertiarycolor}};          @endisset
            @isset($secondarycolor) --bs-white: {{$secondarycolor}};          @endisset
            @isset($primarycolor) --bs-gray-100: {{$primarycolor}};           @endisset
            @isset($textcolor)--bs-gray-700: {{$textcolor}};                  @endisset
            @isset($tertiarycolor) --bs-gray-800: {{$tertiarycolor}};         @endisset
            @isset($textcolor)--bs-gray-900: {{$textcolor}};                  @endisset
            @isset($secondarycolor) --bs-primary-rgb: {{$primarycolorrgb}};   @endisset
            @isset($secondarycolor) --bs-white-rgb: {{$secondarycolorrgb}};   @endisset
            @isset($primarycolor) --bs-red-100-rgb: {{$primarycolorrgb}};     @endisset
            @isset($primarycolor) --bs-gray-100-rgb: {{$primarycolorrgb}};    @endisset
            @isset($tertiarycolor) --bs-gray-800-rgb: {{$tertiarycolorrgb}};  @endisset
            @isset($secondarycolor) --bs-white-rgb: {{$secondarycolorrgb}};   @endisset
            @isset($primarycolor) --bs-body-bg-rgb: {{$primarycolorrgb}};     @endisset
            @isset($textcolor)  --bs-body-color: {{$textcolor}};              @endisset
            @isset($primarycolor)  --bs-body-bg: {{$primarycolor}};           @endisset
        }
    </style>

    <!-- Sweet alert 2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @livewireStyles
</head>

<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-sm navbar-transparent navbar-dark navbar-theme-primary mb-4">
    <div class="container position-relative">
        <a class="navbar-brand me-lg-5" href="{{ route('home') }}">
            @if (file_exists(storage_path("app/public/images/".$settings->custom_icon_filename)) && !is_null($settings->custom_icon_filename))
                <img src="{{asset("storage/images/".$settings->custom_icon_filename)}}" height="50" width="50" alt="Logo">
            @endif
        </a>
        <div class="w-100" id="navbar-default-primary">
            <ul class="navbar-nav navbar-nav-hover align-items-lg-center">
                <li class="nav-item">
                    <a href="{{ route('dashboard.index') }}" class="nav-link">
                        <span>
                            <i style="font-size: 16px;" class="fas pe-1 fa-home"></i>
                            {{__('Home')}}
                        </span>
                    </a>
                </li>
            </ul>
        </div>
        <ul class="navbar-nav navbar-nav-hover align-items-lg-center">

            <!-- Check if user is NOT logged in -->
            @if(Auth::guest())
                <li class="nav-item">
                    <a href="{{route('login')}}" class="nav-link">{{__('Login')}}</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('register')}}" class="nav-link">{{__('Register')}}</a>
                </li>
            @endif


            <!-- Check if user IS logged in -->
            @if(!Auth::guest())
                <li class="nav-item dropdown ms-lg-3">
                    <a class="nav-link dropdown-toggle pt-1 px-0" href="#" role="button" data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <div class="media d-flex align-items-center">
                            <img class="avatar rounded-circle"
                                 src="{{ Auth::user()->getAvatar() }}"
                                 alt="{{ Auth::user()->name }}">
                            <div class="media-body ms-2 text-dark align-items-center d-none d-lg-block">
                                <span class="mb-0 font-small fw-bold">{{ auth()->user()->name }}</span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dashboard-dropdown dropdown-menu-end mt-2 py-1">
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.show') }}">
                            <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                                      clip-rule="evenodd"></path>
                            </svg>

                            {{ __('My Profile') }}
                        </a>
                        <div role="separator" class="dropdown-divider my-1"></div>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                            <form method="POST" id="logout-form" action="{{ route('logout') }}">
                                @csrf
                            </form>
                            <svg class="dropdown-icon text-danger me-2" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            {{ __('Log Out') }}
                        </a>
                    </div>
                </li>
            @endif

        </ul>
    </div>
</nav>


<livewire:checkout/>

@livewireScripts
</body>
</html>
