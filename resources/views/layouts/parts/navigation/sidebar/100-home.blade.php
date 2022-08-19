<li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
    <a href="{{ route('dashboard.index') }}" class="nav-link">
            <span class="sidebar-icon me-3">
                <i class="fas fa-home fa-fw"></i>
            </span>
        <span class="sidebar-text">{{ __('Dashboard') }}</span>
    </a>
</li>
