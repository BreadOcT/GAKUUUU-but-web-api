@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Data Pengguna</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Depan</label>
                        <input type="text" name="first_name" class="form-control" value="{{ $user->userData->first_name ?? '' }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Belakang</label>
                        <input type="text" name="last_name" class="form-control" value="{{ $user->userData->last_name ?? '' }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Login</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" name="no_telepon" class="form-control" value="{{ $user->userData->no_telepon ?? '' }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Status Akun</label>
                    <select name="status_akun" class="form-select">
                        <option value="1" {{ $user->aktif ? 'selected' : '' }}>Aktif (Bisa Login)</option>
                        <option value="0" {{ !$user->aktif ? 'selected' : '' }}>Nonaktif / Banned</option>
                    </select>
                    <div class="form-text text-danger">Jika dinonaktifkan, user tidak akan bisa login.</div>
                </div>

                <hr>
                <h6 class="text-muted">Ubah Password (Opsional)</h6>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Isi hanya jika ingin mereset password user">
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection