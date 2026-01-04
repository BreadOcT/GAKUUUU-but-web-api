@extends('layouts.app')

@section('content')
<div class="container py-3">
    <h3 class="mb-4">Pengaturan Profil</h3>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <div class="mb-3">
                        <img src="{{ $user->userData->foto_profil ? asset('storage/'.$user->userData->foto_profil) : 'https://via.placeholder.com/150' }}" 
                             class="rounded-circle img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                    <h5 class="fw-bold">{{ $user->userData->first_name }} {{ $user->userData->last_name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                    <span class="badge bg-primary">Siswa Active</span>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">Edit Biodata</div>
                <div class="card-body">
                    <form action="{{ route('siswa.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Depan</label>
                                <input type="text" class="form-control" name="first_name" value="{{ $user->userData->first_name }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Belakang</label>
                                <input type="text" class="form-control" name="last_name" value="{{ $user->userData->last_name }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" name="no_telepon" value="{{ $user->userData->no_telepon }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" name="alamat_lengkap" rows="3">{{ $user->userData->alamat_lengkap }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ganti Foto Profil</label>
                            <input type="file" class="form-control" name="foto_profil">
                            <div class="form-text">Format JPG/PNG, max 2MB.</div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection