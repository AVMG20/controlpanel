<div class="bg-white rounded shadow p-4 my-4">
    <ul class="list-inline list-group-flush list-group-borderless mb-0">

        @can('settings.general.read')
            <li class="list-inline-item px-0 {{ request()->routeIs('settings.general.index') ? 'text-info' : '' }} px-sm-2">
                <a href="{{route('settings.general.index')}}">{{__('General')}}</a>
            </li>
        @endcan

        @can('settings.pterodactyl.read')
            <li class="list-inline-item px-0 {{ request()->routeIs('settings.pterodactyl.index') ? 'text-info' : '' }} px-sm-2">
                <a href="{{route('settings.pterodactyl.index')}}">{{__('Pterodactyl')}}</a>
            </li>
        @endcan

    </ul>
</div>
