@can('admin.configurations.read')
    <li class="nav-item {{ request()->routeIs('admin.configurations.index') ? 'active' : '' }}">
        <a href="{{ route('admin.configurations.index') }}" class="nav-link">
            <span class="sidebar-icon me-3">
               <i class="fas fa-cog fa-fw"></i>
            </span>
            <span class="sidebar-text">{{ __('Configurations') }}</span>
        </a>
    </li>
@endcan
