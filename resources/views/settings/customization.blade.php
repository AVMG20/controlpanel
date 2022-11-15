@extends('settings.layout')

@section('settings_content')
    <h2 class="mb-4 h5">{{__('Customization')}}</h2>

    <p class="alert alert-warning">
        <i class="fa fa-exclamation-triangle me-2"></i>
        {!!__('You may need to reload the webpage without caches :hotkey to notice the changes.', ['hotkey' => '<code>CTRL + F5</code>'])!!}
    </p>

    <form method="post" action="{{route('settings.customization.update')}}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="row">
            <div class="col-lg-6">

                <x-input.text label="{{(__('Primary color'))}}"
                              name="primary_color"
                              placeholder="#F2F4F6"
                              tooltip="{{(__('This is the background color of your dashboard.')) . ' (Default: #F2F4F6)'}}"
                              value="{{$settings->primary_color}}"/>

                <x-input.text label="{{(__('Secondary color'))}}"
                              name="secondary_color"
                              placeholder="#FFFFFF"
                              tooltip="{{(__('This is the foreground color of your dashboard.')) . ' (Default: #FFFFFF)'}}"
                              value="{{$settings->secondary_color}}"/>

                <x-input.text label="{{(__('Tertiary color'))}}"
                              name="tertiary_color"
                              placeholder="#1F2937"
                              tooltip="{{(__('This is the color for the buttons and other elements.')) . ' (Default: #1F2937)'}}"
                              value="{{$settings->tertiary_color}}"/>

                <x-input.text label="{{(__('Text color'))}}"
                              name="text_color"
                              placeholder="#111827"
                              tooltip="{{(__('This is the color for most of the text.')) . ' (Default: #111827)'}}"
                              value="{{$settings->text_color}}"/>

            </div>
            <div class="col-lg-6">
                <!-- This file is loaded in the Header of /view/layouts/dashboard.blade.php -->
                <x-input.textarea label="{{(__('Custom Javascript'))}}"
                                  name="jscode"
                                  value="{!! $jscode !!}"
                                  tooltip="{{(__('This Javascript will be applied on all pages'))}}"/>


                <x-input.checkbox label="{{(__('Alert enabled'))}}"
                                  name="alert_enabled"
                                  value="{{$settings->alert_enabled}}"
                                  tooltip="{{__('Enable or disable the alert text box.')}}"/>

                <x-input.textarea label="{{(__('Alert message'))}}"
                                  name="alert_message"
                                  value="{!!  $settings->alert_message !!}"
                                  tooltip="{{(__('This will be displayed on every page. You can use HTML'))}}"/>

                <x-input.select label="{{(__('Alert type'))}}"
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
                    <x-input.checkbox label="{{(__('Enable logo on login and register screen.'))}}"
                                      name="logo_enabled"
                                      value="{{$settings->logo_enabled}}"
                                      tooltip="{{__('Enable or disable the logo on login screen.')}}"/>

                    <x-input.file label="{{(__('Dashboard logo'))}}"
                                  name="logo"
                                  tooltip="{{(__('Your dashboards logo, shown on the login screen.'))}}"

                                  accept="image/png,image/jpeg,image/jpg"/>
                    <x-input.file label="{{(__('Dashboard icon'))}}"
                                  name="icon"
                                  tooltip="{{(__('Your dashboards icon, shown in the navigation menu.'))}}"
                                  accept="image/png,image/jpeg,image/jpg"/>

                    <x-input.file label="{{(__('Dashboard favicon'))}}"
                                  name="favicon"
                                  tooltip="{{(__('Your dashboards favicon, shown on tab of your web browser.'))}}"
                                  accept="image/png,image/jpeg,image/jpg"/>
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
