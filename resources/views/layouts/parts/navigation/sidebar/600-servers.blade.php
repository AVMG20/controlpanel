@can('admin.servers.read')
    <li class="nav-item {{ request()->routeIs('admin.servers.index') ? 'active' : '' }}">
        <a href="{{ route('admin.servers.index') }}" class="nav-link">
                <span class="sidebar-icon me-3">
                    <i class="fas fa-server fa-fw"></i>
                </span>
            <span class="sidebar-text">{{ __('Servers') }}</span>
        </a>
    </li>
@endcan
