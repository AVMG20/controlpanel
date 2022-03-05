@extends('layouts.dashboard')

@section('content')
    <div class="main py-4">

        @can('admin.api.write')
            <div class="d-flex justify-content-end my-3">
                <a href="{{route('admin.api.create')}}" class="btn btn-primary"><i
                        class="fa fas fa-key pe-2"></i>{{__('Create new api key')}}</a>
            </div>
        @endcan

        <div class="card card-body border-0 shadow table-wrapper table-responsive">
            <h2 class="mb-4 h5">{{ __('API keys') }}</h2>

            {!! $html->table() !!}

        </div>
    </div>
@endsection

@section('scripts')
    {!! $html->scripts() !!}
@endsection


