@extends('layouts.app')

@section('content')
<div class="container py-3">
    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Kembali ke Kelas
    </a>

    <div class="card shadow">
        <div class="card-header bg-dark text-white p-3">
            <h4 class="mb-0">{{ $materi->judul }}</h4>
            <small>{{ $materi->modul->kelas->matakuliah->nama_mk }}</small>
        </div>
        <div class="card-body">
            <p class="lead">{{ $materi->deskripsi }}</p>
            <hr>
            
            <div class="text-center py-5 bg-light rounded border">
                <i class="fas fa-file-alt fa-5x text-secondary mb-3"></i>
                <h5>File Materi Tersedia</h5>
                <p>Silakan unduh atau baca materi ini melalui perangkat Anda.</p>
                @if($materi->file_path)
                    <a href="{{ asset('storage/' . $materi->file_path) }}" class="btn btn-primary btn-lg" target="_blank">
                        <i class="fas fa-download me-2"></i> Download / Buka Materi
                    </a>
                @else
                    <button class="btn btn-secondary" disabled>File tidak tersedia</button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection