@extends('layouts.dashboard')

@section('content')
    <div class="main py-4">

        <div class="card card-body border-0 shadow table-wrapper table-responsive">
            <form method="post"
                  action="{{isset($configuration) && !request()->routeIs('admin.configurations.clone') ? route('admin.configurations.update', $configuration->id) : route('admin.configurations.store')}}">
                @csrf
                @isset($configuration)
                    @if(!request()->routeIs('admin.configurations.clone'))
                        @method('PATCH')
                    @endif
                @endisset

                <div class="d-flex justify-content-between">
                    <h2 class="mb-4 h5">{{ isset($configuration) ?  __('Edit configuration') : __('Create configuration') }}</h2>
                    <div class="form-check form-switch ">

                        <input class="form-check-input" name="disabled" value="1" @if(isset($configuration) && $configuration->disabled) checked @endif type="checkbox" id="flexSwitchCheckDefault">
                        <label class="form-check-label" for="flexSwitchCheckDefault">{{__('Disabled')}}</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">

                        <p class="text-muted">{{__('Information')}}</p>

                        <x-input.text label="{{(__('Name'))}}"
                                      name="name"
                                      value="{{ isset($configuration) ? $configuration->name : null}}"/>

                        <x-input.textarea label="{{(__('Small description'))}}"
                                          name="description"
                                          value="{{ isset($configuration) ? $configuration->description : null}}"/>


                        <div class="row mt-4">
                            <div class="col-lg-6">
                                <p class="text-muted">{{__('Resource Management')}}</p>

                                <x-input.number label="{{(__('Memory'))}}"
                                                name="memory"
                                                min="0"
                                                max="999999999"
                                                prepend="{{__('MB')}}"
                                                tooltip="{{__('The maximum amount of memory allowed for this container. Setting this to 0 will allow unlimited memory in a container.')}}"
                                                value="{{ isset($configuration) ? $configuration->memory : null}}"/>

                                <x-input.number label="{{(__('Cpu'))}}"
                                                name="cpu"
                                                min="0"
                                                max="999999999"
                                                prepend="%"
                                                tooltip="{{__('If you do not want to limit CPU usage, set the value to 0. To determine a value, take the number of threads and multiply it by 100.')}}"
                                                value="{{ isset($configuration) ? $configuration->cpu : null}}"/>

                                <x-input.number label="{{(__('Disk space'))}}"
                                                name="disk"
                                                min="0"
                                                max="999999999"
                                                prepend="{{__('MB')}}"
                                                tooltip="{{__('This server will not be allowed to boot if it is using more than this amount of space. If a server goes over this limit while running it will be safely stopped and locked until enough space is available. Set to 0 to allow unlimited disk usage.')}}"
                                                value="{{ isset($configuration) ? $configuration->disk : null}}"/>

                                <x-input.number label="{{(__('Swap'))}}"
                                                name="swap"
                                                min="0"
                                                max="999999999"
                                                prepend="{{__('MB')}}"
                                                tooltip="{{__('Setting this to 0 will disable swap space on this server. Setting to -1 will allow unlimited swap.')}}"
                                                value="{{ isset($configuration) ? $configuration->swap : 0}}"/>


                                <x-input.number label="{{(__('Block IO Weight'))}}"
                                                name="io"
                                                min="0"
                                                max="999999999"
                                                tooltip="{{__('Advanced: The IO performance of this server relative to other running containers on the system. Value should be between 10 and 1000')}}"
                                                value="{{ isset($configuration) ? $configuration->io : 500}}"/>

                            </div>

                            <div class="col-lg-6">
                                <p class="text-muted">{{__('Application Feature Limits')}}</p>

                                <x-input.number label="{{(__('Database Limit'))}}"
                                                name="databases"
                                                min="0"
                                                max="999999999"
                                                tooltip="{{__('The total number of databases a user is allowed to create for this server.')}}"
                                                value="{{ isset($configuration) ? $configuration->databases : 0}}"/>

                                <x-input.number label="{{(__('Backup Limit'))}}"
                                                name="backups"
                                                min="0"
                                                max="999999999"
                                                tooltip="{{__('The total number of backups that can be created for this server.')}}"
                                                value="{{ isset($configuration) ? $configuration->backups : 0}}"/>

                                <x-input.number label="{{(__('Allocation Limit'))}}"
                                                name="allocations"
                                                min="0"
                                                max="999999999"
                                                tooltip="{{__('The total number of allocations a user is allowed to create for this server.')}}"
                                                value="{{ isset($configuration) ? $configuration->allocations : 0}}"/>
                            </div>
                        </div>



                    </div>

                    <div class="col-lg-6">

                        <p class="text-muted">{{__('Pricing')}}</p>

                        <x-input.number label="{{(__('Price per month'))}}"
                                        name="price"
                                        min="0"
                                        max="9999999999999"
                                        step=".000001"
                                        tooltip="{{__('Price per month. Servers are charged hourly (price / 30 / 24)')}}"
                                        value="{{ isset($configuration) ? $configuration->price : null}}"/>

                        <x-input.number label="{{(__('Setup price'))}}"
                                        name="setup_price"
                                        min="0"
                                        max="9999999999999"
                                        step=".000001"
                                        tooltip="{{__('Charge an initial fee for creating the server.')}}"
                                        value="{{ isset($configuration) ? $configuration->setup_price : null}}"/>

                        <x-input.number
                            label="{{(__('Minimum required :credits_display_name' ,['credits_display_name' => $settings->credits_display_name]))}}"
                            name="minimum_required_credits"
                            min="0"
                            max="9999999999999"
                            step=".000001"
                            tooltip="{{__('The minimum amount of :credits_display_name required for this configuration',['credits_display_name' => $settings->credits_display_name])}}"
                            value="{{ isset($configuration) ? $configuration->minimum_required_credits : null}}"/>


                        <p class="mt-4 text-muted">{{__('Applicable locations and eggs')}}</p>

                        <x-input.select label="{{(__('Locations'))}}"
                                        multiple
                                        name="locations">
                            @foreach($locations as $location)
                                <option @if(isset($configuration) && $configuration->locations->contains($location)) selected
                                        @endif value="{{$location->id}}">{{$location->name}}</option>
                            @endforeach
                        </x-input.select>

                        <x-input.select label="{{(__('Eggs'))}}"
                                        multiple
                                        style="height: 180px;"
                                        name="eggs">
                            @foreach($eggs as $egg)
                                <option @if(isset($configuration) && $configuration->eggs->contains($egg)) selected
                                        @endif value="{{$egg->id}}">{{$egg->name}}</option>
                            @endforeach
                        </x-input.select>

                    </div>
                </div>

                <div class="form-group d-flex justify-content-end mt-3">
                    <button name="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                </div>
            </form>

        </div>

    </div>
@endsection

