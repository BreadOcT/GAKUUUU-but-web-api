@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Katalog Mata Kuliah</h1>
</div>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('siswa.katalog.index') }}" method="GET" class="row g-3 align-items-center">
            
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari Nama Mata Kuliah..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-5">
                <select name="filter" class="form-select">
                    <option value="">Urutkan Berdasarkan...</option>
                    <option value="terbaru" {{ request('filter') == 'terbaru' ? 'selected' : '' }}>Paling Baru Ditambahkan</option>
                    <option value="terlama" {{ request('filter') == 'terlama' ? 'selected' : '' }}>Paling Lama Ditambahkan</option>
                    <option value="abjad_a_z" {{ request('filter') == 'abjad_a_z' ? 'selected' : '' }}>Abjad (A - Z)</option>
                    <option value="abjad_z_a" {{ request('filter') == 'abjad_z_a' ? 'selected' : '' }}>Abjad (Z - A)</option>
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Cari</button>
            </div>

        </form>
    </div>
</div>

@if($matakuliah->isEmpty())
    <div class="alert alert-warning text-center shadow-sm" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> Tidak ada mata kuliah yang cocok dengan pencarian <strong>"{{ request('search') }}"</strong>.
    </div>
@else
    <div class="row">
        @foreach($matakuliah as $mk)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-primary">{{ $mk->nama_mk }}</h5>
                    <p class="card-text text-muted small">
                        {{ Str::limit($mk->deskripsi, 100) }}
                    </p>
                    <div class="d-flex align-items-center mt-3">
                        <div class="avatar bg-light rounded-circle text-center pt-1" style="width: 35px; height: 35px;">
                            <i class="fas fa-user text-secondary mt-1"></i>
                        </div>
                        <div class="ms-2">
                            <small class="d-block text-muted" style="line-height: 1;">Tentor:</small>
                            <span class="fw-bold small">{{ $mk->pengampu->userData->first_name ?? 'Tim Pengajar' }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 pb-3">
                    <a href="{{ route('siswa.katalog.show', $mk->id) }}" class="btn btn-primary w-100">
                        Lihat Detail & Daftar
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection