@extends('layouts.app')

@section('title', 'Order Now')

@section('back-url', $kembaliUrl)

@section('page-heading', 'Order Now')

@push('styles')
    <style>
        .order-resto { color: #6b8a1f; font-weight: 700; margin: 8px 0 16px; }
        .order-card { display: flex; justify-content: space-between; gap: 12px; background: #eef2e2; border: 1px solid #dfe5c8; border-radius: 32px; padding: 18px 22px; margin-bottom: 18px; box-shadow: 0 2px 6px rgba(0,0,0,.1); }
        .order-info { flex: 1; display: flex; flex-direction: column; justify-content: space-between; min-width: 0; }
        .order-name { font-size: 22px; font-weight: 800; color: #4f6a12; margin: 0 0 6px; padding-bottom: 8px; border-bottom: 2px solid #c9d49a; }
        .order-stock { font-weight: 700; color: #4f6a12; margin-bottom: 14px; }
        .order-amount { font-weight: 700; color: #8a9f2e; font-size: 18px; }
        .order-side { display: flex; flex-direction: column; align-items: flex-end; justify-content: space-between; gap: 12px; flex-shrink: 0; }
        .order-img { width: 72px; height: 72px; max-width: 72px; border-radius: 50%; object-fit: cover; background: #6b8a1f; }
        .qty { display: flex; align-items: center; gap: 8px; }
        .qty form { margin: 0; }
        .qty-btn { width: 28px; height: 28px; border-radius: 50%; border: 0; background: #8a9f2e; color: #fff; font-size: 18px; font-weight: 700; line-height: 1; cursor: pointer; padding: 0; }
        .qty-num { min-width: 28px; height: 28px; border: 1px solid #8a9f2e; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; color: #4f6a12; }
        .order-actions { display: flex; flex-direction: column; align-items: center; gap: 12px; margin: 28px 0 40px; }
        .btn-pill-green { width: 190px; background: #556b16; color: #fff; border: 0; border-radius: 30px; padding: 10px 0; font-weight: 700; box-shadow: 0 3px 6px rgba(0,0,0,.2); }
    </style>
@endpush

@section('content')
    @if ($grup->isNotEmpty())
        @foreach ($grup as $g)
            <div class="order-resto">{{ $g['nama'] }}</div>

            @foreach ($g['items'] as $item)
                <div class="order-card">
                    <div class="order-info">
                        <div>
                            <h4 class="order-name">{{ $item->daftarMakanan->nama_makanan }}</h4>
                            <div class="order-stock">Stock: {{ $item->daftarMakanan->stok }}</div>
                        </div>
                        <div class="order-amount">Amount</div>
                    </div>

                    <div class="order-side">
                        <img src="{{ asset($item->daftarMakanan->image ?: 'assets/images/food-placeholder.svg') }}"
                             alt="{{ $item->daftarMakanan->nama_makanan }}" class="order-img" width="72" height="72"
                             onerror="this.onerror=null;this.src='{{ asset('assets/images/food-placeholder.svg') }}';">

                        <div class="qty">
                            <form action="/order-now/item/{{ $item->id }}/kurang" method="POST">
                                @csrf
                                <button type="submit" class="qty-btn" aria-label="Kurangi">&minus;</button>
                            </form>

                            <span class="qty-num">{{ $item->kuantitas }}</span>

                            <form action="/order-now/item/{{ $item->id }}/tambah" method="POST">
                                @csrf
                                <button type="submit" class="qty-btn" aria-label="Tambah">+</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach

        {{-- Tampilan saja: checkout (UC03) belum dikerjakan --}}
        <div class="order-actions">
            <button type="button" class="btn-pill-green">Pick-Up</button>
            <button type="button" class="btn-pill-green">Delivery</button>
        </div>
    @else
        <div class="text-center py-5">
            <h5 class="fw-semibold text-secondary">Keranjang masih kosong</h5>
            <a href="/customer-home" class="btn btn-warning rounded-pill px-4 mt-3">Kembali ke Customer Home</a>
        </div>
    @endif
@endsection
