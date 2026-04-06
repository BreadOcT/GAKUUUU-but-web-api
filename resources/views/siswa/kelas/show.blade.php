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
    <div class="col-12">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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
                                <p class="text-muted small mb-3">{{ $modul->deskripsi }}</p>
                                
                                <div class="list-group list-group-flush shadow-sm rounded border">
                                    
                                    @if($modul->file_path)
                                        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center text-primary bg-light">
                                            <i class="fas fa-file-pdf fa-lg me-3"></i> 
                                            <div>
                                                <span class="fw-bold">Buku Modul Utama</span>
                                                <small class="d-block text-muted">Klik untuk mengunduh</small>
                                            </div>
                                        </a>
                                    @endif

                                    @foreach($modul->materi as $materi)
                                        <div class="list-group-item p-0">
                                            
                                            <div class="d-flex align-items-center justify-content-between p-3 list-group-item-action">
                                                
                                                <a href="{{ route('siswa.materi.show', $materi->id) }}" class="text-decoration-none text-dark d-flex align-items-center flex-grow-1">
                                                    <i class="fas fa-video fa-lg me-3 text-danger"></i> 
                                                    <div>
                                                        <span class="fw-bold">{{ $materi->judul }}</span>
                                                        <small class="d-block text-muted">Materi Pembelajaran</small>
                                                    </div>
                                                </a>
                                                
                                                @if(isset($completedMateriIds) && in_array($materi->id, $completedMateriIds))
                                                    <div class="ms-3 text-success fw-bold text-nowrap">
                                                        <i class="fas fa-check-double"></i> Selesai
                                                    </div>
                                                @else
                                                    <form action="{{ route('siswa.materi.mark_done', $materi->id) }}" method="POST" class="ms-3 mb-0">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-success rounded-pill">
                                                            <i class="fas fa-check"></i> Mark Done
                                                        </button>
                                                    </form>
                                                @endif

                                            </div>

                                            @if($materi->tugas)
                                                <a href="{{ route('siswa.tugas.show', $materi->tugas->id) }}" class="text-decoration-none text-dark d-flex align-items-center bg-light p-3 border-top" style="padding-left: 3rem !important;">
                                                    <i class="fas fa-tasks fa-lg me-3 text-primary"></i> 
                                                    <div class="w-100">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="fw-bold">{{ $materi->tugas->judul }}</span>
                                                            <span class="badge bg-danger rounded-pill">
                                                                Tenggat: {{ $materi->tugas->deadline ? \Carbon\Carbon::parse($materi->tugas->deadline)->format('d M, H:i') : '-' }}
                                                            </span>
                                                        </div>
                                                        <small class="d-block text-muted">Tugas / Kuis</small>
                                                    </div>
                                                </a>
                                            @endif
                                            
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <img src="https://img.freepik.com/free-vector/no-data-concept-illustration_114360-536.jpg" alt="Kosong" style="width: 150px; opacity: 0.6;">
                                <p class="text-muted mt-3">Belum ada modul atau materi yang diunggah oleh tentor.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="tab-pane fade" id="info">
                        <div class="p-3">
                            <h5 class="fw-bold mb-3">Deskripsi Kelas</h5>
                            <p class="text-muted">{{ $kelas->deskripsi ?? 'Tidak ada deskripsi khusus.' }}</p>
                            
                            <hr class="my-4">
                            
                            <h5 class="fw-bold mb-3">Jadwal Kelas</h5>
                            @if($kelas->jadwal && $kelas->jadwal->count() > 0)
                                <ul class="list-group list-group-flush w-50">
                                    @foreach($kelas->jadwal as $jadwal)
                                        <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                            <span><i class="far fa-calendar-alt me-2 text-primary"></i> Hari {{ $jadwal->hari }}</span>
                                            <strong>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</strong>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted small">Jadwal belum ditentukan.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection