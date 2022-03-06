<li class="nav-item {{ request()->routeIs('checkout') ? 'active' : '' }}">
    <a href="{{ route('checkout') }}" class="nav-link">
           <span class="sidebar-icon me-3">
                <i class="fas fa-server fa-fw"></i>
            </span>
        <span class="sidebar-text">{{ __('Create server') }}</span>
    </a>
</li>
