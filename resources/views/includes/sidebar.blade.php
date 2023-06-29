<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-book"></i>
        </div>
        <div class="sidebar-brand-text mx-3">{{ config('app.name') }}</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <li class="nav-item @if(Request::route()->getName() == 'admin.dashboard') active @endif">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-item @if(Request::route()->getName() == 'admin.lectures.index') active @endif">
        <a class="nav-link" href="{{ route('admin.lectures.index') }}">
            <i class="fas fa-chalkboard-teacher"></i>
            <span>Master Lecture</span>
        </a>
    </li>

    <li class="nav-item @if(Request::route()->getName() == 'admin.students.index') active @endif">
        <a class="nav-link" href="{{ route('admin.students.index') }}">
            <i class="fas fa-users"></i>
            <span>Master Student</span>
        </a>
    </li>

    <li class="nav-item @if(Request::route()->getName() == 'admin.activities.index') active @endif">
        <a class="nav-link" href="{{ route('admin.activities.index') }}">
            <i class="fas fa-star"></i>
            <span>Master Activity</span>
        </a>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    {{-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Components</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Components:</h6>
                <a class="collapse-item" href="buttons.html">Buttons</a>
                <a class="collapse-item" href="cards.html">Cards</a>
            </div>
        </div>
    </li> --}}

    <!-- Divider -->
    <div class="text-center ml-3 mr-3 mb-3 mt-5 d-md-inline">
        <button class="btn btn-danger btn-block border-0 logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
