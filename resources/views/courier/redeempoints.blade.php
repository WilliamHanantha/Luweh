@extends('layouts.app')

@section('title', 'Redeem Points')

@section('role', 'courier')

@section('page-subtitle', 'Your Contribution Rewards')

@section('page-heading', 'Redeem Points')

@section('back-url', route('courier.home'))

@section('content')

<div class="redeem-container">

    {{-- SUCCESS MESSAGE --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            {{ session('success') }}
            <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    {{-- CONTRIBUTION POINTS --}}
    <div class="redeem-balance-card">
        <div class="redeem-balance-info">
            <div class="redeem-balance-label">
                Your Contribution Points
            </div>

            <div class="redeem-balance-value">
                {{ number_format($kontribusi->total_point) }}
                <span>Points</span>
            </div>

            <div class="redeem-balance-owner">
                Welcome, {{ $courier->name }}!
            </div>
        </div>
    </div>

    {{-- AVAILABLE REWARDS --}}
    <div class="redeem-section-header">
        <h4>Available Rewards</h4>
        <p>Exchange your contribution points for partner products.</p>
    </div>

    <div class="row g-4">

        @forelse ($produkMitras as $produkMitra)

            @php
                $canRedeem =
                    $kontribusi->total_point >= $produkMitra->poin_dibutuhkan
                    && $produkMitra->stok > 0;
            @endphp

            <div class="col-12 col-md-6 col-lg-4">

                <div class="redeem-product-card">

                    {{-- PRODUCT IMAGE --}}
                    <div class="redeem-product-image">

                        @if ($produkMitra->gambar)
                            <img
                                src="{{ asset($produkMitra->gambar) }}"
                                alt="{{ $produkMitra->nama_produk }}"
                            >
                        @else
                            <div class="redeem-image-placeholder">
                                LUWEH REWARD
                            </div>
                        @endif

                    </div>

                    {{-- PRODUCT DETAILS --}}
                    <div class="redeem-product-body">

                        <h5>
                            {{ $produkMitra->nama_produk }}
                        </h5>

                        <p class="redeem-partner">
                            Partner:
                            {{ $produkMitra->restoran->nama_resto ?? 'LUWEH Partner' }}
                        </p>

                        <p class="redeem-description">
                            {{ $produkMitra->deskripsi }}
                        </p>

                        <div class="redeem-product-points">
                            <span>Points Required</span>

                            <strong>
                                {{ number_format($produkMitra->poin_dibutuhkan) }}
                                Points
                            </strong>
                        </div>

                        <div class="redeem-product-stock">
                            Stock: {{ $produkMitra->stok }}
                        </div>

                        {{-- REDEMPTION BUTTON --}}
                        @if ($canRedeem)

                            <a
                                href="{{ route('courier.redeem.confirm', $produkMitra->id) }}"
                                class="btn redeem-btn w-100"
                            >
                                Tukar
                            </a>

                        @else

                            <button
                                type="button"
                                class="btn redeem-btn-disabled w-100"
                                disabled
                            >
                                Tukar
                            </button>

                            <small class="redeem-unavailable">
                                @if ($produkMitra->stok <= 0)
                                    Stok produk habis.
                                @else
                                    Poin kontribusi tidak mencukupi.
                                @endif
                            </small>

                        @endif

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">
                <div class="redeem-empty-state">
                    <h5>No rewards available</h5>
                    <p>
                        There are currently no partner products
                        available for redemption.
                    </p>
                </div>
            </div>

        @endforelse

    </div>

</div>

@endsection

@push('styles')

<style>

    .redeem-container {
        max-width: 1100px;
        margin: 0 auto;
        padding-bottom: 40px;
    }

    /* Contribution Balance */

    .redeem-balance-card {
        background: linear-gradient(135deg, #176b45, #279966);
        color: white;
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 35px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .redeem-balance-label {
        font-size: 15px;
        font-weight: 500;
        opacity: 0.9;
    }

    .redeem-balance-value {
        font-size: 34px;
        font-weight: 700;
        margin: 8px 0;
    }

    .redeem-balance-value span {
        font-size: 17px;
        font-weight: 500;
    }

    .redeem-balance-owner {
        font-size: 13px;
        opacity: 0.85;
    }

    /* Section Header */

    .redeem-section-header {
        margin-bottom: 22px;
    }

    .redeem-section-header h4 {
        font-weight: 700;
        margin-bottom: 5px;
    }

    .redeem-section-header p {
        color: #777;
        font-size: 14px;
    }

    /* Product Cards */

    .redeem-product-card {
        height: 100%;
        background: white;
        border: 1px solid #e8e8e8;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(0,0,0,0.04);
        transition: transform 0.2s ease,
                    box-shadow 0.2s ease;
    }

    .redeem-product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.08);
    }

    .redeem-product-image {
        height: 175px;
        background: #f3f6f4;
        overflow: hidden;
    }

    .redeem-product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .redeem-image-placeholder {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #176b45;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .redeem-product-body {
        padding: 20px;
    }

    .redeem-product-body h5 {
        font-weight: 700;
        margin-bottom: 8px;
    }

    .redeem-partner {
        font-size: 13px;
        color: #176b45;
        margin-bottom: 10px;
    }

    .redeem-description {
        font-size: 13px;
        color: #777;
        min-height: 38px;
    }

    .redeem-product-points {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-top: 1px solid #eee;
        font-size: 13px;
    }

    .redeem-product-points strong {
        color: #176b45;
    }

    .redeem-product-stock {
        font-size: 12px;
        color: #777;
        margin-bottom: 14px;
    }

    /* Buttons */

    .redeem-btn {
        background: #176b45;
        color: white;
        border-radius: 8px;
        padding: 10px;
        font-weight: 600;
    }

    .redeem-btn:hover {
        background: #125638;
        color: white;
    }

    .redeem-btn-disabled {
        background: #d8d8d8;
        color: #777;
        border-radius: 8px;
        padding: 10px;
        cursor: not-allowed;
    }

    .redeem-unavailable {
        display: block;
        text-align: center;
        color: #dc3545;
        margin-top: 8px;
    }

    /* Empty State */

    .redeem-empty-state {
        text-align: center;
        padding: 45px 20px;
        background: #f8f9fa;
        border-radius: 12px;
    }

</style>

@endpush
