<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaku! - Bimbingan Belajar Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #343a40; color: white; }
        .sidebar a { color: #cfd2d6; text-decoration: none; padding: 10px 15px; display: block; }
        .sidebar a:hover, .sidebar a.active { background-color: #495057; color: white; }
        .card { border: none; shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 px-0 sidebar collapse d-md-block" id="sidebarMenu">
            <div class="p-3 text-center border-bottom border-secondary">
                <h4 class="mb-0 fw-bold"><i class="fas fa-graduation-cap"></i> Gaku!</h4>
                <small>Siswa Panel</small>
            </div>
            <ul class="nav flex-column mt-3">
                <li class="nav-item">
                    <a href="{{ route('siswa.dashboard') }}" class="{{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('siswa.katalog.index') }}" class="{{ request()->routeIs('siswa.katalog.*') ? 'active' : '' }}">
                        <i class="fas fa-book-open me-2"></i> Katalog Mata Kuliah
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('siswa.nilai.index') }}" class="{{ request()->routeIs('siswa.nilai.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar me-2"></i> Riwayat Nilai
                    </a>
                </li>
                <li class="nav-item mt-4 border-top border-secondary pt-2">
                    <a href="{{ route('siswa.profile.edit') }}" class="{{ request()->routeIs('siswa.profile.*') ? 'active' : '' }}">
                        <i class="fas fa-user-cog me-2"></i> Profil Saya
                    </a>
                </li>
                <li class="nav-item">
                    <form action="#" method="POST" class="d-inline">
                        @csrf 
                        <button type="submit" class="btn btn-link text-start w-100 text-decoration-none" style="color: #cfd2d6; padding: 10px 15px;">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <nav class="navbar navbar-light bg-light d-md-none mb-3 rounded shadow-sm">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <span class="navbar-brand mb-0 h1">Gaku!</span>
                </div>
            </nav>

            @yield('content')
            
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>