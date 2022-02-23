@extends('settings.layout')

@section('settings_content')
        <h2 class="mb-4 h5">{{__('General')}}</h2>

        <form method="post" action="{{route('settings.general.update')}}">
        @csrf
        @method('PATCH')

            <div class="row">
                <div class="col-lg-6">

                    <div class="form-group mb-3">
                        <label for="credits_display_name">{{(__('Credits display name'))}}</label>
                        <input value="{{old('credits_display_name',  $settings->credits_display_name)}}" id="credits_display_name" name="credits_display_name"
                               type="text" class="form-control @error('credits_display_name')is-invalid @enderror">
                        @error('credits_display_name')
                        <div class="invalid-feedback">{{$message}}</div>@enderror
                    </div>


                    <div class="form-group d-flex justify-content-end mt-3">
                        <button name="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                    </div>

                </div>
            </div>
        </form>
@endsection
