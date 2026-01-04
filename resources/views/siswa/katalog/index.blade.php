@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Katalog Mata Kuliah</h1>
</div>

<div class="row">
    @foreach($matakuliah as $mk)
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title fw-bold text-primary">{{ $mk->nama_mk }}</h5>
                <p class="card-text text-muted small">
                    {{ Str::limit($mk->deskripsi, 100) }}
                </p>
                <div class="d-flex align-items-center mt-3">
                    <div class="avatar bg-light rounded-circle text-center pt-1" style="width: 35px; height: 35px;">
                        <i class="fas fa-user text-secondary"></i>
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
@endsection