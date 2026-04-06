@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Saya</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <span class="text-muted">Halo, {{ Auth::user()->username ?? 'Siswa' }}</span>
    </div>
</div>

<div class="alert alert-primary shadow-sm" role="alert">
    <i class="fas fa-info-circle me-2"></i> Selamat datang di <strong>Gaku!</strong> Semangat belajar hari ini.
</div>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('siswa.dashboard') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Cari Mata Kuliah..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="filter" class="form-select">
                    <option value="">Filter Urutan...</option>
                    <option value="terbaru" {{ request('filter') == 'terbaru' ? 'selected' : '' }}>Pendaftaran Terbaru</option>
                    <option value="terlama" {{ request('filter') == 'terlama' ? 'selected' : '' }}>Pendaftaran Terlama</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    @foreach($enrollments as $enrollment)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <span class="badge bg-success float-end">Aktif</span>
                    <h5 class="card-title fw-bold text-primary">{{ $enrollment->kelas->matakuliah->nama_mk }}</h5>
                </div>
                <div class="card-body">
                    <div class="mt-3">
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Progress Belajar</span>
                            <span>{{ $enrollment->completion_rate }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $enrollment->completion_rate }}%;" aria-valuenow="{{ $enrollment->completion_rate }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    </div>
                <div class="card-footer bg-white border-top-0 pb-3">
                    <a href="{{ route('siswa.kelas.show', $enrollment->kelas->id) }}" class="btn btn-outline-primary w-100">
                        Masuk Kelas <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection