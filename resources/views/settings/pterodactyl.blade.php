@extends('settings.layout')

@section('settings_content')
        <h2 class="mb-4 h5">{{__('Pterodactyl')}}</h2>

        <form method="post" action="{{route('settings.pterodactyl.update')}}">
        @csrf
        @method('PATCH')

            <div class="row">
                <div class="col-lg-6">

                    <div class="form-group mb-3">
                        <label for="url">{{(__('Pterodactyl URL'))}}</label>
                        <input value="{{old('url',  $settings->url)}}" id="url" name="url"
                               type="text" placeholder="https://panel.domain.com" class="form-control @error('url')is-invalid @enderror">
                        @error('url')
                        <div class="invalid-feedback">{{$message}}</div>@enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="api_key">{{(__('Pterodactyl API key'))}}</label>
                        <input value="{{old('api_key',  $settings->api_key)}}" id="api_key" name="api_key"
                               type="text" placeholder="OfbeIWTskurX7PyDWiGr4DG21w72KDZb" class="form-control @error('api_key')is-invalid @enderror">
                        @error('api_key')
                        <div class="invalid-feedback">{{$message}}</div>@enderror
                    </div>

                    <div class="form-group d-flex justify-content-end mt-3">
                        <button name="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                    </div>

                </div>
            </div>
        </form>
@endsection
