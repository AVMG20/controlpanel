@can('settings.general.read')
    <li class="list-inline-item px-0 {{ request()->routeIs('settings.general.index') ? 'text-info' : '' }} px-sm-2">
        <a href="{{route('settings.general.index')}}">{{__('General')}}</a>
    </li>
@endcan
