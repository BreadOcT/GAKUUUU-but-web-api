@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Riwayat Nilai</h1>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Mata Kuliah</th>
                        <th>Tugas / Kuis</th>
                        <th>Tanggal Submit</th>
                        <th>Status</th>
                        <th class="text-center">Nilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilai as $item)
                    <tr>
                        <td class="ps-4 fw-bold text-secondary">
                            {{ $item->tugas->materi->modul->kelas->matakuliah->nama_mk }}
                        </td>
                        <td>{{ $item->tugas->judul }}</td>
                        <td>{{ $item->tanggal_selesai->format('d M Y') }}</td>
                        <td>
                            @if($item->nilai)
                                <span class="badge bg-success">Dinilai</span>
                            @else
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->nilai)
                                <span class="fw-bold fs-5">{{ $item->nilai }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('siswa.tugas.show', $item->tugas->id) }}" class="btn btn-sm btn-outline-primary">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            Belum ada data nilai. Kerjakan tugas terlebih dahulu.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection