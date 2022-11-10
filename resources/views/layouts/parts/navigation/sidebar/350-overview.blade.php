@can('admin.overview.read')
    <li class="nav-item {{ request()->routeIs('admin.overview.index') ? 'active' : '' }}">
        <a href="{{ route('admin.overview.index') }}" class="nav-link">
                <span class="sidebar-icon me-3">
                    <i class="fas fa-eye fa-fw"></i>
                </span>
            <span class="sidebar-text">{{ __('Overview') }}</span>
        </a>
    </li>
@endcan
