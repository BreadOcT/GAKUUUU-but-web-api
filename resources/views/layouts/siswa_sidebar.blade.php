<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse text-white">
    <div class="position-sticky pt-3 sidebar-sticky">
        
        <div class="text-center mb-4 px-3 d-none d-md-block border-bottom border-secondary pb-3">
            <h4 class="fw-bold text-white"><i class="fas fa-graduation-cap"></i> Gaku!</h4>
            <small class="text-muted">Panel Siswa</small>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('siswa.dashboard') ? 'active bg-primary text-white rounded mx-2' : 'text-secondary' }} px-3 py-2" 
                   href="{{ route('siswa.dashboard') }}">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('siswa.katalog.*') ? 'active bg-primary text-white rounded mx-2' : 'text-secondary' }} px-3 py-2" 
                   href="{{ route('siswa.katalog.index') }}">
                    <i class="fas fa-book-open me-2"></i> Katalog & Jadwal
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('siswa.nilai.*') ? 'active bg-primary text-white rounded mx-2' : 'text-secondary' }} px-3 py-2" 
                   href="{{ route('siswa.nilai.index') }}">
                    <i class="fas fa-chart-bar me-2"></i> Riwayat Nilai
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-2 text-muted text-uppercase small">
            <span>Utilitas</span>
        </h6>
        
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('siswa.profile.*') ? 'active bg-primary text-white rounded mx-2' : 'text-secondary' }} px-3 py-2" 
                   href="{{ route('siswa.profile.edit') }}">
                    <i class="fas fa-user-cog me-2"></i> Profil Saya
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('siswa.feedback.*') ? 'active bg-primary text-white rounded mx-2' : 'text-secondary' }} px-3 py-2" 
                   href="{{ url('/siswa/feedback') }}"> 
                   <i class="fas fa-star me-2"></i> Beri Feedback
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('siswa.kontak-cs.*') ? 'active bg-primary text-white rounded mx-2' : 'text-secondary' }} px-3 py-2" 
                   href="{{ url('/siswa/kontak-cs') }}">
                    <i class="fas fa-headset me-2"></i> Hubungi CS
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