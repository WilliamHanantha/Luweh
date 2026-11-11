@extends('layouts.app')

@section('title', 'History')
@section('role', 'courier')
@section('page-heading', 'History')
@section('back-url', route('courier.activity'))

@section('content')

<div class="foodhero-list">
    @forelse ($pesanans as $pesanan)
        @php
            $resto = $pesanan->restoran;
            $namaResto = $resto->nama_resto ?? 'Restoran #' . ($pesanan->restoran_id ?? $pesanan->id);
            $pickupLoc = $resto->alamat ?? 'Lokasi Restoran Belum Diisi';
            $receiverName = $pesanan->nama_penerima ?? 'Penerima';
            $deliveryLoc = $pesanan->alamat_pengiriman ?? 'Alamat Pengiriman Belum Diisi';
            $imageSrc = ($resto && $resto->gambar) ? $resto->gambar : 'assets/images/nasi-goreng.png';
            $questNumber = str_pad($pesanan->id, 2, '0', STR_PAD_LEFT);
        @endphp

        <div class="foodhero-card" id="history-card-{{ $pesanan->id }}">
            {{-- Top: image + meta (tanpa badge status) --}}
            <div class="foodhero-card-top">
                <img src="{{ asset($imageSrc) }}" alt="{{ $namaResto }}" class="foodhero-img">
                <div class="foodhero-meta">
                    <div class="foodhero-quest-number">Quest Number #{{ $questNumber }}</div>
                    <div class="foodhero-name">{{ $namaResto }}</div>
                </div>
            </div>

            {{-- Details: hanya detail pesanan saja (tanpa tombol delivered) --}}
            <div class="foodhero-details pb-3">
                <div class="foodhero-detail-row">
                    <div class="foodhero-detail-label">Pick-up Location</div>
                    <div class="foodhero-detail-value">{{ $pickupLoc }}</div>
                </div>
                <div class="foodhero-detail-row">
                    <div class="foodhero-detail-label">Receiver's Name</div>
                    <div class="foodhero-detail-value">{{ $receiverName }}</div>
                </div>
                <div class="foodhero-detail-row">
                    <div class="foodhero-detail-label">Delivery Location</div>
                    <div class="foodhero-detail-value">{{ $deliveryLoc }}</div>
                </div>
            </div>
        </div>
    @empty
        <div class="foodhero-empty-state">
            <h5>Belum ada riwayat pesanan</h5>
            <p class="text-muted">Pesanan yang telah selesai diantar akan muncul di sini.</p>
            <a href="{{ route('courier.activity') }}" class="btn btn-sm btn-outline-success">Kembali ke Activity</a>
        </div>
    @endforelse
</div>

@endsection
