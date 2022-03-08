<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Main js-->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Sweet alert 2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<!-- JS specific to this page, Alpine wants to load inside the body -->
<script src="{{ asset('js/checkout.js') }}"></script>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent navbar-dark navbar-theme-primary mb-4">
    <div class="container position-relative">
        <a class="navbar-brand me-lg-5" href="{{ route('main-site') }}">
            <img class="navbar-brand-dark" src="{{ asset('images/brand/light.svg') }}" alt="Volt logo"/>
            <img class="navbar-brand-light" src="{{ asset('images/brand/dark.svg') }}" alt="Volt logo"/>
        </a>
        <div class="navbar-collapse collapse w-100" id="navbar-default-primary">
            <div class="navbar-collapse-header">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="#">
                            <img src="../../assets/img/brand/dark.svg" alt="menuimage">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <i class="fas fa-times" data-toggle="collapse" role="button"
                           data-target="#navbar-default-primary" aria-controls="navbar-default-primary"
                           aria-expanded="false" aria-label="Toggle navigation"></i>
                    </div>
                </div>
            </div>
            <ul class="navbar-nav navbar-nav-hover align-items-lg-center">
                <li class="nav-item">
                    <a href="{{ route('main-site') }}" class="nav-link"><i style="font-size: 16px;"
                                                                           class="fas pe-1 fa-home"></i>{{__('Home')}}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('dashboard')}}" class="nav-link"><i style="font-size: 16px;"
                                                                         class="fas pe-1 fa-server"></i>{{__('Client Area')}}
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
        <div class="d-flex align-items-center">
            <button class="navbar-toggler ms-2" type="button" data-toggle="collapse"
                    data-target="#navbar-default-primary" aria-controls="navbar-default-primary"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>
</nav>
<!-- End navbar -->

