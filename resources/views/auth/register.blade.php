@extends('layouts.auth')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 d-flex align-items-center justify-content-center">
                <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
                    <div class="text-center text-md-center mb-4 mt-md-0">
                        <h1 class="mt-n3 mb-0 h3">{{ __('Create Account') }}</h1>
                    </div>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        @error('pterodactyl_error')
                        <div class="invalid-feedback"> {{ $message }} </div>
                        @enderror
                        @error('g-recaptcha-response')
                        <div class="invalid-feedback"> {{ $message }} </div>
                        @enderror

                        <!-- Form -->
                        <div class="form-group mt-4 mb-4">
                            <label for="username">{{ __('Your Username') }}</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fas fa-user-alt fa-fw"></i>
                                </span>
                                <input name="username" id="username" type="name" class="form-control"
                                       placeholder="{{ __('Username') }}" value="{{ old('username') }}" autofocus required>
                            </div>

                            @error('username')
                            <div class="invalid-feedback"> {{ $message }} </div> @enderror
                        </div>
                        <!-- End of Form -->
                        
                        <div class="row">
                            <!-- Form -->
                            <div class="col form-group">
                                <label for="first_name">{{ __('Your First Name') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">
                                        <i class="fas fa-user-alt fa-fw"></i>
                                    </span>
                                    <input name="first_name" id="first_name" type="name" class="form-control"
                                        placeholder="{{ __('First Name') }}" value="{{ old('first_name') }}" autofocus required>
                                </div>

                                @error('first_name')
                                <div class="invalid-feedback"> {{ $message }} </div> @enderror
                            </div>
                            <!-- End of Form -->
                            <!-- Form -->
                            <div class="col form-group">
                                <label for="last_name">{{ __('Your Last Name') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">
                                        <i class="fas fa-user-alt fa-fw"></i>
                                    </span>
                                    <input name="last_name" id="last_name" type="name" class="form-control"
                                        placeholder="{{ __('Last Name') }}" value="{{ old('last_name') }}" autofocus>
                                </div>

                                @error('last_name')
                                <div class="invalid-feedback"> {{ $message }} </div> @enderror
                            </div>
                            <!-- End of Form -->
                        </div>

                        <!-- Form -->
                        <div class="form-group mt-4 mb-4">
                            <label for="email">{{ __('Your Email') }}</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">
                                    <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                        </path>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z">
                                        </path>
                                    </svg>
                                </span>
                                <input name="email" id="email" type="email" class="form-control"
                                       placeholder="{{ __('Email') }}" value="{{ old('email') }}" autofocus required>
                            </div>
                            @error('email')
                            <div class="invalid-feedback"> {{ $message }} </div> @enderror
                        </div>
                        <!-- End of Form -->
                        <!-- Form -->
                        <div class="form-group mb-4">
                            <label for="password">{{ __('Your Password') }}</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon2">
                                    <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                              d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                              clip-rule="evenodd">
                                        </path>
                                    </svg>
                                </span>
                                <input name="password" type="password" placeholder="{{ __('Password') }}"
                                       class="form-control" id="password"
                                       required autocomplete="new-password">
                            </div>
                            @error('password')
                            <div class="invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                        <!-- End of Form -->
                        <!-- Form -->
                        <div class="form-group mb-4">
                            <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon3">
                                    <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                              d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                              clip-rule="evenodd">
                                        </path>
                                    </svg>
                                </span>
                                <input name="password_confirmation" type="password"
                                       placeholder="{{ __('Confirm Password') }}"
                                       class="form-control" id="password_confirmation" required>
                            </div>
                        </div>

                        <!-- End of Form -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-gray-800">{{ __('Register') }}</button>
                        </div>

                        <div id="public_captcha"></div>
                        {!!  GoogleReCaptchaV3::renderField('public_captcha','public') !!}

                    </form>

                    <div class="d-flex justify-content-center align-items-center mt-4">
                        <span class="fw-normal">
                            {{ __('Already have an account?') }}
                            <a href="{{ route('login') }}" class="fw-bold">{{ __('Login here') }}</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!!  GoogleReCaptchaV3::renderOne('public_captcha','public') !!}
@endsection
