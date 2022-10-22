@extends('settings.layout')

@section('settings_content')
    <h2 class="mb-4 h5">{{__('SMTP')}}</h2>

    <form method="post" action="{{route('settings.smtp.update')}}">
        @csrf
        @method('PATCH')

        <div class="row">
            <div class="col-lg-6">

                <x-input.text label="{{(__('Mail host'))}}"
                              name="mail_host"
                              value="{{$settings->mail_host}}"/>

                <x-input.text label="{{(__('Mail port'))}}"
                              name="mail_port"
                              value="{{$settings->mail_port}}"/>

                <x-input.select label="{{(__('Mail encryption'))}}"
                                name="mail_encryption">
                    <option value="null">{{__('None')}}</option>
                    <option value="tls">{{__('Transport Layer Security (TLS)')}}</option>
                    <option value="ssl">{{__('Secure Sockets Layer (SSL)')}}</option>
                </x-input.select>

                <x-input.text label="{{(__('Mail username'))}}"
                              name="mail_username"
                              value="{{$settings->mail_username}}"/>

                <x-input.text label="{{(__('Mail password'))}}"
                              type="password"
                              name="mail_password"
                              value="{{$settings->mail_password}}"/>

                <x-input.text label="{{(__('Mail from address'))}}"
                              name="mail_from_address"
                              value="{{$settings->mail_from_address}}"
                              tooltip="{{__('Enter an email address that all outgoing emails will originate from.')}}"/>

                <x-input.text label="{{(__('Mail from name'))}}"
                              name="mail_from_name"
                              value="{{$settings->mail_from_name}}"
                              tooltip="{{__('The name that emails should appear to come from.')}}"/>

                <x-input.checkbox label="{{(__('Mail enabled'))}}"
                                   name="mail_enabled"
                                   value="{{$settings->mail_enabled}}"
                                   tooltip="{{__('Enable or disable the mail system.')}}"/>

                @can('settings.pterodactyl.write')
                    <div class="form-group d-flex justify-content-end mt-3">
                        <button name="send_test_email" type="submit" class="btn btn-info me-3">{{__('Test')}}</button>
                        <button name="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                    </div>
                @endcan

            </div>
        </div>
    </form>
@endsection
