@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Pilih Jadwal Belajar</h5>
                </div>
                <div class="card-body">
                    <h5 class="card-title fw-bold">{{ $matakuliah->nama_mk }}</h5>
                    <p class="text-muted mb-4">Silakan pilih kelas dan jadwal yang tersedia untuk mata kuliah ini.</p>

                    @if($matakuliah->kelas->isEmpty())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle me-1"></i> 
                            Belum ada jadwal kelas yang dibuka untuk saat ini.
                        </div>
                        <a href="{{ route('siswa.katalog.show', $matakuliah->id) }}" class="btn btn-secondary">Kembali</a>
                    @else
                        <form action="{{ url('/api/enrollment') }}" method="POST"> 
                            @csrf
                            <input type="hidden" name="matakuliah_id" value="{{ $matakuliah->id }}">
                            
                            <div class="list-group mb-4">
                                @foreach($matakuliah->kelas as $kelas)
                                <label class="list-group-item list-group-item-action d-flex align-items-start gap-3">
                                    <input class="form-check-input flex-shrink-0 mt-2" type="radio" name="kelas_id" value="{{ $kelas->id }}" required>
                                    <div class="w-100">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1 fw-bold">{{ $kelas->nama_kelas }}</h6>
                                            <small class="text-muted">Tentor: {{ $kelas->pengampu->userData->first_name ?? 'Staf' }}</small>
                                        </div>
                                        <small class="text-primary mb-2 d-block">
                                            @foreach($kelas->jadwal as $jadwal)
                                                <i class="far fa-clock"></i> {{ $jadwal->hari }}, {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}<br>
                                            @endforeach
                                        </small>
                                        <p class="mb-1 small text-muted">{{ $kelas->deskripsi }}</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-check-circle me-2"></i> Konfirmasi Pendaftaran
                                </button>
                                <a href="{{ route('siswa.katalog.show', $matakuliah->id) }}" class="btn btn-light text-muted">Batal</a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection