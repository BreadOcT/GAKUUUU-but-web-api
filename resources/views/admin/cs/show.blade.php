@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('admin.cs.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Kembali ke Inbox
    </a>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Pesan dari: {{ $keluhan->user->userData->first_name }}</span>
                    <span class="text-muted small">{{ $keluhan->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold text-secondary mb-3">Isi Keluhan:</h6>
                    <div class="p-3 bg-light rounded border mb-4">
                        {!! nl2br(e($keluhan->deskripsi_keluhan)) !!}
                    </div>

                    @if($keluhan->bukti_keluhan)
                        <h6 class="fw-bold text-secondary mb-2">Bukti Lampiran:</h6>
                        <div class="card mb-3" style="max-width: 300px;">
                            <a href="{{ asset('storage/' . $keluhan->bukti_keluhan) }}" target="_blank">
                                <img src="{{ asset('storage/' . $keluhan->bukti_keluhan) }}" class="card-img-top" alt="Bukti">
                            </a>
                            <div class="card-footer p-2 text-center small bg-white">
                                <a href="{{ asset('storage/' . $keluhan->bukti_keluhan) }}" target="_blank" class="text-decoration-none">
                                    <i class="fas fa-expand me-1"></i> Perbesar Gambar
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info py-2 small">
                            <i class="fas fa-info-circle me-1"></i> Tidak ada lampiran bukti.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card shadow border-primary h-100">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-reply me-2"></i> Tindak Lanjut Admin
                </div>
                <div class="card-body">
                    @if($keluhan->balasan_admin)
                        <div class="alert alert-success">
                            <strong><i class="fas fa-check-circle"></i> Sudah Dibalas</strong><br>
                            <small class="text-muted">Pada: {{ $keluhan->updated_at->format('d M Y, H:i') }}</small>
                            <hr>
                            <p class="mb-0 fst-italic">"{{ $keluhan->balasan_admin }}"</p>
                        </div>
                        <button class="btn btn-secondary w-100" disabled>Tiket Selesai</button>
                    @else
                        <form action="{{ route('admin.cs.reply', $keluhan->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tulis Balasan:</label>
                                <textarea name="balasan_admin" class="form-control" rows="6" placeholder="Halo {{ $keluhan->user->userData->first_name }}, mohon maaf atas ketidaknyamanan..." required></textarea>
                                <div class="form-text">Balasan ini akan muncul di dashboard siswa.</div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold">
                                <i class="fas fa-paper-plane me-2"></i> Kirim Balasan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection