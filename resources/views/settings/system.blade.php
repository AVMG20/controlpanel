@extends('settings.layout')

@section('settings_content')
    <h2 class="mb-4 h5">{{__('System')}}</h2>

    <form method="post" action="{{route('settings.system.update')}}">
        @csrf
        @method('PATCH')

        <div class="row">
            <div class="col-lg-6">
                <x-input.checkbox label="{{(__('Register IP Check'))}}"
                    name="register_ip_check"
                    tooltip="{{(__('Prevent users from making multiple accounts using the same IP.'))}}"
                    value="{{$settings->register_ip_check}}"
                />
                <x-input.checkbox label="{{(__('Creation of new users'))}}"
                    name="creation_of_new_users"
                    tooltip="{{(__('If unchecked, the creation of new users will be blocked.'))}}"
                    value="{{$settings->creation_of_new_users}}"
                />
                <x-input.checkbox label="{{(__('Creation of new servers'))}}"
                    name="creation_of_new_servers"
                    tooltip="{{(__('If unchecked, the creation of new servers will be blocked, this has no effect for users who have full permissions.'))}}"
                    value="{{$settings->creation_of_new_servers}}"
                />
            </div>
        </div>
        @can('settings.system.write')
            <div class="form-group d-flex justify-content-end mt-3">
                <button name="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
            </div>
        @endcan
    </form>
@endsection