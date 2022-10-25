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
            <div class="col-lg-6">
                <x-input.textarea label="{{(__('Custom Javascript'))}}"
                                  name="jscode"
                                  value="{!! $jscode !!}"
                                  tooltip="{{(__('This Javascript code will be implemented in your global CP.GG'))}}"/>

            </div>
            @can('settings.customization.write')
                <div class="form-group d-flex justify-content-end mt-3">
                    <button name="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                </div>
            @endcan
        </div>
    </form>
@endsection
