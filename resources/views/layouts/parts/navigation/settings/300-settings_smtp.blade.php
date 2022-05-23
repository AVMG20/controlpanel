@can('settings.smtp.read')
    <li class="list-inline-item px-0 {{ request()->routeIs('settings.smtp.index') ? 'text-info' : '' }} px-sm-2">
        <a href="{{route('settings.smtp.index')}}">{{__('SMTP')}}</a>
    </li>
@endcan
