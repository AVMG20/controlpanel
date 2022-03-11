@can('admin.notifications.read')
    <li class="nav-item {{ request()->routeIs('admin.notifications.index') ? 'active' : '' }}">
        <a href="{{ route('admin.notifications.index') }}" class="nav-link">
            <span class="sidebar-icon me-3">
               <i class="fas fa-envelope fa-fw"></i>
            </span>
            <span class="sidebar-text">{{ __('Notifications') }}</span>
        </a>
    </li>
@endcan
