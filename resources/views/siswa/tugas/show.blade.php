@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <span class="badge bg-info text-dark mb-2">{{ ucfirst($tugas->jenis) }}</span>
                    <h3 class="card-title fw-bold">{{ $tugas->judul }}</h3>
                    <p class="text-muted border-bottom pb-3">
                        <i class="far fa-clock me-1"></i> Deadline: 
                        <span class="fw-bold text-danger">
                            {{ $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->format('d F Y, H:i') : 'Tanpa Batas' }}
                        </span>
                    </p>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold">Instruksi:</h6>
                        <p>{!! nl2br(e($tugas->deskripsi)) !!}</p>
                    </div>

                    @if($tugas->file_path)
                        <div class="alert alert-secondary d-flex align-items-center">
                            <i class="fas fa-paperclip fa-lg me-3"></i>
                            <div>
                                <strong>Lampiran Soal</strong><br>
                                <a href="#" class="alert-link">Download File Soal</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow border-0">
                <div class="card-header bg-white fw-bold">
                    Status Pengumpulan
                </div>
                <div class="card-body">
                    @if($submission)
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%" class="text-muted">Status Pengumpulan</td>
                                    <td><span class="badge bg-success">Terkumpul</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tanggal Kirim</td>
                                    <td>{{ $submission->tanggal_selesai->format('d M Y, H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Nilai</td>
                                    <td>
                                        @if($submission->nilai)
                                            <span class="fs-4 fw-bold text-primary">{{ $submission->nilai }}</span> / 100
                                        @else
                                            <span class="badge bg-warning text-dark">Belum Dinilai</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($submission->komentar_tentor)
                                <tr>
                                    <td class="text-muted">Komentar Tentor</td>
                                    <td><div class="alert alert-info py-2 mb-0">{{ $submission->komentar_tentor }}</div></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i> Anda belum mengumpulkan tugas ini.
                        </div>

                        <form action="{{ route('siswa.tugas.store', $tugas->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="file_jawaban" class="form-label fw-bold">Upload Jawaban (PDF/DOCX)</label>
                                <input class="form-control" type="file" id="file_jawaban" name="file_jawaban" required>
                                <div class="form-text">Maksimal ukuran file 5MB.</div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i> Upload Tugas
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection