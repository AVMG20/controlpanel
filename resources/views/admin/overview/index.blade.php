@extends('layouts.dashboard')

@section('content')
    <div class="main py-4">
        <!-- Cards -->
        <div class="row">
            <!-- Server card -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <span class="h6 font-semibold text-muted text-sm d-block mb-2">Total servers</span>
                                <span class="h4 font-bold mb-0">{{$counters['servers']->total}}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-primary text-white text-lg rounded-circle">
                                    <i class="fas fa-server fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Server card -->

            <!-- Credits card -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <span
                                    class="h6 font-semibold text-muted text-sm d-block mb-2">{{__("Total users")}}</span>
                                <span class="h4 font-bold mb-0">{{$counters['users']}}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-success text-white text-lg rounded-circle">
                                    <i class="fas fa-coins fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Credits card -->

            <!-- Usage card -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <span class="h6 font-semibold text-muted text-sm d-block mb-2">{{__('Total :credits ', ['credits' => $settings->credits_display_name])}}
                                </span>
                                <span class="h4 font-bold mb-0">{{$counters['credits']}}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                    <i class="fas fa-chart-line fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Usage card -->

            <!-- Usage card -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <span class="h6 font-semibold text-muted text-sm d-block mb-2">{{__('Total :credits earnings', ['credits' => $settings->credits_display_name])}}
                                </span>
                                <span class="h4 font-bold mb-0">{{$counters['earnings']->active}}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                    <i class="fas fa-chart-line fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Usage card -->
            <!-- Usage card -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <span class="h6 font-semibold text-muted text-sm d-block mb-2">{{__('Last Sync from Pterodactyl')}}
                                </span>
                                <span class="h4 font-bold mb-0">{{$lastPteroSync}}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-warning text-white text-lg rounded-circle">
                                    <i class="fas fa-upload fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Usage card -->
        </div>
        <!-- /Cards -->


    </div>
@endsection


