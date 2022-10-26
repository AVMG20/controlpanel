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
                                <span class="h4 font-bold mb-0">{{$user->server_count}}</span>
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
                                <span class="h4 font-bold mb-0">{{$user->credits_formatted}}</span>
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
                                <span class="h4 font-bold mb-0">{{$user->credit_usage}}</span>
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
          <div class="col-12">

              <!-- Information -->
              <div class="card card-body border-0 shadow table-wrapper table-responsive">
                  <div class="d-flex justify-content-between mb-3">
                      <div>
                          <h2 class="mb-4 h5">{{ __('Servers') }}</h2>
                      </div>
                      <div>
                          <a href="{{route("checkout.store")}}"><button class="btn btn-primary"><i class="fas fa-plus me-2"></i> {{__('Create server')}}</button></a>
                      </div>
                  </div>

                  {!! $html->table() !!}

              </div>
              <!-- /Information -->

          </div>
        </div>

    </div>
@endsection

@section('scripts')
    {!! $html->scripts() !!}
@endsection

