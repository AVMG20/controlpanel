@extends('layouts.dashboard')

@section('content')
    <div class="main py-4">

        @can('admin.configurations.write')
            <div class="d-flex justify-content-end my-3">
                <a href="{{route('admin.configurations.create')}}" class="btn btn-primary mx-2"><i
                        class="fa fas fa-cog pe-2"></i>{{__('Create configuration')}}</a>

                <a href="{{route('admin.configurations.sync')}}" class="btn btn-primary mx-2"><i
                        class="fa fas fa-sync pe-2"></i>{{__('Sync Pterodactyl')}}</a>
            </div>
        @endcan

        <div class="card card-body border-0 shadow table-wrapper table-responsive">
            <h2 class="mb-4 h5">{{ __('Configurations') }}</h2>

            {!! $html->table() !!}

        </div>
    </div>
@endsection

@section('scripts')
    {!! $html->scripts() !!}
@endsection


