@extends('layouts.app')

@section('title', 'Redeem Points')
@section('role', 'courier')
@section('page-heading', 'Redeem Points')

@section('content')

<div class="redeem-container">

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Available Points --}}
    <div class="points-card">
        <div>
            <p class="mb-1">Your Contribution Points</p>
            <h2>{{ number_format($kontribusi->total_point) }} Points</h2>
        </div>
        <span class="points-icon">★</span>
    </div>

    {{-- Available Rewards --}}
    <h4 class="redeem-section-title">Available Rewards</h4>

    <div class="row g-3">

        @forelse ($produkMitras as $produkMitra)

            @php
                $canRedeem =
                    $kontribusi->total_point >= $produkMitra->poin_dibutuhkan
                    && $produkMitra->stok > 0
                    && $produkMitra->status;
            @endphp

            <div class="col-12 col-md-6">

                <div class="reward-card">

                    @if ($produkMitra->gambar)
                        <img
                            src="{{ asset($produkMitra->gambar) }}"
                            alt="{{ $produkMitra->nama_produk }}"
                            class="reward-image"
                        >
                    @else
                        <div class="reward-image-placeholder">
                            No Product Image
                        </div>
                    @endif

                    <div class="reward-content">

                        <h5>{{ $produkMitra->nama_produk }}</h5>

                        <p class="partner-name">
                            Partner:
                            {{ $produkMitra->restoran->nama_resto ?? 'LUWEH Partner' }}
                        </p>

                        <p class="reward-description">
                            {{ $produkMitra->deskripsi }}
                        </p>

                        <div class="reward-info">
                            <span>Points Required</span>
                            <strong>
                                {{ number_format($produkMitra->poin_dibutuhkan) }}
                                Points
                            </strong>
                        </div>

                        <div class="reward-info">
                            <span>Available Stock</span>
                            <strong>{{ $produkMitra->stok }}</strong>
                        </div>

                        @if ($canRedeem)

                            <a
                                href="{{ route('courier.redeem.confirm', $produkMitra->id) }}"
                                class="btn btn-success w-100 mt-3"
                            >
                                Tukar
                            </a>

                        @else

                            <button
                                class="btn btn-secondary w-100 mt-3"
                                disabled
                            >
                                Tukar
                            </button>

                            <small class="text-danger d-block mt-2">
                                @if ($produkMitra->stok <= 0)
                                    Out of stock
                                @else
                                    Insufficient points
                                @endif
                            </small>

                        @endif

                    </div>
                </div>

            </div>

        @empty

            <div class="col-12">
                <div class="alert alert-info">
                    No partner rewards are currently available.
                </div>
            </div>

        @endforelse

    </div>

</div>

@endsection

@push('styles')
<style>
    .redeem-container {
        padding-bottom: 35px;
    }

    .points-card {
        background: #176b45;
        color: white;
        padding: 25px;
        border-radius: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .points-card h2 {
        font-weight: 700;
        margin: 0;
    }

    .points-icon {
        font-size: 35px;
    }

    .redeem-section-title {
        font-weight: 700;
        margin-bottom: 18px;
    }

    .reward-card {
        background: white;
        border: 1px solid #e9e9e9;
        border-radius: 13px;
        overflow: hidden;
        height: 100%;
    }

    .reward-image,
    .reward-image-placeholder {
        width: 100%;
        height: 170px;
        object-fit: cover;
    }

    .reward-image-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f1f1;
        color: #777;
    }

    .reward-content {
        padding: 18px;
    }

    .reward-content h5 {
        font-weight: 700;
    }

    .partner-name,
    .reward-description {
        font-size: 13px;
        color: #777;
    }

    .reward-info {
        display: flex;
        justify-content: space-between;
        padding: 9px 0;
        border-bottom: 1px solid #eee;
        font-size: 13px;
    }

    .reward-info strong {
        color: #176b45;
    }
</style>
@endpush
