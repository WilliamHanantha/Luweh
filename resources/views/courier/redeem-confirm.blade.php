@extends('layouts.app')

@section('title', 'Confirm Redemption')
@section('role', 'courier')
@section('page-heading', 'Konfirmasi Penukaran')

@section('back-url', route('courier.redeem'))

@section('content')

<div class="confirm-container">

    {{-- SUCCESSFUL REDEMPTION RECEIPT --}}
    @if ($penukaran)

        @php
            $receiptProduct = $penukaran->produkMitra;
        @endphp

        <div class="receipt-card">

            <div class="receipt-success">
                <div class="success-icon">✓</div>
                <h4>Penukaran Berhasil!</h4>
                <p>Your points have been successfully redeemed.</p>
            </div>

            <hr>

            <h5>Struk Konfirmasi</h5>

            <div class="receipt-detail">
                <span>Transaction ID</span>
                <strong>#{{ $penukaran->id }}</strong>
            </div>

            <div class="receipt-detail">
                <span>Courier</span>
                <strong>{{ $courier->name }}</strong>
            </div>

            <div class="receipt-detail">
                <span>Partner</span>
                <strong>
                    {{ $receiptProduct->restoran->nama_resto ?? 'LUWEH Partner' }}
                </strong>
            </div>

            <div class="receipt-detail">
                <span>Product</span>
                <strong>{{ $receiptProduct->nama_produk }}</strong>
            </div>

            <div class="receipt-detail">
                <span>Points Used</span>
                <strong>
                    {{ number_format($penukaran->jumlah_poin_digunakan) }}
                    Points
                </strong>
            </div>

            <div class="receipt-detail">
                <span>Transaction Date</span>
                <strong>
                    {{ $penukaran->created_at->format('d M Y, H:i') }}
                </strong>
            </div>

            <hr>

            <div class="remaining-points">
                <span>Remaining Contribution Points</span>
                <h3>
                    {{ number_format($kontribusi->total_point) }} Points
                </h3>
            </div>

            <a
                href="{{ route('courier.redeem') }}"
                class="btn btn-success w-100 mt-4"
            >
                Back to Redeem Points
            </a>

        </div>

    {{-- REDEMPTION CONFIRMATION FORM --}}
    @else

        <div class="confirm-card">

            <h4>Redemption Summary</h4>

            <p class="text-muted">
                Please review your selected reward before confirming.
            </p>

            <hr>

            <div class="text-center mb-4">

                @if ($produkMitra->gambar)
                    <img
                        src="{{ asset($produkMitra->gambar) }}"
                        alt="{{ $produkMitra->nama_produk }}"
                        class="confirm-product-image"
                    >
                @endif

                <h5 class="fw-bold">
                    {{ $produkMitra->nama_produk }}
                </h5>

                <p class="text-muted">
                    Partner:
                    {{ $produkMitra->restoran->nama_resto ?? 'LUWEH Partner' }}
                </p>

                <p>{{ $produkMitra->deskripsi }}</p>

            </div>

            <hr>

            <div class="confirm-detail">
                <span>Available Points</span>
                <strong>
                    {{ number_format($kontribusi->total_point) }}
                </strong>
            </div>

            <div class="confirm-detail">
                <span>Points Required</span>
                <strong>
                    {{ number_format($produkMitra->poin_dibutuhkan) }}
                </strong>
            </div>

            <div class="confirm-detail">
                <span>Remaining Points After Redemption</span>
                <strong>
                    {{ number_format(
                        max(0, $kontribusi->total_point -
                        $produkMitra->poin_dibutuhkan)
                    ) }}
                </strong>
            </div>

            <hr>

            @if ($canRedeem)

                <div class="alert alert-info">
                    Are you sure you want to redeem this product?
                    The required contribution points will be deducted
                    from your balance.
                </div>

                <form
                    action="{{ route('courier.redeem.store', $produkMitra->id) }}"
                    method="POST"
                >
                    @csrf

                    <button
                        type="submit"
                        class="btn btn-success w-100"
                    >
                        Konfirmasi Penukaran
                    </button>
                </form>

            @else

                <div class="alert alert-warning">
                    You cannot redeem this product because your points
                    are insufficient or the product is unavailable.
                </div>

            @endif

            <a
                href="{{ route('courier.redeem') }}"
                class="btn btn-outline-secondary w-100 mt-3"
            >
                Cancel
            </a>

        </div>

    @endif

</div>

@endsection

@push('styles')
<style>
    .confirm-container {
        max-width: 600px;
        margin: auto;
        padding-bottom: 40px;
    }

    .confirm-card,
    .receipt-card {
        background: white;
        border: 1px solid #eee;
        border-radius: 14px;
        padding: 25px;
    }

    .confirm-product-image {
        width: 180px;
        height: 150px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 15px;
    }

    .confirm-detail,
    .receipt-detail {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding: 12px 0;
        border-bottom: 1px solid #eee;
        font-size: 14px;
    }

    .confirm-detail strong,
    .receipt-detail strong {
        text-align: right;
        color: #176b45;
    }

    .receipt-success {
        text-align: center;
        padding: 15px 0;
    }

    .success-icon {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background: #176b45;
        color: white;
        font-size: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
    }

    .receipt-success h4 {
        font-weight: 700;
        color: #176b45;
    }

    .receipt-success p {
        color: #777;
        font-size: 14px;
    }

    .remaining-points {
        text-align: center;
        padding: 15px 0;
    }

    .remaining-points h3 {
        color: #176b45;
        font-weight: 700;
        margin-top: 8px;
    }
</style>
@endpush
