@extends('layouts.app')

@section('content')
<h3 class="mb-4">Manajemen Pengguna</h3>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 fw-bold text-primary">Daftar Akun (Siswa & Tentor)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                <thead class="table-dark">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->userData->first_name ?? '-' }} {{ $user->userData->last_name ?? '' }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge {{ $user->role == 'tentor' ? 'bg-info' : 'bg-secondary' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            @if($user->aktif)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif/Pending</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini selamanya?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection