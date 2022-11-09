<div>
    <div class="container">
        @if(!$creation_of_new_servers)
            {{-- Check if user has all permissions --}}
            @if(Auth::user()->can('*'))
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                    </svg>
                    <div class="text-primary">
                        {{__('The creation of new servers has been disabled for ordinary users, you enable it again')}}
                            <a href="{{route('settings.system.index')}}" class="alert-link">{{__('here')}}</a>.
                    </div>
                </div>
            @endcan
        @endif
        <form action="{{route('checkout.store')}}" method="POST" wire:submit.prevent="createServer">
            @csrf
            <div class="row">

                <!-- Form -->
                <div class="col-md-7">
                    <div class="mb-3">
                        <h4 class="text-primary mb-3">{{__('Configure')}}</h4>
                        <span>{{__('Configure your desired options and continue to checkout.')}}</span>
                    </div>

                    <div class="card p-4">

                        <div class="form-group mb-3">
                            <label for="egg">{{__('Server software')}}</label>
                            <select wire:model="selected_egg_id" name="egg" class="form-control" id="egg">
                                <option value="-1" disabled>{{__('Select software specification')}}</option>
                                @foreach ($nests as $nest)
                                    <optgroup label="{{$nest['name']}}">
                                        @foreach($nest['eggs'] as $egg)
                                            <option value="{{$egg['id']}}">{{$egg['name']}}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-center align-items-center border-bottom pb-2 my-3">
                            <span>{{__('Configurable Options')}}</span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="location">{{__('Server location')}}</label>
                            <select wire:model="selected_location_id" name="location" class="form-control"
                                    id="location">
                                <option value="-1" disabled>{{__('Select server location')}}</option>
                                @foreach ($locations as $location)
                                    <option
                                        {{$location['description']}} value="{{$location['id']}}">{{$location['name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="configuration">{{__('Server configuration')}}</label>
                            <select wire:model="selected_configuration_id" name="configuration" class="form-control"
                                    id="configuration">
                                <option value="-1" disabled>{{__('Select server configuration')}}</option>
                                @foreach ($configurations as $configuration)
                                    <option @if(count($configurations) === 1) selected
                                            @endif value="{{$configuration['id']}}">{{$configuration['name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-center align-items-center border-bottom pb-2 my-3">
                            <span>{{__('Additional Required Information')}}</span>
                        </div>

                        <div class="form-group">
                            <label for="name">{{__('Server name')}}</label>
                            <input wire:model="name" name="name" id="name" type="text"
                                   class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>
                </div>


                <!-- Order summary -->
                <div class="col-md-5 col-lg-4 order-md-last">

                    <h4 class="d-flex justify-content-between mb-3 mt-sm-3">
                        <span class="text-primary">{{__('Order Summary')}}</span>
                    </h4>

                    <ul class="list-group mb-3">

                        <!-- Selected egg -->
                        <li class="list-group-item rounded-top d-flex flex-column ">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="my-0">{{__('Server software')}}</h6>
                            </div>
                            <div>
                                <!-- Egg name is placed here -->
                                <small
                                    class="text-muted">{{$selected_egg ? $selected_egg['name'] : __('Not selected')}}</small>
                            </div>
                        </li>

                        <!-- Selected location -->
                        <li class="list-group-item d-flex flex-column ">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="my-0">{{__('Server location')}}</h6>
                            </div>
                            <div>
                                <!-- Location name is placed here -->
                                <small
                                    class="text-muted my-0">{{$selected_location ? $selected_location['name'] : __('Not selected')}}</small>
                            </div>
                        </li>


                        <!-- Selected configuration -->
                        <li class="list-group-item d-flex flex-column ">
                            <div class="d-flex justify-content-between align-items-center">

                                <h6 class="my-0">{{__('Server configuration')}}</h6>

                                <span class="text-muted fw-bold">
                                    <i class="text-b fas fa-coins pe-4"></i>
                                    <!-- Configuration price is placed here -->
                                    <span>{{$selected_configuration ? $selected_configuration['price'] : 0}}</span>
                                </span>
                            </div>
                            <div>
                                <!-- Configuration name is placed here -->
                                <small class="my-0 mb-1 @if(!$selected_configuration) text-muted @endif">
                                    {{$selected_configuration ? $selected_configuration['name'] : __('Not selected')}}
                                </small>
                                <!-- Configuration description is placed here -->
                                <small class="text-muted">
                                    {!! $selected_configuration ? $selected_configuration['description'] : '' !!}
                                </small>
                            </div>
                        </li>

                    </ul>


                    <ul class="list-group mb-3">
                        <!-- Setup fee -->
                        @if($selected_configuration && $selected_configuration['setup_price'] > 0)
                            <li class="list-group-item rounded-top d-flex flex-column ">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="my-0">{{__('Setup costs')}}</h6>
                                    <span class="text-muted"><i class="fas fa-coins pe-4"></i>
                                        <!-- Setup price is located here -->
                                <span class="fw-bold">{{$selected_configuration['setup_price']}}</span>
                            </span>
                                </div>
                                <div>
                                    <small class="text-muted">
                                        {{__('This configuration includes an initial setup fee.')}}
                                    </small>
                                </div>
                            </li>
                        @endif

                        <!-- Total per month -->
                        <li class="list-group-item rounded-bottom d-flex flex-column ">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="my-0">{{__('Total')}} ({{$credits_display_name}})</h6>

                                <span><i class="fas fa-coins pe-4"></i>{{$selected_configuration ? $selected_configuration['price'] : 0}}<span
                                        class="fw-bold"></span></span>

                            </div>
                            <div>
                                <small class="text-muted">{{__('Pricing is per month')}}</small>
                            </div>
                        </li>


                    </ul>

                    @if($selected_configuration)
                        <div class="d-flex justify-content-center align-items-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas pe-2 fa-server"></i>
                                {{__('Create server')}}
                            </button>
                        </div>
                    @endif
                </div>
                <!-- End order summary -->

            </div>
        </form>
    </div>

    @if (Session::has('error'))
        <script defer>
            Swal.fire({
                icon: 'error',
                title: 'Error.',
                html: '{{ Session::get('error') }}',
            })
        </script>
    @endif
</div>
