@extends('settings.layout')

@section('settings_content')
    <h2 class="mb-4 h5">{{__('Customization')}}</h2>

    <form method="post" action="{{route('settings.customization.update')}}">
        @csrf
        @method('PATCH')

        <div class="row">
            <div class="col-lg-6">


                <x-input.text label="{{(__('Primary Color'))}}"
                              name="primary_color"
                              tooltip="{{(__('This is the Background Color of your Dashboard. (Default: #F2F4F6)'))}}"
                              value="{{$settings->primary_color}}"/>

                <x-input.text label="{{(__('Secondary Color'))}}"
                              name="secondary_color"
                              tooltip="{{(__('This is the Foreground Color of your Dashboard. (Default: #FFFFFF)'))}}"
                              value="{{$settings->secondary_color}}"/>

                <x-input.text label="{{(__('Tertiary Color'))}}"
                              name="tertiary_color"
                              tooltip="{{(__('This is the color of the Buttons and other Elements (Default: #1F2937)'))}}"
                              value="{{$settings->tertiary_color}}"/>

                <x-input.text label="{{(__('Text Color'))}}"
                              name="text_color"
                              tooltip="{{(__('This is the color of most of the Text (Default: #111827)'))}}"
                              value="{{$settings->text_color}}"/>

            </div>
            <div class="col-lg-6">
                <!-- This file is loaded in the Header of /view/layouts/dashboard.blade.php -->
                <x-input.textarea label="{{(__('Custom Javascript'))}}"
                                  name="jscode"
                                  value="{!! $jscode !!}"
                                  tooltip="{{(__('This Javascript code will be implemented in your global CP.GG'))}}"/>


                <x-input.checkbox label="{{(__('Alert enabled'))}}"
                                  name="alert_enabled"
                                  value="{{$settings->alert_enabled}}"
                                  tooltip="{{__('Enable or disable the Alert Textbox on the Dashboard for all users.')}}"/>

                <x-input.textarea label="{{(__('Alert Message'))}}"
                                  name="alert_message"
                                  value="{!!  $settings->alert_message !!}"
                                  tooltip="{{(__('This will be Display on every Users Dashboard. You can use HTML'))}}"/>

                <x-input.select label="{{(__('Alert Type'))}}"
                                name="alert_type">
                    <option @if($settings->alert_type == "light") selected
                            @endif value="light">{{__('Light')}}</option>
                    <option @if($settings->alert_type == "success") selected
                            @endif value="success">{{__('Success')}}</option>
                    <option @if($settings->alert_type == "warning") selected
                            @endif value="warning">{{__('Warning')}}</option>
                    <option @if($settings->alert_type == "danger") selected
                            @endif value="danger">{{__('Danger')}}</option>
                    <option @if($settings->alert_type == "info") selected
                            @endif  value="info">{{__('Info')}}</option>

                </x-input.select>


            </div>

            <div class="row mt-4">

                <div class="col-lg-6">
                    <x-input.checkbox label="{{(__('Logo enabled'))}}"
                                      name="logo_enabled"
                                      value="{{$settings->logo_enabled}}"
                                      tooltip="{{__('Enable or disable the Logo on Login-Screen.')}}"/>


                    <x-input.file label="{{(__('Dashboard Logo'))}}"
                                  name="logo"
                                  tooltip="{{(__('Your Dashboards logo, shown on the Login-Screen'))}}"

                                  accept="image/png,image/jpeg,image/jpg"/>
                    <x-input.file label="{{(__('Dashboard Icon'))}}"
                                  name="icon"
                                  tooltip="{{(__('Your Dashboards logo, shown on the top left'))}}"
                                  accept="image/png,image/jpeg,image/jpg"/>

                    <x-input.file label="{{(__('Dashboard FavIcon'))}}"
                                  name="favicon"
                                  tooltip="{{(__('Your Dashboards FavIcon, shown on Tab of a Webbrowser'))}}"
                                  accept="image/x-icon"/>


                </div>
            </div>

            @can('settings.customization.write')
                <div class="form-group d-flex justify-content-end mt-3">
                    <button name="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                </div>
            @endcan
        </div>
    </form>
@endsection
