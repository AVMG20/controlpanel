@can('settings.system.read')
    <li class="list-inline-item px-0 {{ request()->routeIs('settings.system.index') ? 'text-info' : '' }} px-sm-2">
        <a href="{{route('settings.system.index')}}">{{__('System')}}</a>
    </li>
@endcan