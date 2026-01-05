@extends('layouts.app')

@section('content')
<h3 class="mb-4">Moderasi Feedback & Testimoni</h3>

<div class="card shadow">
    <div class="card-header py-3 bg-white">
        <h6 class="m-0 fw-bold text-primary">Daftar Masuk</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="15%">Pengguna</th>
                        <th width="10%" class="text-center">Rating</th>
                        <th>Komentar</th>
                        <th width="10%">Status</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedbacks as $item)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $item->user->userData->first_name ?? 'User' }}</div>
                            <small class="text-muted">{{ $item->created_at->format('d M Y') }}</small>
                        </td>
                        <td class="text-center">
                            <span class="text-warning fw-bold">
                                {{ $item->rating }} <i class="fas fa-star"></i>
                            </span>
                        </td>
                        <td>
                            <p class="mb-0 small text-secondary">
                                "{{ Str::limit($item->komentar, 100) }}"
                            </p>
                        </td>
                        <td>
                            @if($item->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($item->status == 'approved')
                                <span class="badge bg-success">Tayang</span>
                            @else
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>
                        <td>
                            @if($item->status == 'pending')
                                <form action="{{ route('admin.feedback.approve', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Setujui">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('admin.feedback.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus feedback ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada feedback baru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $feedbacks->links() }}
        </div>
    </div>
</div>
@endsection