<ul class="nav flex-column pt-3 pt-md-0">
    <li class="nav-item">
        <a href="{{ route('home') }}" class="nav-link d-flex align-items-center">

            @php($settings = app(app\Settings\CustomizationSettings::class))

            @if (!empty($settings->custom_icon_filename) && asset('storage/images/' . $settings->custom_icon_filename))
                <span class="sidebar-icon me-3">
                    <img src="{{asset("storage/images/".$settings->custom_icon_filename)}}" height="50" width="50" alt="Logo">
                </span>
            @endif

            <span class="mt-1 ms-1 sidebar-text">
                {{ config('app.name', 'Laravel') }}
            </span>
        </a>
    </li>

    {!! $navigationManager->render(\App\Enums\NavigationLocation::sidebar) !!}

</ul>
