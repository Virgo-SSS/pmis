<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}"
                href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        @can('view departments')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('department') ? '' : 'collapsed' }}"
                    href="{{ route('department') }}">
                    <i class="bi bi-building"></i>
                    <span>Department</span>
                </a>
            </li>
        @endcan

        @can('view roles')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('role') ? '' : 'collapsed' }}"
                    href="{{ route('role') }}">
                    <i class="bi bi-building"></i>
                    <span>Role and Permission Control</span>
                </a>
            </li>
        @endcan

        @canany(['view activity logs'])
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#logs-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-layout-text-window-reverse"></i><span>Logs</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                @can('view activity log')
                    <ul id="logs-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="{{ route('activity-logs') }}" class="nav-link">
                                <i class="bi bi-circle"></i><span>Activity Logs</span>
                            </a>
                        </li>
                    </ul>
                @endcan
            </li>
        @endcanany
    </ul>
</aside>
