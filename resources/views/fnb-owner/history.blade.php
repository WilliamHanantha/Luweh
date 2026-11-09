
@extends('layouts.app')

@section('title', 'History')

@section('page-heading', 'History')

@section('back-url', url('/fnb-owner/order'))

@section('content')

<div class="history-list">

    @forelse ($items as $item)
        @php
            $makanan = $item->daftarMakanan;
            $harga   = $makanan ? $makanan->harga * $item->kuantitas : 0;
            $gambar  = ($makanan && $makanan->gambar) ? $makanan->gambar : 'assets/images/nasi-goreng.png';
        @endphp

        <div class="history-card">

            <img
                src="{{ asset($gambar) }}"
                alt="{{ $makanan->nama_makanan ?? '-' }}"
                class="history-image"
            >

            <div class="history-details">

                <div>
                    <div class="history-number">
                        Order Number #{{ str_pad($item->id, 2, '0', STR_PAD_LEFT) }}
                    </div>

                    <div class="history-menu">
                        {{ $makanan->nama_makanan ?? '-' }}
                    </div>

                    <div class="history-quantity">
                        Quantity: {{ $item->kuantitas }}
                    </div>
                </div>

                <div class="history-price">
                    {{ number_format($harga, 0, ',', '.') }}
                </div>

            </div>

        </div>
    @empty
        <div class="history-empty">
            <p>Belum ada pesanan yang sudah diambil kurir.</p>
        </div>
    @endforelse

</div>

@endsection
