@extends('layouts.app')

@section('title', 'Set Menu Quantity')

@section('page-heading', 'Set Menu Quantity')

@section('content')
@php
    $makanan = $makanan ?? \App\Models\DaftarMakanan::first();

    $imageSrc = asset('assets/images/food-placeholder.svg');
    if (!empty($makanan->image)) {
        if (Illuminate\Support\Str::startsWith($makanan->image, ['http://', 'https://'])) {
            $imageSrc = $makanan->image;
        } elseif (file_exists(public_path($makanan->image))) {
            $imageSrc = asset($makanan->image);
        } elseif (file_exists(public_path('storage/' . $makanan->image))) {
            $imageSrc = asset('storage/' . $makanan->image);
        } else {
            $imageSrc = asset($makanan->image);
        }
    }
@endphp

<div class="set-quantity-wrapper">
    {{-- Card Menu Item --}}
    <div class="menu-card d-flex align-items-center">
        {{-- Foto Makanan --}}
        <div class="menu-img-wrapper flex-shrink-0">
            <img src="{{ $imageSrc }}"
                 alt="{{ $makanan->nama_makanan }}"
                 class="menu-img"
                 onerror="this.onerror=null;this.src='{{ asset('assets/images/food-placeholder.svg') }}';">
        </div>

        {{-- Detail Makanan --}}
        <div class="menu-info flex-grow-1 ms-3">
            <h4 class="menu-name">{{ $makanan->nama_makanan }}</h4>
            <p class="menu-weight mb-2">
                {{ $makanan->deskripsi ?? 'Weight (per item): -' }}
            </p>

            <div class="d-flex justify-content-between align-items-center">
                <span class="menu-price">
                    {{ number_format($makanan->harga, 0, ',', '.') }}
                </span>

                {{-- Counter Quantity --}}
                <div class="quantity-control d-flex align-items-center">
                    <button type="button" class="btn-qty" id="btnMinus" aria-label="Decrease quantity">
                        <svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor">
                            <rect y="7" width="16" height="2" rx="1" fill="#7ea32e" />
                        </svg>
                    </button>

                    <span class="qty-number" id="qtyText">1</span>

                    <button type="button" class="btn-qty" id="btnPlus" aria-label="Increase quantity">
                        <svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor">
                            <rect y="7" width="16" height="2" rx="1" fill="#7ea32e" />
                            <rect x="7" width="2" height="16" rx="1" fill="#7ea32e" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Confirm ke Database --}}
    <form id="confirmQuantityForm" action="{{ url('/fnb-owner/' . $makanan->id . '/confirm-quantity') }}" method="POST" class="mt-auto pt-5 text-center">
        @csrf
        <input type="hidden" name="stok" id="stokInput" value="1">

        <button type="submit" class="btn-confirm">
            Confirm
        </button>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnMinus = document.getElementById('btnMinus');
        const btnPlus = document.getElementById('btnPlus');
        const qtyText = document.getElementById('qtyText');
        const stokInput = document.getElementById('stokInput');

        let currentQty = parseInt(stokInput.value) || 1;

        function updateDisplay() {
            qtyText.textContent = currentQty;
            stokInput.value = currentQty;
            btnMinus.style.opacity = currentQty <= 1 ? '0.4' : '1';
            btnMinus.style.cursor = currentQty <= 1 ? 'not-allowed' : 'pointer';
        }

        btnMinus.addEventListener('click', function () {
            if (currentQty > 1) {
                currentQty--;
                updateDisplay();
            }
        });

        btnPlus.addEventListener('click', function () {
            currentQty++;
            updateDisplay();
        });

        updateDisplay();
    });
</script>
@endpush

@endsection
