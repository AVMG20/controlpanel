@can('settings.general.read')
    <li class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
        <a href="{{ route('settings.general.index') }}" class="nav-link">
            <span class="sidebar-icon me-3">
               <i class="fas fa-cogs fa-fw"></i>
            </span>
            <span class="sidebar-text">{{ __('Settings') }}</span>
        </a>
    </li>
@endcan
