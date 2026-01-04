@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-headset me-2"></i> Hubungi Customer Service</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Mengalami kendala teknis atau masalah pembayaran? Laporkan di sini.</p>
                    
                    <form action="{{ route('siswa.kontak-cs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="deskripsi_keluhan" class="form-label fw-bold">Deskripsi Masalah</label>
                            <textarea class="form-control" id="deskripsi_keluhan" name="deskripsi_keluhan" rows="5" placeholder="Jelaskan masalah Anda secara detail..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="bukti_keluhan" class="form-label fw-bold">Bukti (Screenshot/Foto)</label>
                            <input class="form-control" type="file" id="bukti_keluhan" name="bukti_keluhan">
                            <div class="form-text">Opsional. Membantu kami memahami masalah lebih cepat.</div>
                        </div>

                        <button type="submit" class="btn btn-info text-white w-100 fw-bold">Kirim Laporan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection