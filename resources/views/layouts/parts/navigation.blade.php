<ul class="nav flex-column pt-3 pt-md-0">
    <li class="nav-item">
        <a href="{{ route('home') }}" class="nav-link d-flex align-items-center">

            @php($settings = app(app\Settings\CustomizationSettings::class))

            <img height="50" width="50" style="border-radius:50%;" alt="Logo" src=
                @if (!empty($settings->custom_icon_filename) && asset('storage/images/' . $settings->custom_icon_filename))
                    "{{asset("storage/images/".$settings->custom_icon_filename)}}"
                @else
                    "{{asset("storage/images/logo.png")}}"
                @endif
            />



            <span class="mt-1 ms-2 sidebar-text">
                {{ config('app.name', 'Laravel') }}
            </span>
        </a>
    </li>

    {!! $navigationManager->render(\App\Enums\NavigationLocation::sidebar) !!}

</ul>
