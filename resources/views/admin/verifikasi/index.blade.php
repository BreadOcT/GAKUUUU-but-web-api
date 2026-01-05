@extends('layouts.app')

@section('content')
<h3 class="mb-4">Verifikasi Tentor Baru</h3>

@if($tentors->isEmpty())
    <div class="alert alert-info">Tidak ada pengajuan tentor baru yang perlu diverifikasi.</div>
@else
    <div class="row">
        @foreach($tentors as $tentor)
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-left-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold">{{ $tentor->userData->first_name }} {{ $tentor->userData->last_name }}</h5>
                            <p class="mb-1 text-muted">{{ $tentor->email }}</p>
                            <small class="text-warning"><i class="fas fa-clock"></i> Menunggu Verifikasi</small>
                        </div>
                        <a href="{{ route('admin.verifikasi.show', $tentor->id) }}" class="btn btn-primary">
                            Periksa Dokumen
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection