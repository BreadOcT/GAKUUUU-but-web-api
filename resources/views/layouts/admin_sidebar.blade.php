<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse text-white">
    <div class="position-sticky pt-3 sidebar-sticky">
        
        <div class="text-center mb-4 px-3 d-none d-md-block border-bottom border-secondary pb-3">
            <h4 class="fw-bold text-danger"><i class="fas fa-user-shield"></i> Gaku!</h4>
            <small class="text-muted">Administrator</small>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active bg-danger text-white rounded mx-2' : 'text-secondary' }} px-3 py-2" 
                   href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-2 text-muted text-uppercase small">
                <span>Manajemen Data</span>
            </h6>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active bg-danger text-white rounded mx-2' : 'text-secondary' }} px-3 py-2" 
                   href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users me-2"></i> Data Pengguna
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.verifikasi.*') ? 'active bg-danger text-white rounded mx-2' : 'text-secondary' }} px-3 py-2" 
                   href="{{ route('admin.verifikasi.index') }}">
                    <i class="fas fa-user-check me-2"></i> Verifikasi Tentor
                </a>
            </li>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-2 text-muted text-uppercase small">
                <span>Moderasi & Layanan</span>
            </h6>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.feedback.*') ? 'active bg-danger text-white rounded mx-2' : 'text-secondary' }} px-3 py-2" 
                   href="{{ route('admin.feedback.index') }}">
                    <i class="fas fa-comments me-2"></i> Moderasi Feedback
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.cs.*') ? 'active bg-danger text-white rounded mx-2' : 'text-secondary' }} px-3 py-2" 
                   href="{{ route('admin.cs.index') }}">
                    <i class="fas fa-headset me-2"></i> Customer Service
                </a>
            </li>
        </ul>

        <div class="mt-5 px-3 border-top border-secondary pt-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-secondary w-100 text-start border-0 text-white">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
</nav>