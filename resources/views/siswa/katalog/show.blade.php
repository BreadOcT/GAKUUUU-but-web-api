@extends('layouts.app')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('siswa.katalog.index') }}">Katalog</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $matakuliah->nama_mk }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="fw-bold text-primary mb-3">{{ $matakuliah->nama_mk }}</h2>
                    <h5 class="fw-bold mt-4">Deskripsi</h5>
                    <p class="text-secondary">{{ $matakuliah->deskripsi }}</p>
                    
                    <h5 class="fw-bold mt-4">Manfaat Belajar</h5>
                    <p class="text-secondary">{{ $matakuliah->manfaat ?? 'Tidak disebutkan.' }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i> Pilih Jadwal Kelas</h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted">Silakan pilih kelas yang tersedia sesuai jadwal Anda:</p>
                    
                    @if($matakuliah->kelas->isEmpty())
                        <div class="alert alert-warning small">Belum ada kelas dibuka untuk mata kuliah ini.</div>
                    @else
                       <form action="{{ route('siswa.enrollment.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="matakuliah_id" value="{{ $matakuliah->id }}">
                            
                            <div class="list-group mb-3">
                                @foreach($matakuliah->kelas as $kelas)
                                <label class="list-group-item d-flex gap-2">
                                    <input class="form-check-input flex-shrink-0" type="radio" name="kelas_id" value="{{ $kelas->id }}" required>
                                    <span>
                                        <strong class="d-block">{{ $kelas->nama_kelas }}</strong>
                                        <small class="text-muted">
                                            @foreach($kelas->jadwal as $jadwal)
                                                {{ $jadwal->hari }}, {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}<br>
                                            @endforeach
                                        </small>
                                    </span>
                                </label>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-success w-100 fw-bold py-2">
                                Daftar Sekarang
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection