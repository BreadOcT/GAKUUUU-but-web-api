@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
    <div>
        <h1 class="h3 fw-bold mb-0">{{ $kelas->matakuliah->nama_mk }}</h1>
        <small class="text-muted">{{ $kelas->nama_kelas }}</small>
    </div>
    <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="modul-tab" data-bs-toggle="tab" data-bs-target="#modul" type="button">
                            <i class="fas fa-book me-2"></i> Modul & Materi
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button">
                            <i class="fas fa-info-circle me-2"></i> Info Kelas
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="modul">
                        @forelse($kelas->modul as $modul)
                            <div class="mb-4 border-bottom pb-3">
                                <h5 class="fw-bold text-dark mb-2">{{ $modul->judul }}</h5>
                                <p class="text-muted small mb-2">{{ $modul->deskripsi }}</p>
                                
                                <div class="list-group list-group-flush">
                                    @if($modul->file_path)
                                        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center text-primary">
                                            <i class="fas fa-file-pdf fa-lg me-3"></i> 
                                            <div>
                                                <span class="fw-bold">Buku Modul Utama</span>
                                                <small class="d-block text-muted">Klik untuk mengunduh</small>
                                            </div>
                                        </a>
                                    @endif

                                    @foreach($modul->materi as $materi)
                                        <a href="{{ route('siswa.materi.show', $materi->id) }}" class="list-group-item list-group-item-action d-flex align-items-center">
                                            <i class="fas fa-video fa-lg me-3 text-danger"></i> <div>
                                                <span class="fw-bold">{{ $materi->judul }}</span>
                                                <small class="d-block text-muted">Materi Pembelajaran</small>
                                            </div>
                                            <span class="badge bg-light text-dark ms-auto">Buka</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-muted">Belum ada modul yang diunggah tentor.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="tab-pane fade" id="info">
                        <h5>Deskripsi Kelas</h5>
                        <p>{{ $kelas->deskripsi ?? 'Tidak ada deskripsi khusus.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-light">
            <div class="card-header bg-transparent fw-bold border-bottom">
                <i class="fas fa-tasks me-2"></i> Daftar Tugas
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($tugasList as $tugas)
                        <a href="{{ route('siswa.tugas.show', $tugas->id) }}" class="list-group-item list-group-item-action bg-transparent">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1 fw-bold">{{ $tugas->judul }}</h6>
                                <small class="text-danger">
                                    {{ $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->format('d M') : '-' }}
                                </small>
                            </div>
                            <small class="text-muted">{{ Str::limit($tugas->deskripsi, 50) }}</small>
                        </a>
                    @empty
                        <div class="p-3 text-center text-muted small">Tidak ada tugas aktif.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection