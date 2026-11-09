@extends('layouts.app')

@section('title', 'Food Hero')
@section('role', 'courier')
@section('page-heading', 'Food Hero')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="foodhero-list">
    @forelse ($pesanans as $pesanan)
        @php
            $resto = $pesanan->restoran;
            $namaResto = $resto->nama_resto ?? 'Restoran #' . ($pesanan->restoran_id ?? $pesanan->id);
            $pickupLoc = $resto->alamat ?? 'Lokasi Restoran Belum Diisi';
            $receiverName = $pesanan->nama_penerima ?? 'Penerima';
            $deliveryLoc = $pesanan->alamat_pengiriman ?? 'Alamat Pengiriman Belum Diisi';
            $imageSrc = $pesanan->gambar_makanan ?: ($resto->gambar ?? 'assets/images/nasi-goreng.png');
            $status = $pesanan->status_courier ?? $pesanan->status ?? 'on-going';
            $statusClass = 'status-' . strtolower(str_replace(' ', '-', $status));
            $questNumber = str_pad($pesanan->id, 2, '0', STR_PAD_LEFT);
        @endphp

        <div class="foodhero-card" id="foodhero-card-{{ $pesanan->id }}">
            {{-- Top: image + meta + badge --}}
            <div class="foodhero-card-top">
                <img src="{{ asset($imageSrc) }}" alt="{{ $namaResto }}" class="foodhero-img">
                <div class="foodhero-meta">
                    <div class="foodhero-quest-number">Quest Number #{{ $questNumber }}</div>
                    <div class="foodhero-name">{{ $namaResto }}</div>
                </div>
                <span class="foodhero-badge {{ $statusClass }}">{{ $status }}</span>
            </div>

            {{-- Details --}}
            <div class="foodhero-details">
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

            {{-- Action Button: Pick this order --}}
            <button
                type="button"
                class="foodhero-pick-btn"
                onclick="openPickModal({{ $pesanan->id }}, '{{ addslashes($namaResto) }}', '{{ addslashes($receiverName) }}')"
            >
                Pick this order
            </button>
        </div>
    @empty
        <div class="foodhero-empty-state">
            <h5>Tidak ada quest pesanan saat ini</h5>
            <p class="text-muted">Semua pesanan on-going telah diambil atau belum tersedia.</p>
            <a href="{{ route('courier.activity') }}" class="btn btn-sm btn-outline-success">Lihat Halaman Activity</a>
        </div>
    @endforelse
</div>

{{-- ===== MODAL KONFIRMASI PENGANTARAN (FOOD HERO) ===== --}}
<div id="pickup-modal" class="pickup-modal-overlay" style="display:none;">
    <div class="pickup-modal-box">
        <div class="pickup-modal-icon">🛵</div>
        <div class="pickup-modal-title">Konfirmasi Pengantaran</div>
        <div class="pickup-modal-desc">
            Apakah mau mengantar pesanan dari <strong id="pickup-resto-name"></strong> untuk <strong id="pickup-receiver-name"></strong>?
        </div>
        <div class="pickup-modal-actions">
            <button type="button" class="pickup-btn-no" onclick="closePickModal()">Tidak</button>
            <button type="button" class="pickup-btn-yes" id="pickup-btn-confirm" onclick="confirmPickOrder()">Iya</button>
        </div>
    </div>
</div>

<script>
    let _pickOrderId   = null;
    let _pickOrderCard = null;

    function openPickModal(orderId, restoName, receiverName) {
        _pickOrderId   = orderId;
        _pickOrderCard = document.getElementById('foodhero-card-' + orderId);
        document.getElementById('pickup-resto-name').textContent = restoName;
        document.getElementById('pickup-receiver-name').textContent = receiverName;
        document.getElementById('pickup-modal').style.display = 'flex';
    }

    function closePickModal() {
        _pickOrderId   = null;
        _pickOrderCard = null;
        document.getElementById('pickup-modal').style.display = 'none';
        const confirmBtn = document.getElementById('pickup-btn-confirm');
        if (confirmBtn) {
            confirmBtn.disabled = false;
            confirmBtn.textContent = 'Iya';
        }
    }

    function confirmPickOrder() {
        if (!_pickOrderId) return;

        const confirmBtn = document.getElementById('pickup-btn-confirm');
        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Memproses...';

        const url   = '/courier/order/' + _pickOrderId + '/pick';
        const token = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type' : 'application/json',
                'X-CSRF-TOKEN' : token,
                'Accept'       : 'application/json',
            },
            body: JSON.stringify({})
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Animasi kartu keluar lalu alihkan ke halaman activity
                if (_pickOrderCard) {
                    _pickOrderCard.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
                    _pickOrderCard.style.opacity    = '0';
                    _pickOrderCard.style.transform  = 'translateX(40px)';
                }
                setTimeout(() => {
                    window.location.href = data.redirect_url || '{{ route("courier.activity") }}';
                }, 350);
            } else {
                alert('Gagal mengambil pesanan. Silakan coba lagi.');
                closePickModal();
            }
        })
        .catch(() => {
            alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
            closePickModal();
        });
    }

    // Tutup modal jika klik di area luar popup box
    document.getElementById('pickup-modal').addEventListener('click', function(e) {
        if (e.target === this) closePickModal();
    });
</script>

@endsection
