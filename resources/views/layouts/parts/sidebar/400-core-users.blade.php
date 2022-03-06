
@can('admin.users.read')
    <li class="nav-item {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
        <a href="{{ route('admin.users.index') }}" class="nav-link">
                <span class="sidebar-icon me-3">
                    <i class="fas fa-users fa-fw"></i>
                </span>
            <span class="sidebar-text">{{ __('Users') }}</span>
        </a>
    </li>
@endcan
