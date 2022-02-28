@extends('layouts.guest')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <div class="mb-3">
                    <h4 class="text-primary mb-3">{{__('Configure')}}</h4>
                    <span>{{__('Configure your desired options and continue to checkout.')}}</span>
                </div>

                <form class="card p-4" action="POST" method="">
                    @csrf

                    <x-input.select
                        label="{{__('Server software')}}"
                        name="location">
                        <option value="">
                            Minecraft java edition
                        </option>
                    </x-input.select>

                    <x-input.select
                        label="{{__('Choose server location')}}"
                        name="location">
                        <option value="">
                            Germany
                        </option>
                    </x-input.select>


                    <div class="d-flex justify-content-center align-items-center border-bottom pb-2 my-3">
                        <span>{{__('Configurable Options')}}</span>
                    </div>

                    <x-input.select
                        label="{{__('Specifications')}}"
                        name="location">
                        <option value="">
                            Basic minecraft server
                        </option>
                    </x-input.select>

                    <div class="d-flex justify-content-center align-items-center border-bottom pb-2 my-3">
                        <span>{{__('Additional Required Information')}}</span>
                    </div>

                    <x-input.text
                        label="{{__('Server name')}}"
                        value="My awesome server"
                        name="name"/>

                </form>
            </div>

            <div class="col-md-5 col-lg-4 order-md-last">
                <h4 class="d-flex justify-content-between mb-3">
                    <span class="text-primary">{{__('Order Summary')}}</span>
                </h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex flex-column ">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="my-0">Basic minecraft server</h6>
                            <span class="text-muted"><strong><i class="fas fa-coins pe-4"></i>250</strong></span>
                        </div>
                        <div>
                            <small class="text-muted">Minecraft server suited for a small group of friends or community</small>
                        </div>
                    </li>
                    <li class="list-group-item d-flex flex-column ">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Total ({{$credits_display_name}})</span>
                            <span><strong><i class="fas fa-coins pe-4"></i>250</strong></span>
                        </div>
                        <div>
                            <small class="text-muted">{{__('Pricing is per month')}}</small>
                        </div>
                    </li>
                </ul>

                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex flex-column ">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="my-0">{{__('Setup fee')}}</h6>
                            <span class="text-muted"><strong><i class="fas fa-coins pe-4"></i>2</strong></span>
                        </div>
                        <div>
                            <small class="text-muted">{{__('This configuration includes an initial setup fee')}}</small>
                        </div>
                    </li>
                </ul>

                <div class="d-flex justify-content-center align-items-center">
                    <button class="btn btn-primary">
                        {{__('Continue')}}
                        <i class="fas ps-2 fa-arrow-right"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>
@endsection
