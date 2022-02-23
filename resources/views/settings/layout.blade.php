@extends('layouts.app')
@section('content')

    <div class="main py-4">

        @include('settings.components.navigation')

        <div class="card card-body border-0 shadow table-wrapper table-responsive">
            @yield('settings_content')
        </div>
    </div>

@endsection




