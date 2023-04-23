<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">{{ config('app.name') }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">{{ config('app.name_short') }}</a>
        </div>
        <ul class="sidebar-menu">
            <li class="active">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-gauge"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-person-chalkboard"></i>
                    <span>Master Lecture</span>
                </a>
            </li>

            <li class="">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-users"></i>
                    <span>Master Student</span>
                </a>
            </li>

            <li class="">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-star"></i>
                    <span>Master Activity</span>
                </a>
            </li>
        </ul>

        <div class="hide-sidebar-mini mt-4 mb-4 p-3">
            <button id="logout-sidebar" class="btn btn-danger btn-lg btn-block btn-icon-split">
                <i class="fas fa-right-from-bracket"></i> Logout
            </button>
        </div>
    </aside>
</div>
