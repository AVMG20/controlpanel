@can('settings.pterodactyl.read')
    <li class="list-inline-item px-0 {{ request()->routeIs('settings.pterodactyl.index') ? 'text-info' : '' }} px-sm-2">
        <a href="{{route('settings.pterodactyl.index')}}">{{__('Pterodactyl')}}</a>
    </li>
@endcan
