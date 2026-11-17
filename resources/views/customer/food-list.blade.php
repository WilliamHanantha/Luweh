@extends('layouts.app')

@section('title', 'Food List')

@section('back-url', url('/customer-home'))

@section('page-subtitle', $restoran->nama_resto)

@section('page-heading', 'Food List')

@push('styles')
    <style>
        .food-card { display: flex; justify-content: space-between; gap: 12px; background: #eef2e2; border: 1px solid #dfe5c8; border-radius: 32px; padding: 16px 20px; margin-bottom: 16px; box-shadow: 0 2px 6px rgba(0,0,0,.1); }
        .food-info { flex: 1; min-width: 0; display: flex; flex-direction: column; justify-content: space-between; }
        .food-name { font-size: 20px; font-weight: 800; color: #4f6a12; margin: 0 0 4px; }
        .food-desc { font-size: 13px; color: #6b7a3a; margin-bottom: 8px; }
        .food-meta { font-weight: 700; color: #8a9f2e; }
        .food-side { display: flex; flex-direction: column; align-items: flex-end; justify-content: space-between; gap: 10px; flex-shrink: 0; }
        .food-img { width: 72px; height: 72px; max-width: 72px; border-radius: 50%; object-fit: cover; background: #6b8a1f; }

        .food-add { width: 38px; height: 38px; border-radius: 50%; border: 0; background: #ffd62e; color: #4f6a12; font-size: 24px; font-weight: 800; line-height: 1; padding: 0; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 3px 6px rgba(0,0,0,.2); transition: transform .15s ease, box-shadow .15s ease; }
        .food-add:hover { transform: scale(1.1); box-shadow: 0 5px 10px rgba(0,0,0,.25); }
        .food-add:active { transform: scale(.95); }
        .food-add:focus-visible { outline: 3px solid #556b16; outline-offset: 2px; }

        .food-added { background: #556b16; color: #fff; font-size: 20px; cursor: default; animation: food-pop .25s ease; }
        .food-added:hover { transform: none; box-shadow: 0 3px 6px rgba(0,0,0,.2); }
        @keyframes food-pop { 0% { transform: scale(.6); } 70% { transform: scale(1.15); } 100% { transform: scale(1); } }

        .food-buy { display: block; width: 220px; margin: 28px auto 40px; text-align: center; background: #ffd62e; color: #4f6a12; border-radius: 30px; padding: 12px 0; font-weight: 800; letter-spacing: .5px; text-decoration: none; box-shadow: 0 4px 10px rgba(0,0,0,.2); transition: transform .15s ease, box-shadow .15s ease; }
        .food-buy:hover { transform: translateY(-2px); box-shadow: 0 6px 14px rgba(0,0,0,.25); color: #4f6a12; }
        .food-buy:active { transform: translateY(0); }

        .food-add.food-added { cursor: pointer; }
        .food-add.food-added:hover { background: #c0392b; transform: scale(1.1); box-shadow: 0 5px 10px rgba(0,0,0,.25); }
    </style>
@endpush

@section('content')
    @forelse ($makanans as $makanan)
        <div class="food-card">
            <div class="food-info">
                <div>
                    <h4 class="food-name">{{ $makanan->nama_makanan }}</h4>
                    <div class="food-desc">{{ $makanan->deskripsi }}</div>
                </div>
                <div class="food-meta">
                    Rp {{ number_format($makanan->harga, 0, ',', '.') }} &middot; Stok {{ $makanan->stok }}
                </div>
            </div>

            <div class="food-side">
                <img src="{{ asset($makanan->image ?: 'assets/images/food-placeholder.svg') }}"
                     alt="{{ $makanan->nama_makanan }}" class="food-img"
                     onerror="this.onerror=null;this.src='{{ asset('assets/images/food-placeholder.svg') }}';">

                @if (in_array($makanan->id, $dalamKeranjang))
                    <form action="/food-list/{{ $restoran->id }}/makanan/{{ $makanan->id }}/hapus" method="POST">
                        @csrf
                        <button type="submit" class="food-add food-added" title="Hapus dari keranjang" aria-label="Hapus dari keranjang">&#10003;</button>
                    </form>
                @else
                    <form action="/food-list/{{ $restoran->id }}/makanan/{{ $makanan->id }}/tambah" method="POST">
                        @csrf
                        <button type="submit" class="food-add" aria-label="Tambah ke keranjang">+</button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <h5 class="fw-semibold text-secondary">Belum ada makanan</h5>
        </div>
    @endforelse

    <a href="/beli" class="food-buy">Beli</a>
@endsection

@if (session('pesan'))
    @push('scripts')
        <script>alert(@json(session('pesan')));</script>
    @endpush
@endif
