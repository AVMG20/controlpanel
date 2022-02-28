@extends('settings.layout')

@section('settings_content')
        <h2 class="mb-4 h5">{{__('General')}}</h2>

        <form method="post" action="{{route('settings.general.update')}}">
        @csrf
        @method('PATCH')

            <div class="row">
                <div class="col-lg-6">

                    <x-input.text label="{{(__('Main site'))}}"
                                  name="main_site"
                                  tooltip="{{(__('Your main website'))}}"
                                  value="{{$settings->main_site}}"/>

                    <x-input.text label="{{(__('Credits display name'))}}"
                                   name="credits_display_name"
                                   tooltip="{{(__('Change the name Credits to anything you like'))}}"
                                   value="{{$settings->credits_display_name}}"/>

                    <div class="form-group d-flex justify-content-end mt-3">
                        <button name="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                    </div>

                </div>
            </div>
        </form>
@endsection
