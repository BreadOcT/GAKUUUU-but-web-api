@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Saya</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <span class="text-muted">Halo, {{ Auth::user()->username ?? 'Siswa' }}</span>
    </div>
</div>

<div class="alert alert-primary shadow-sm" role="alert">
    <i class="fas fa-info-circle me-2"></i> Selamat datang di <strong>Gaku!</strong> Semangat belajar hari ini.
</div>

<div class="row">
    @if($enrollments->isEmpty())
        <div class="col-12 text-center py-5">
            <img src="https://img.freepik.com/free-vector/no-data-concept-illustration_114360-536.jpg" alt="Kosong" style="width: 200px; opacity: 0.7;">
            <h5 class="mt-3 text-muted">Anda belum mengambil mata kuliah apapun.</h5>
            <a href="{{ route('siswa.katalog.index') }}" class="btn btn-primary mt-2">
                <i class="fas fa-search me-2"></i> Cari Mata Kuliah
            </a>
        </div>
    @else
        @foreach($enrollments as $enrollment)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                        <span class="badge bg-success float-end">Aktif</span>
                        <h5 class="card-title fw-bold text-primary">{{ $enrollment->kelas->matakuliah->nama_mk }}</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">
                            <i class="fas fa-chalkboard-teacher me-1"></i> 
                            {{ $enrollment->kelas->nama_kelas }}
                        </h6>
                        <p class="card-text small text-secondary">
                            Tentor: {{ $enrollment->kelas->pengampu->userData->first_name ?? 'Tentor' }}
                        </p>
                        
                        <div class="bg-light p-2 rounded small mb-3">
                            <i class="far fa-clock me-1"></i> Jadwal:<br>
                            <strong>
                                @foreach($enrollment->kelas->jadwal as $jadwal)
                                    {{ $jadwal->hari }}, {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}<br>
                                @endforeach
                            </strong>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 pb-3">
                        <a href="{{ route('siswa.kelas.show', $enrollment->kelas->id) }}" class="btn btn-outline-primary w-100">
                            Masuk Kelas <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection