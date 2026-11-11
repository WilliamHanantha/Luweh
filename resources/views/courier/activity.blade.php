@extends('layouts.app')

@section('title', 'Activity')
@section('role', 'courier')
@section('page-heading', 'Activity')

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
            $status = $pesanan->status_courier ?? $pesanan->status ?? 'on-delivery';
            $normalizedStatus = strtolower(str_replace([' ', '_'], '-', $status));
            $statusClass = 'status-' . $normalizedStatus;
            $questNumber = str_pad($pesanan->id, 2, '0', STR_PAD_LEFT);
            $isFinished = in_array($normalizedStatus, ['finished', 'selesai']);
        @endphp

        <div class="foodhero-card" id="activity-card-{{ $pesanan->id }}">
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

            {{-- Action Button: Delivered --}}
            @if ($isFinished)
                <button type="button" class="foodhero-pick-btn" disabled>Delivered</button>
            @else
                <button
                    type="button"
                    class="foodhero-pick-btn"
                    onclick="openDeliverModal({{ $pesanan->id }}, '{{ addslashes($namaResto) }}', '{{ addslashes($receiverName) }}')"
                >
                    Delivered
                </button>
            @endif
        </div>
    @empty
        <div class="foodhero-empty-state">
            <h5>Belum ada aktivitas pesanan</h5>
            <p class="text-muted">Ambil pesanan di halaman Food Hero untuk mulai mengantar.</p>
            <a href="{{ route('courier.foodhero') }}" class="btn btn-sm btn-outline-success">Buka Food Hero</a>
        </div>
    @endforelse
</div>

{{-- ===== MODAL KONFIRMASI DELIVERED (ACTIVITY) ===== --}}
<div id="deliver-modal" class="pickup-modal-overlay" style="display:none;">
    <div class="pickup-modal-box">
        <div class="pickup-modal-icon">🛵</div>
        <div class="pickup-modal-title">Konfirmasi Pengantaran</div>
        <div class="pickup-modal-desc">
            Apakah pesanan untuk <strong id="deliver-receiver-name"></strong> sudah selesai/sampai?
        </div>
        <div class="pickup-modal-actions">
            <button type="button" class="pickup-btn-no" onclick="closeDeliverModal()">Tidak</button>
            <button type="button" class="pickup-btn-yes" id="deliver-btn-confirm" onclick="confirmDeliverOrder()">Iya</button>
        </div>
    </div>
</div>

<script>
    let _deliverOrderId   = null;
    let _deliverOrderCard = null;

    function openDeliverModal(orderId, restoName, receiverName) {
        _deliverOrderId   = orderId;
        _deliverOrderCard = document.getElementById('activity-card-' + orderId);
        document.getElementById('deliver-receiver-name').textContent = receiverName;
        document.getElementById('deliver-modal').style.display = 'flex';
    }

    function closeDeliverModal() {
        _deliverOrderId   = null;
        _deliverOrderCard = null;
        document.getElementById('deliver-modal').style.display = 'none';
        const confirmBtn = document.getElementById('deliver-btn-confirm');
        if (confirmBtn) {
            confirmBtn.disabled = false;
            confirmBtn.textContent = 'Iya';
        }
    }

    function confirmDeliverOrder() {
        if (!_deliverOrderId) return;

        const confirmBtn = document.getElementById('deliver-btn-confirm');
        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Memproses...';

        const url   = '/courier/order/' + _deliverOrderId + '/deliver';
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
                closeDeliverModal();
                // Update kartu secara dinamis: ubah badge ke status-finished dan tombol ke disabled
                if (_deliverOrderCard) {
                    const badge = _deliverOrderCard.querySelector('.foodhero-badge');
                    if (badge) {
                        badge.className = 'foodhero-badge status-finished';
                        badge.textContent = 'finished';
                    }
                    const btn = _deliverOrderCard.querySelector('.foodhero-pick-btn');
                    if (btn) {
                        btn.disabled = true;
                        btn.removeAttribute('onclick');
                    }
                }
            } else {
                alert('Gagal menyelesaikan status pesanan. Silakan coba lagi.');
                closeDeliverModal();
            }
        })
        .catch(() => {
            alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
            closeDeliverModal();
        });
    }

    // Tutup modal jika klik di area luar popup box
    document.getElementById('deliver-modal').addEventListener('click', function(e) {
        if (e.target === this) closeDeliverModal();
    });
</script>

@endsection
