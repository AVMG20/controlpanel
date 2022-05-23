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
                                <span class="h6 font-semibold text-muted text-sm d-block mb-2">Servers</span>
                                <span class="h4 font-bold mb-0">2</span>
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
                                <span class="h6 font-semibold text-muted text-sm d-block mb-2">{{$settings->credits_display_name}}</span>
                                <span class="h4 font-bold mb-0">245.45</span>
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
                                <span class="h6 font-semibold text-muted text-sm d-block mb-2">{{__(':credits usage', ['credits' => $settings->credits_display_name])}}
                                    <span><i data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Usage per month')}}" class="fas fa-info-circle"></i>
                                    </span>
                                </span>
                                <span class="h4 font-bold mb-0">82.56</span>
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
        </div>
        <!-- /Cards -->

        <div class="row">

            <!-- Information -->
            <div class="col-lg-12 col-xl-6">
                <div class="card p-3">
                    <h2>Welcome to controlpanel</h2>

                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores eaque enim ex facilis fuga harum, impedit ipsam magnam magni nihil nostrum reprehenderit vitae voluptatum! Asperiores aut ducimus error laboriosam laborum.</p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores eaque enim ex facilis fuga harum, impedit ipsam magnam magni nihil nostrum reprehenderit vitae voluptatum! Asperiores aut ducimus error laboriosam laborum.</p>
                </div>
            </div>
            <!-- /Information -->

        </div>

    </div>
@endsection

