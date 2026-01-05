@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('admin.verifikasi.index') }}" class="btn btn-outline-secondary mb-3">&larr; Kembali</a>
    
    <div class="card shadow">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0 fw-bold">Detail Calon Tentor</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 border-end">
                    <h6 class="fw-bold text-uppercase text-muted mb-3">Biodata Diri</h6>
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%">Nama</td>
                            <td>: <strong>{{ $tentor->userData->first_name }} {{ $tentor->userData->last_name }}</strong></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>: {{ $tentor->email }}</td>
                        </tr>
                        <tr>
                            <td>Telepon</td>
                            <td>: {{ $tentor->userData->no_telepon ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>: {{ $tentor->userData->alamat_lengkap ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h6 class="fw-bold text-uppercase text-muted mb-3">Dokumen Pendukung</h6>
                    
                    {{-- Asumsi ada kolom file_cv di user_data, jika tidak ada bisa di skip --}}
                    <div class="alert alert-secondary">
                        <i class="fas fa-file-alt me-2"></i> CV / Resume
                        <a href="#" class="float-end fw-bold text-decoration-none">Lihat File</a>
                    </div>
                    
                    <p class="small text-muted">Pastikan data sesuai dengan kualifikasi pengajar Gaku!.</p>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-center gap-3">
                <form action="{{ route('admin.verifikasi.process', $tentor->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="tolak">
                    <button type="submit" class="btn btn-danger btn-lg px-4" onclick="return confirm('Yakin menolak? Akun ini akan dihapus.')">
                        <i class="fas fa-times me-2"></i> Tolak Pengajuan
                    </button>
                </form>

                <form action="{{ route('admin.verifikasi.process', $tentor->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="terima">
                    <button type="submit" class="btn btn-success btn-lg px-4" onclick="return confirm('Yakin menerima? Akun akan diaktifkan.')">
                        <i class="fas fa-check me-2"></i> Terima & Aktifkan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection