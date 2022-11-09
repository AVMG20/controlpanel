@can('settings.customization.read')
    <li class="list-inline-item px-0 {{ request()->routeIs('settings.customization.index') ? 'text-info' : '' }} px-sm-2">
        <a href="{{route('settings.customization.index')}}">{{__('Customization')}}</a>
    </li>
@endcan
