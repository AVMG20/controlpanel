@extends('layouts.dashboard')

@section('content')
    <div class="main py-4">

        <!-- actions -->
        @if (isset($user))
            <div class="d-flex justify-content-end my-3">
                <a href="{{route('admin.users.create')}}" class="btn btn-primary">
                    <i class="fa fas fa-sign-in-alt pe-2"></i>{{__('Login as')}}
                </a>
            </div>
        @endif

        <form method="post" action="{{isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store')}}">
            @csrf
            @isset($user)
                @method('PATCH')
            @endisset

        <!-- card -->
            <div class="card card-body border-0 shadow table-wrapper table-responsive">
                <h2 class="mb-4 h5">{{isset($user) ? __('Edit user') : __('Create user')}}</h2>


                <div class="row">
                    <div class="col-lg-6">

                        <x-input.text label="{{(__('Name'))}}"
                                      name="name"
                                      value="{{ isset($user) ? $user->name : null}}"/>

                        <x-input.text label="{{(__('Email'))}}"
                                      name="email"
                                      value="{{ isset($user) ? $user->email : null}}"/>

                        <x-input.number label="{{ $settings->credits_display_name }}"
                                        name="credits"
                                        min="0"
                                        max="9999999999999"
                                        step=".000001"
                                        value="{{ isset($user) ? $user->credits : $settings->initial_user_credits}}"/>

                        <x-input.number label="{{(__('Server limit'))}}"
                                        name="server_limit"
                                        min="0"
                                        tooltip="{{__('The amount of servers an user can have')}}"
                                        max="2147483647"
                                        step="1"
                                        value="{{ isset($user) ? $user->server_limit : $settings->initial_server_limit}}"/>

                    </div>
                    <div class="col-lg-6">

                        <x-input.select
                            label="{{(__('Roles'))}}"
                            name="roles"
                            multiple>
                            @foreach($roles as $role)
                                <option style="color: {{$role->color}}"
                                        @if(isset($user) && $user->roles->contains($role)) selected
                                        @endif value="{{$role->id}}">{{$role->name}}</option>
                            @endforeach
                        </x-input.select>

                    </div>
                </div>
                <div class="form-group d-flex justify-content-end mt-3">
                    <button name="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-body border-0 shadow table-wrapper mt-3">
                        <h2 class="mb-4 h5">{{isset($user) ? __('Edit user password') : __('Create user password')}}</h2>

                        <x-input.text label="{{(__('Password'))}}"
                                      type="password"
                                      name="password"/>

                        <x-input.text label="{{(__('Password confirmation'))}}"
                                      type="password"
                                      name="password_confirmation"/>

                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection


