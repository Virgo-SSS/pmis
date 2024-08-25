<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}"
                href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        @can('view users')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#users-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-layout-text-window-reverse"></i><span>Users</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="users-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    @can('view users')
                        <li>
                            <a href="{{ route('user') }}" class="nav-link">
                                <i class="bi bi-circle"></i><span>Users</span>
                            </a>
                        </li>
                    @endcan

                    @can('create users')
                        <li>
                            <a href="{{ route('user.create') }}" class="nav-link">
                                <i class="bi bi-circle"></i><span>Create User</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('view banks')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('bank') ? '' : 'collapsed' }}"
                    href="{{ route('bank') }}">
                    <i class="bi bi-building"></i>
                    <span>Bank</span>
                </a>
            </li>
        @endcan

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
                <ul id="logs-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    @can('view activity log')
                        <li>
                            <a href="{{ route('activity-logs') }}" class="nav-link">
                                <i class="bi bi-circle"></i><span>Activity Logs</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @can('view attendances')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('attendance') ? '' : 'collapsed' }}"
                    href="{{ route('attendance') }}">
                    <i class="bi bi-building"></i>
                    <span>Attendance</span>
                </a>
            </li>
        @endcan
    </ul>
</aside>
