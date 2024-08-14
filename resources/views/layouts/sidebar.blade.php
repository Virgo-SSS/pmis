<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('department') ? '' : 'collapsed' }}" href="{{ route('department') }}">
                <i class="bi bi-building"></i>
                <span>Department</span>
            </a>
        </li>
    </ul>
</aside><!-- End Sidebar-->