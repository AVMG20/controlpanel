@can('admin.roles.read')
    <li class="nav-item {{ request()->routeIs('admin.roles.index') ? 'active' : '' }}">
        <a href="{{ route('admin.roles.index') }}" class="nav-link">
                <span class="sidebar-icon me-3">
                   <i class="fas fa-shield-alt fa-fw"></i>
                </span>
            <span class="sidebar-text">{{ __('Roles') }}</span>
        </a>
    </li>
@endcan
