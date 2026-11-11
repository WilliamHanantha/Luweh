
@extends('layouts.app')

@section('title', 'History')

@section('page-heading', 'History')

@section('back-url', url('/fnb-owner/order'))

@section('content')

<div class="history-list">

    @forelse ($pesanans as $pesanan)
        @php
            $customerName = 'Lafuan Igna';
            $orderNumber  = str_pad($pesanan->id, 2, '0', STR_PAD_LEFT);
            $total        = $pesanan->total_harga;
        @endphp

        <div class="history-card">

            {{-- Baris atas: Order # + Customer --}}
            <div class="history-card-header">
                <div class="history-number">Order #{{ $orderNumber }}</div>
                <div class="history-customer">Customer: <strong>{{ $customerName }}</strong></div>
            </div>

            {{-- Baris tengah: daftar makanan (kiri) + total (kanan) --}}
            <div class="history-card-body">

                <ul class="history-items-list">
                    @foreach ($pesanan->itemPesanans as $item)
                        @php $namaMakanan = $item->daftarMakanan->nama_makanan ?? '-'; @endphp
                        <li>{{ $namaMakanan }} : {{ $item->kuantitas }}</li>
                    @endforeach
                </ul>

                <div class="history-total-section">
                    <div class="history-total-label">Total</div>
                    <div class="history-price">Rp {{ number_format($total, 0, ',', '.') }}</div>
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
