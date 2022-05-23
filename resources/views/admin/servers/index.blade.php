@extends('layouts.dashboard')

@section('content')
    <div class="main py-4">

        @can('admin.servers.read')
            <div class="d-flex justify-content-end my-3">
                <a href="{{app(\App\Settings\PterodactylSettings::class)->getUrl() . 'admin/servers'}}" class="btn btn-primary"><i
                        class="fa fas fa-eye pe-2"></i>{{__('View on Pterodactyl')}}</a>
            </div>
        @endcan

        <div class="card card-body border-0 shadow table-wrapper table-responsive">
            <h2 class="mb-4 h5">{{ __('Servers') }}</h2>

            {!! $html->table() !!}

        </div>
    </div>
@endsection

@section('scripts')
    {!! $html->scripts() !!}
@endsection


