@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Admin</h1>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-uppercase mb-1">Total Siswa</div>
                        <div class="h5 mb-0 fw-bold">{{ $stats['total_siswa'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-uppercase mb-1">Total Tentor</div>
                        <div class="h5 mb-0 fw-bold">{{ $stats['total_tentor'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-dark text-uppercase mb-1">Verifikasi Pending</div>
                        <div class="h5 mb-0 fw-bold text-dark">{{ $stats['pending_tentor'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-clock fa-2x text-dark"></i>
                    </div>
                </div>
            </div>
            @if($stats['pending_tentor'] > 0)
            <div class="card-footer bg-warning border-0 p-0">
                <a href="{{ route('admin.verifikasi.index') }}" class="btn btn-sm btn-warning w-100 text-dark fw-bold">Lihat Detail <i class="fas fa-arrow-right"></i></a>
            </div>
            @endif
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-uppercase mb-1">Keluhan Baru</div>
                        <div class="h5 mb-0 fw-bold">{{ $stats['keluhan_pending'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-envelope-open-text fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection