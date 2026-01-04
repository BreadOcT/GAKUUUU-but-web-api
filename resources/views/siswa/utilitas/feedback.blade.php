@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-star me-2"></i> Beri Feedback / Testimoni</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Bagaimana pengalaman belajar Anda di Gaku!? Masukan Anda sangat berarti bagi kami.</p>
                    
                    <form action="{{ route('siswa.feedback.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4 text-center">
                            <label class="form-label fw-bold d-block">Rating Kepuasan</label>
                            <div class="btn-group" role="group" aria-label="Rating">
                                <input type="radio" class="btn-check" name="rating" id="star1" value="1">
                                <label class="btn btn-outline-warning" for="star1">1 <i class="fas fa-star"></i></label>

                                <input type="radio" class="btn-check" name="rating" id="star2" value="2">
                                <label class="btn btn-outline-warning" for="star2">2 <i class="fas fa-star"></i></label>

                                <input type="radio" class="btn-check" name="rating" id="star3" value="3">
                                <label class="btn btn-outline-warning" for="star3">3 <i class="fas fa-star"></i></label>

                                <input type="radio" class="btn-check" name="rating" id="star4" value="4">
                                <label class="btn btn-outline-warning" for="star4">4 <i class="fas fa-star"></i></label>

                                <input type="radio" class="btn-check" name="rating" id="star5" value="5" checked>
                                <label class="btn btn-outline-warning" for="star5">5 <i class="fas fa-star"></i></label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="komentar" class="form-label fw-bold">Komentar / Ulasan</label>
                            <textarea class="form-control" id="komentar" name="komentar" rows="4" placeholder="Ceritakan pengalaman Anda..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Kirim Feedback</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection