@extends('settings.layout')

@section('settings_content')
        <h2 class="mb-4 h5">{{__('Pterodactyl')}}</h2>

        <form method="post" action="{{route('settings.pterodactyl.update')}}">
        @csrf
        @method('PATCH')

            <div class="row">
                <div class="col-lg-6">

                    <div class="form-group mb-3">
                        <label for="pterodactyl_url">{{(__('Pterodactyl URL'))}}</label>
                        <input value="{{old('pterodactyl_url',  $settings->pterodactyl_url)}}" id="pterodactyl_url" name="pterodactyl_url"
                               type="text" placeholder="https://panel.domain.com" class="form-control @error('pterodactyl_url')is-invalid @enderror">
                        @error('pterodactyl_url')
                        <div class="invalid-feedback">{{$message}}</div>@enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="pterodactyl_api_key">{{(__('Pterodactyl API key'))}}</label>
                        <input value="{{old('pterodactyl_api_key',  $settings->pterodactyl_api_key)}}" id="pterodactyl_api_key" name="pterodactyl_api_key"
                               type="text" placeholder="OfbeIWTskurX7PyDWiGr4DG21w72KDZb" class="form-control @error('pterodactyl_api_key')is-invalid @enderror">
                        @error('pterodactyl_api_key')
                        <div class="invalid-feedback">{{$message}}</div>@enderror
                    </div>

                    <div class="form-group d-flex justify-content-end mt-3">
                        <button name="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                    </div>

                </div>
            </div>
        </form>
@endsection
