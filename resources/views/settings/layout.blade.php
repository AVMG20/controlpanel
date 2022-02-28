@extends('layouts.dashboard')
@section('content')

    <div class="main py-4">

        @include('settings.parts.navigation')

        <div class="card card-body border-0 shadow table-wrapper table-responsive">
            @yield('settings_content')
        </div>
    </div>

@endsection




