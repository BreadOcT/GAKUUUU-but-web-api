@extends('layouts.app')

@section('content')
<h3 class="mb-4">Layanan Pelanggan (CS)</h3>

<div class="card shadow">
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @forelse($keluhan as $item)
                <a href="{{ route('admin.cs.show', $item->id) }}" class="list-group-item list-group-item-action p-4">
                    <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                        <h6 class="mb-1 fw-bold text-primary">
                            <i class="fas fa-envelope me-2"></i> {{ $item->user->userData->first_name ?? 'User' }}
                            <small class="text-muted fw-normal">({{ $item->user->email }})</small>
                        </h6>
                        <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
                    </div>
                    
                    <p class="mb-2 text-dark">{{ Str::limit($item->deskripsi_keluhan, 120) }}</p>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @if($item->status == 'pending')
                                <span class="badge bg-danger">Butuh Balasan</span>
                            @elseif($item->status == 'diproses')
                                <span class="badge bg-warning text-dark">Sedang Dilihat</span>
                            @else
                                <span class="badge bg-success">Selesai</span>
                            @endif

                            @if($item->bukti_keluhan)
                                <span class="badge bg-light text-dark border ms-2"><i class="fas fa-paperclip"></i> Ada Lampiran</span>
                            @endif
                        </div>
                        <span class="btn btn-sm btn-outline-primary">Buka Pesan &rarr;</span>
                    </div>
                </a>
            @empty
                <div class="text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076478.png" width="100" class="mb-3 opacity-50">
                    <p class="text-muted">Tidak ada pesan keluhan baru.</p>
                </div>
            @endforelse
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $keluhan->links() }}
    </div>
</div>
@endsection