<div x-data="checkoutData()" class="container">
    <form action="{{route('checkout.store')}}" method="POST">
        @csrf
        <div class="row">

            <!-- Form -->
            <div class="col-md-7">
                <div class="mb-3">
                    <h4 class="text-primary mb-3">{{__('Configure')}}</h4>
                    <span>{{__('Configure your desired options and continue to checkout.')}}</span>
                </div>

                <div class="card p-4">

                    <div class="form-group mb-3">
                        <label for="egg">{{__('Server software')}}</label>
                        <select name="egg" class="form-control" id="egg" @change="updateSelectedEgg; ">
                            <option selected disabled>{{__('Select software specification')}}</option>
                            <template x-for="nest in nests">
                                <optgroup :label="nest.name">
                                    <template x-for="egg in nest.eggs">
                                        <option :selected="egg.selected" x-text="egg.name"
                                                :value="egg.id"></option>
                                    </template>
                                </optgroup>
                            </template>
                        </select>
                    </div>


                    <div class="d-flex justify-content-center align-items-center border-bottom pb-2 my-3">
                        <span>{{__('Configurable Options')}}</span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="location">{{__('Server location')}}</label>
                        <select name="location" class="form-control" id="location" @change="updateSelectedLocation; ">
                            <option selected disabled>{{__('Select server location')}}</option>
                            <template x-for="location in locations">
                                <option :selected="location.selected" x-text="location.name"
                                        :value="location.id"></option>
                            </template>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="configuration">{{__('Server configuration')}}</label>
                        <select name="configuration" class="form-control" id="configuration"
                                @change="updateSelectedConfiguration; ">
                            <option selected disabled>{{__('Select server configuration')}}</option>
                            <template x-for="configuration in configurations">
                                <option :selected="configuration.selected" x-text="configuration.name"
                                        :value="configuration.id"></option>
                            </template>
                        </select>
                    </div>

                    <div class="d-flex justify-content-center align-items-center border-bottom pb-2 my-3">
                        <span>{{__('Additional Required Information')}}</span>
                    </div>

                    <div class="form-group">
                        <label for="name">{{__('Server name')}}</label>
                        <input x-model="name" @input="checkFormComplete()" name="name" id="name" type="text"
                               class="form-control">
                    </div>

                </div>
            </div>


            <!-- Order summary -->
            <div class="col-md-5 col-lg-4 order-md-last">

                <h4 class="d-flex justify-content-between mb-3 mt-sm-3">
                    <span class="text-primary">{{__('Order Summary')}}</span>
                </h4>

                <template x-if="selected_egg?.name">
                    <ul class="list-group mb-3">

                        <!-- Selected egg -->
                        <template x-if="selected_egg?.name">
                            <li class="list-group-item rounded-top d-flex flex-column ">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="my-0">{{__('Server software')}}</h6>
                                </div>
                                <div>
                                    <!-- Egg name is placed here -->
                                    <small x-html="selected_egg.name" class="text-muted"></small>
                                </div>
                            </li>
                        </template>

                        <!-- Selected location -->
                        <template x-if="selected_location?.name">
                            <li class="list-group-item d-flex flex-column ">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="my-0">{{__('Server location')}}</h6>
                                </div>
                                <div>
                                    <!-- Location name is placed here -->
                                    <p x-html="selected_location.name" class="text-muted my-0"></p>
                                </div>
                            </li>
                        </template>

                        <!-- Selected configuration -->
                        <template x-if="selected_configuration?.name">
                            <li class="list-group-item d-flex flex-column ">
                                <div class="d-flex justify-content-between align-items-center">

                                    <h6 class="my-0">{{__('Server configuration')}}</h6>

                                    <span class="text-muted fw-bold">
                                    <i class="text-b fas fa-coins pe-4"></i>
                                        <!-- Configuration price is placed here -->
                                    <span x-text="selected_configuration.price"></span>
                                </span>
                                </div>
                                <div>
                                    <!-- Configuration name is placed here -->
                                    <p x-html="selected_configuration.name" class="my-0 mb-1"></p>
                                    <!-- Configuration description is placed here -->
                                    <small x-html="selected_configuration.description" class="text-muted"></small>
                                </div>
                            </li>
                        </template>


                        <!-- Setup fee -->
                        <template x-if="selected_configuration?.setup_price > 0">
                            <li class="list-group-item rounded-bottom d-flex flex-column ">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="my-0">{{__('Setup fee')}}</h6>
                                    <span class="text-muted"><i class="fas fa-coins pe-4"></i>
                                        <!-- Setup price is located here -->
                                <span class="fw-bold" x-text="selected_configuration.setup_price"></span>
                            </span>
                                </div>
                                <div>
                                    <small
                                        class="text-muted">{{__('This configuration includes an initial setup fee.')}}</small>
                                </div>
                            </li>
                        </template>
                    </ul>
                </template>

                <ul class="list-group mb-3">
                    <!-- Total price -->
                    <template x-if="selected_configuration?.name">
                        <li class="list-group-item rounded d-flex flex-column ">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>{{__('Total')}} ({{$credits_display_name}})</span>

                                <span><i class="fas fa-coins pe-4"></i>
                            <span class="fw-bold"
                                  x-text="selected_configuration.price + selected_configuration.setup_price"></span>
                        </span>
                            </div>
                            <div>
                                <small class="text-muted">{{__('Pricing is per month')}}</small>
                            </div>
                        </li>
                    </template>

{{--                    <template x-if="selected_configuration?.name">--}}
{{--                        <li class="list-group-item rounded d-flex flex-column ">--}}
{{--                            <div class="d-flex justify-content-between align-items-center">--}}
{{--                                <span>{{__('Hourly rate')}} ({{$credits_display_name}})</span>--}}

{{--                                <span><i class="fas fa-coins pe-4"></i>--}}
{{--                            <span class="fw-bold"--}}
{{--                                  x-text="Number((selected_configuration.price + selected_configuration.setup_price ) / 30 / 24).toFixed(2)"></span>--}}
{{--                        </span>--}}
{{--                            </div>--}}
{{--                            <div>--}}
{{--                                <small class="text-muted">{{__(':credits are charged hourly', ['credits' => $credits_display_name])}}</small>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                    </template>--}}
                </ul>

                <template x-if="selected_egg?.name">
                    <div class="d-flex justify-content-center align-items-center">
                        <button :disabled="!form_complete" type="submit" class="btn btn-primary">
                            {{__('Continue')}}
                            <i class="fas ps-2 fa-arrow-right"></i>
                        </button>
                    </div>
                </template>
            </div>
            <!-- End order summary -->

        </div>
    </form>
</div>


@if (Session::has('error'))
  <script defer>
      Swal.fire({
          icon: 'error',
          title: 'Oops...',
          html: '{{ Session::get('error') }}',
      })
  </script>
@endif

</body>
</html>
