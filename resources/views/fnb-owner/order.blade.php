
@extends('layouts.app')

@section('title', 'Order')

@section('page-heading', 'Order')

@section('show-history', 'true')

@section('content')

<div class="order-list" id="order-list">

    @forelse ($pesanans as $pesanan)
        @php
            // Hard-code nama customer sampai sistem user dibuat
            $customerName = 'Lafuan Igna';

            // Nomor order dari id pesanan
            $orderNumber = str_pad($pesanan->id, 2, '0', STR_PAD_LEFT);

            // Total harga dari pesanan
            $total = $pesanan->total_harga;
        @endphp

        <div class="order-card" id="order-card-{{ $pesanan->id }}">

            {{-- Baris atas: Order # + Customer --}}
            <div class="order-card-header">
                <div class="order-number">Order #{{ $orderNumber }}</div>
                <div class="order-customer">Customer: <strong>{{ $customerName }}</strong></div>
            </div>

            {{-- Baris tengah: daftar makanan (kiri) + total (kanan) --}}
            <div class="order-card-body">

                <ul class="order-items-list">
                    @foreach ($pesanan->itemPesanans as $item)
                        @php
                            $namaMakanan = $item->daftarMakanan->nama_makanan ?? '-';
                        @endphp
                        <li>{{ $namaMakanan }} : {{ $item->kuantitas }}</li>
                    @endforeach
                </ul>

                <div class="order-total-section">
                    <div class="order-total-label">Total</div>
                    <div class="order-total-price">Rp {{ number_format($total, 0, ',', '.') }}</div>
                </div>

            </div>

            {{-- Tombol Picked Up by Driver --}}
            <button
                type="button"
                class="order-pickup-btn"
                onclick="showPickupConfirm({{ $pesanan->id }}, '{{ str_pad($pesanan->id, 2, '0', STR_PAD_LEFT) }}')"
            >
                Picked Up by Driver
            </button>

        </div>
    @empty
        <div class="order-empty" id="order-empty-state">
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
            Apakah pesanan <strong id="pickup-order-label"></strong> sudah benar-benar diambil oleh kurir?
        </div>
        <div class="pickup-modal-actions">
            <button type="button" class="pickup-btn-no" onclick="closePickupModal()">Tidak</button>
            <button type="button" class="pickup-btn-yes" onclick="confirmPickup()">Iya</button>
        </div>
    </div>
</div>

<script>
    let _pickupPesananId   = null;
    let _pickupCard        = null;

    function showPickupConfirm(pesananId, orderLabel) {
        _pickupPesananId = pesananId;
        _pickupCard      = document.getElementById('order-card-' + pesananId);
        document.getElementById('pickup-order-label').textContent = 'Order #' + orderLabel;
        document.getElementById('pickup-modal').style.display = 'flex';
    }

    function closePickupModal() {
        _pickupPesananId = null;
        _pickupCard      = null;
        document.getElementById('pickup-modal').style.display = 'none';
    }

    function confirmPickup() {
        if (!_pickupPesananId) return;

        const url   = "{{ url('/fnb-owner/order/pesanan') }}/" + _pickupPesananId + "/pickup";
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
                // Animasi fade-out + slide-up lalu hapus card dari DOM
                if (_pickupCard) {
                    const cardHeight = _pickupCard.offsetHeight;
                    const list       = document.getElementById('order-list');

                    // Ambil semua kartu setelah kartu ini untuk animasi naik
                    const allCards  = Array.from(list.querySelectorAll('.order-card'));
                    const thisIndex = allCards.indexOf(_pickupCard);
                    const below     = allCards.slice(thisIndex + 1);

                    // Fade-out kartu yang di-pickup
                    _pickupCard.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
                    _pickupCard.style.opacity    = '0';
                    _pickupCard.style.transform  = 'translateX(40px)';

                    // Geser kartu di bawahnya ke atas secara smooth
                    const gap = 18; // sesuai gap di .order-list
                    below.forEach(card => {
                        card.style.transition = 'transform 0.4s ease';
                        card.style.transform  = 'translateY(-' + (cardHeight + gap) + 'px)';
                    });

                    setTimeout(() => {
                        _pickupCard.remove();

                        // Reset transform kartu di bawah setelah dihapus
                        below.forEach(card => {
                            card.style.transition = 'none';
                            card.style.transform  = '';
                        });

                        // Jika tidak ada kartu lagi, tampilkan empty state
                        if (list && list.querySelectorAll('.order-card').length === 0) {
                            list.innerHTML = '<div class="order-empty"><p>Tidak ada pesanan yang menunggu pengambilan.</p></div>';
                        }
                    }, 420);
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
