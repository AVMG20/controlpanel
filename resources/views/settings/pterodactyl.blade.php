@extends('settings.layout')

@section('settings_content')
        <h2 class="mb-4 h5">{{__('Pterodactyl')}}</h2>

        <form method="post" action="{{route('settings.pterodactyl.update')}}">
        @csrf
        @method('PATCH')

            <div class="row">
                <div class="col-lg-6">

                    <x-input.text label="{{(__('Pterodactyl URL'))}}"
                                  name="url"
                                  value="{{$settings->url}}"
                                  tooltip="{{(__('Enter the URL to your Pterodactyl installation'))}}"/>

                    <x-input.text label="{{(__('Pterodactyl API key'))}}"
                                  name="api_key"
                                  value="{{$settings->api_key}}"
                                  tooltip="{{(__('Enter the API key obtained from your Pterodactyl panel'))}}"/>

                    <div class="form-group d-flex justify-content-end mt-3">
                        <button name="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                    </div>

                </div>
            </div>
        </form>
@endsection
