@extends('layouts.app')

@section('title', 'Customer Home')

@section('page-subtitle', 'Hello!')

@section('page-heading', 'Save the Food, now!')

@push('styles')
    <style>
        .resto-search { background: #fff; border-radius: 40px; padding: 6px 16px; box-shadow: 0 2px 8px rgba(0,0,0,.12); }
        .resto-title { color: #556b16; }
        .resto-viewall { color: #8a9f2e; font-weight: 600; font-size: 14px; }
        .resto-card { background: #fff; border: 1px solid #dfe5c8; border-radius: 24px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,.08); }
        .resto-img { height: 140px; background: #e9eed4; }
        .resto-img img { width: 100%; height: 100%; object-fit: cover; }
        .resto-body { padding: 10px 16px; }
        .resto-name { font-weight: 700; color: #556b16; }
        .resto-dist { font-size: 13px; color: #8a9f2e; }
        .resto-rating { font-size: 13px; font-weight: 700; color: #8a9f2e; }
    </style>
@endpush

@section('content')
    {{-- Search bar: tampilan saja, belum berfungsi --}}
    <div class="resto-search mb-4">
        <input type="text" class="form-control border-0 shadow-none" placeholder="Search food here" disabled>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-5">
        <h5 class="resto-title fw-bold mb-0">Recommendation</h5>
        <a href="#" class="resto-viewall text-decoration-none">View All</a>
    </div>

    @forelse ($restorans as $resto)
        <a href="#" class="resto-card d-block text-decoration-none mb-3">
            <div class="resto-img">
                <img src="{{ asset('assets/images/food-placeholder.svg') }}" alt="{{ $resto->nama_resto }}">
            </div>
            <div class="resto-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="resto-name">{{ $resto->nama_resto }}</div>
                    <div class="resto-dist">{{ number_format($resto->jarak, 1) }} km</div>
                </div>
                <div class="resto-rating">{{ number_format($resto->rating, 1) }} &#9733;</div>
            </div>
        </a>
    @empty
        <div class="text-center py-5">
            <h5 class="fw-semibold text-secondary">Belum ada restoran</h5>
        </div>
    @endforelse

    <div class="text-center mt-4 mb-5">
        <a href="/order-now" class="btn btn-warning rounded-pill px-4">Lihat Keranjang</a>
    </div>
@endsection
