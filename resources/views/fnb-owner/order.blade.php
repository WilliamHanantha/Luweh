
@extends('layouts.app')

@section('title', 'Order')

@section('page-heading', 'Order')

@section('show-history', 'true')

@section('content')

<div class="order-list">

    @forelse ($items as $item)
        @php
            $makanan = $item->daftarMakanan;
            $harga   = $makanan ? $makanan->harga * $item->kuantitas : 0;
            $gambar  = ($makanan && $makanan->gambar) ? $makanan->gambar : 'assets/images/nasi-goreng.png';
        @endphp

        <div class="order-card" id="order-card-{{ $item->id }}">

            <img
                src="{{ asset($gambar) }}"
                alt="{{ $makanan->nama_makanan ?? '-' }}"
                class="order-image"
            >

            <div class="order-details">

                <div>
                    <div class="order-number">
                        Order Number #{{ str_pad($item->id, 2, '0', STR_PAD_LEFT) }}
                    </div>

                    <div class="order-menu">
                        {{ $makanan->nama_makanan ?? '-' }}
                    </div>

                    <div class="order-quantity">
                        Quantity: {{ $item->kuantitas }}
                    </div>
                </div>

                <div class="order-bottom">

                    <div class="order-price">
                        {{ number_format($harga, 0, ',', '.') }}
                    </div>

                    {{-- Tombol: klik buka popup konfirmasi --}}
                    <button
                        type="button"
                        class="order-status"
                        onclick="showPickupConfirm({{ $item->id }}, '{{ addslashes($makanan->nama_makanan ?? '-') }}')"
                    >
                        Picked Up by Driver
                    </button>

                </div>

            </div>

        </div>
    @empty
        <div class="order-empty">
            <p>Tidak ada pesanan yang menunggu pengambilan.</p>
        </div>
    @endforelse

</div>

{{-- ===== MODAL KONFIRMASI PICKUP ===== --}}
<div id="pickup-modal" class="pickup-modal-overlay" style="display:none;">
    <div class="pickup-modal-box">
        <div class="pickup-modal-icon">🛵</div>
        <div class="pickup-modal-title">Konfirmasi Pengambilan</div>
        <div class="pickup-modal-desc">
            Apakah pesanan <strong id="pickup-menu-name"></strong> sudah benar-benar diambil oleh kurir?
        </div>
        <div class="pickup-modal-actions">
            <button type="button" class="pickup-btn-no" onclick="closePickupModal()">Tidak</button>
            <button type="button" class="pickup-btn-yes" onclick="confirmPickup()">Iya</button>
        </div>
    </div>
</div>

<script>
    let _pickupItemId   = null;
    let _pickupItemCard = null;

    function showPickupConfirm(itemId, menuName) {
        _pickupItemId   = itemId;
        _pickupItemCard = document.getElementById('order-card-' + itemId);
        document.getElementById('pickup-menu-name').textContent = menuName;
        document.getElementById('pickup-modal').style.display = 'flex';
    }

    function closePickupModal() {
        _pickupItemId   = null;
        _pickupItemCard = null;
        document.getElementById('pickup-modal').style.display = 'none';
    }

    function confirmPickup() {
        if (!_pickupItemId) return;

        const url   = '/fnb-owner/order/item/' + _pickupItemId + '/pickup';
        const token = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type' : 'application/json',
                'X-CSRF-TOKEN' : token,
                'Accept'       : 'application/json',
            },
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Animasi fade-out lalu hapus card dari DOM
                if (_pickupItemCard) {
                    _pickupItemCard.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    _pickupItemCard.style.opacity    = '0';
                    _pickupItemCard.style.transform  = 'translateX(40px)';
                    setTimeout(() => {
                        _pickupItemCard.remove();

                        // Jika sudah tidak ada card lagi, tampilkan pesan kosong
                        const list = document.querySelector('.order-list');
                        if (list && list.querySelectorAll('.order-card').length === 0) {
                            list.innerHTML = '<div class="order-empty"><p>Tidak ada pesanan yang menunggu pengambilan.</p></div>';
                        }
                    }, 400);
                }
                closePickupModal();
            } else {
                alert('Gagal memperbarui status. Silakan coba lagi.');
                closePickupModal();
            }
        })
        .catch(() => {
            alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
            closePickupModal();
        });
    }

    // Tutup modal jika klik di luar box
    document.getElementById('pickup-modal').addEventListener('click', function(e) {
        if (e.target === this) closePickupModal();
    });
</script>

@endsection
