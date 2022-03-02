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
                    <a href="{{ route('main-site') }}" class="nav-link"><i style="font-size: 16px;" class="fas pe-1 fa-home"></i>{{__('Home')}}</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('dashboard')}}" class="nav-link"><i style="font-size: 16px;" class="fas pe-1 fa-server"></i>{{__('Client Area')}}</a>
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
