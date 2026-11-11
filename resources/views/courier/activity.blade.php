@extends('layouts.app')

@section('title', 'Activity')
@section('role', 'courier')
@section('page-heading', 'Activity')
@section('show-history', 'true')
@section('history-url', route('courier.history'))

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="foodhero-list" id="activity-list">
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

        <div class="foodhero-card" id="activity-card-{{ $pesanan->id }}">
            {{-- Top: image + meta (tanpa badge status) --}}
            <div class="foodhero-card-top">
                <img src="{{ asset($imageSrc) }}" alt="{{ $namaResto }}" class="foodhero-img">
                <div class="foodhero-meta">
                    <div class="foodhero-quest-number">Quest Number #{{ $questNumber }}</div>
                    <div class="foodhero-name">{{ $namaResto }}</div>
                </div>
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
            <button
                type="button"
                class="foodhero-pick-btn"
                onclick="handleDeliverOrder(event, {{ $pesanan->id }})"
            >
                Delivered
            </button>
        </div>
    @empty
        <div class="foodhero-empty-state" id="activity-empty-state">
            <h5>Belum ada aktivitas pesanan</h5>
            <p class="text-muted">Ambil pesanan di halaman Food Hero untuk mulai mengantar.</p>
            <a href="{{ route('courier.foodhero') }}" class="btn btn-sm btn-outline-success">Buka Food Hero</a>
        </div>
    @endforelse
</div>

<script>
    function handleDeliverOrder(event, orderId) {
        if (event) event.stopPropagation();

        const card = document.getElementById('activity-card-' + orderId);
        if (!card) return;

        // Cegah klik berulang
        const btn = card.querySelector('.foodhero-pick-btn');
        if (btn) {
            btn.disabled = true;
            btn.textContent = 'Menyelesaikan...';
        }

        // Langsung hilangkan pesanan dari halaman activity dengan animasi mulus
        const cardHeight = card.offsetHeight;
        const list       = document.getElementById('activity-list');
        const allCards  = Array.from(list.querySelectorAll('.foodhero-card'));
        const thisIndex = allCards.indexOf(card);
        const below     = allCards.slice(thisIndex + 1);

        // Efek animasi kartu yang di-delivered (fade-out & geser)
        card.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
        card.style.opacity    = '0';
        card.style.transform  = 'translateX(40px)';

        // Pesanan di bawahnya langsung naik ke atas mengisi ruang kosong
        const gap = 24;
        below.forEach(c => {
            c.style.transition = 'transform 0.4s ease';
            c.style.transform  = 'translateY(-' + (cardHeight + gap) + 'px)';
        });

        // Hapus elemen dari DOM
        setTimeout(() => {
            card.remove();

            below.forEach(c => {
                c.style.transition = 'none';
                c.style.transform  = '';
            });

            // Jika semua pesanan sudah selesai diantar, tampilkan empty state
            if (list && list.querySelectorAll('.foodhero-card').length === 0) {
                list.innerHTML = `
                    <div class="foodhero-empty-state" id="activity-empty-state">
                        <h5>Belum ada aktivitas pesanan</h5>
                        <p class="text-muted">Ambil pesanan di halaman Food Hero untuk mulai mengantar.</p>
                        <a href="{{ route('courier.foodhero') }}" class="btn btn-sm btn-outline-success">Buka Food Hero</a>
                    </div>
                `;
            }
        }, 400);

        // Kirim request ke server untuk update status pesanan menjadi 'completed'
        const url   = "{{ url('/courier/order') }}/" + orderId + "/deliver";
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
            if (!data.success) {
                console.error('Gagal memperbarui status pesanan:', data);
            }
        })
        .catch(err => {
            console.error('Kesalahan jaringan saat update status pesanan:', err);
        });
    }
</script>

@endsection
