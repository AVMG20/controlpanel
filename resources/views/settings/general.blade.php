@extends('settings.layout')

@section('settings_content')
    <h2 class="mb-4 h5">{{__('General')}}</h2>

    <form method="post" action="{{route('settings.general.update')}}">
        @csrf
        @method('PATCH')

        <div class="row">
            <div class="col-lg-6">

                <x-input.text label="{{(__('Main site URL'))}}"
                              name="main_site"
                              tooltip="{{(__('Your main website, Leave empty if you dont have one yet'))}}"
                              value="{{$settings->main_site}}"/>

                <x-input.text label="{{(__('Credits display name'))}}"
                              name="credits_display_name"
                              tooltip="{{(__('Change the name Credits to anything you like'))}}"
                              value="{{$settings->credits_display_name}}"/>

            </div>

            <div class="col-lg-6">
                <x-input.checkbox label="{{(__('Register IP Check'))}}"
                                  name="register_ip_check"
                                  tooltip="{{(__('Prevent users from making multiple accounts using the same IP'))}}"
                                  value="{{$settings->register_ip_check}}"/>

                <x-input.number
                    label="{{(__('Initial amount of :credits', ['credits' => $settings->credits_display_name ?? 'Credits']))}}"
                    name="initial_user_credits"
                    min="0"
                    max="9999999999999"
                    step=".000001"
                    tooltip="{{(__('The initial amount of :credits given to new users', ['credits' => $settings->credits_display_name ?? 'Credits']))}}"
                    value="{{$settings->initial_user_credits}}"/>

                <x-input.number label="{{(__('Initial server limit'))}}"
                                name="initial_server_limit"
                                min="0"
                                max="999999999"
                                tooltip="{{(__('The initial amount of server limit given to new users'))}}"
                                value="{{$settings->initial_server_limit}}"/>
            </div>
        </div>

        @can('settings.general.write')
            <div class="form-group d-flex justify-content-end mt-3">
                <button name="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
            </div>
        @endcan
    </form>
@endsection
