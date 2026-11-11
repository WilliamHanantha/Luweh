@extends('layouts.app')

@section('title', 'Your Menu')

@section('page-heading', 'Your Menu')

@section('content')
@php
    // Ambil data jika belum dikirim dari controller
    $daftarMakanan = $daftarMakanan ?? \App\Models\DaftarMakanan::all();

    // Filter daftar makanan berdasarkan status (0 = Nonaktif, 1 = Aktif)
    $nonaktifMenus = $daftarMakanan->filter(fn($item) => empty($item->status) || $item->status == 0 || $item->status === false);
    $aktifMenus = $daftarMakanan->filter(fn($item) => !empty($item->status) && ($item->status == 1 || $item->status === true));
@endphp

<div class="menu-page-wrapper">
    {{-- Alert Notifikasi Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- TAB NAVIGATION (Nonaktif / Aktif) --}}
    <ul class="nav menu-tabs justify-content-center mb-4" id="menuTab" role="tablist">
        <li class="nav-item flex-fill text-center" role="presentation">
            <button class="nav-link active w-100" id="nonaktif-tab" data-bs-toggle="tab" data-bs-target="#nonaktif-pane" type="button" role="tab" aria-controls="nonaktif-pane" aria-selected="true">
                Nonaktif
            </button>
        </li>
        <li class="nav-item flex-fill text-center" role="presentation">
            <button class="nav-link w-100" id="aktif-tab" data-bs-toggle="tab" data-bs-target="#aktif-pane" type="button" role="tab" aria-controls="aktif-pane" aria-selected="false">
                Aktif
            </button>
        </li>
    </ul>

    {{-- TAB CONTENT / RECYCLE VIEW --}}
    <div class="tab-content" id="menuTabContent">

        {{-- TAB PANE: NONAKTIF --}}
        <div class="tab-pane fade show active" id="nonaktif-pane" role="tabpanel" aria-labelledby="nonaktif-tab" tabindex="0">
            <div class="menu-list">
                @forelse ($nonaktifMenus as $item)
                    @php
                        $imageSrc = asset('assets/images/food-placeholder.svg');
                        if (!empty($item->image)) {
                            if (Illuminate\Support\Str::startsWith($item->image, ['http://', 'https://'])) {
                                $imageSrc = $item->image;
                            } elseif (file_exists(public_path($item->image))) {
                                $imageSrc = asset($item->image);
                            } elseif (file_exists(public_path('storage/' . $item->image))) {
                                $imageSrc = asset('storage/' . $item->image);
                            } else {
                                $imageSrc = asset($item->image);
                            }
                        }
                    @endphp

                    {{-- Card Menu Item --}}
                    <div class="menu-card d-flex align-items-center mb-3">
                        {{-- Foto Makanan --}}
                        <div class="menu-img-wrapper flex-shrink-0">
                            <img src="{{ $imageSrc }}"
                                 alt="{{ $item->nama_makanan }}"
                                 class="menu-img"
                                 onerror="this.onerror=null;this.src='{{ asset('assets/images/food-placeholder.svg') }}';">
                        </div>

                        {{-- Detail Makanan --}}
                        <div class="menu-info flex-grow-1 ms-3">
                            <h4 class="menu-name">{{ $item->nama_makanan }}</h4>
                            <p class="menu-weight mb-2">
                                {{ $item->deskripsi ?? 'Weight (per item): -' }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="menu-price">
                                    {{ number_format($item->harga, 0, ',', '.') }}
                                </span>

                                {{-- Tombol Post -> Arahkan ke Halaman Set Menu Quantity --}}
                                <a href="{{ url('/fnb-owner/' . $item->id . '/set-quantity') }}" class="btn-post text-decoration-none">
                                    Post
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state text-center py-5">
                        <img src="{{ asset('assets/images/food-placeholder.svg') }}" alt="Empty" style="width: 80px; opacity: 0.5; margin-bottom: 12px;">
                        <h5 class="fw-semibold text-secondary">Belum ada menu nonaktif</h5>
                        <p class="text-muted small">Tambahkan menu baru menggunakan tombol kuning (+) di bawah.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- TAB PANE: AKTIF --}}
        <div class="tab-pane fade" id="aktif-pane" role="tabpanel" aria-labelledby="aktif-tab" tabindex="0">
            <div class="menu-list">
                @forelse ($aktifMenus as $item)
                    @php
                        $imageSrc = asset('assets/images/food-placeholder.svg');
                        if (!empty($item->image)) {
                            if (Illuminate\Support\Str::startsWith($item->image, ['http://', 'https://'])) {
                                $imageSrc = $item->image;
                            } elseif (file_exists(public_path($item->image))) {
                                $imageSrc = asset($item->image);
                            } elseif (file_exists(public_path('storage/' . $item->image))) {
                                $imageSrc = asset('storage/' . $item->image);
                            } else {
                                $imageSrc = asset($item->image);
                            }
                        }
                    @endphp

                    {{-- Card Menu Item --}}
                    <div class="menu-card d-flex align-items-center mb-3">
                        {{-- Foto Makanan --}}
                        <div class="menu-img-wrapper flex-shrink-0">
                            <img src="{{ $imageSrc }}"
                                 alt="{{ $item->nama_makanan }}"
                                 class="menu-img"
                                 onerror="this.onerror=null;this.src='{{ asset('assets/images/food-placeholder.svg') }}';">
                        </div>

                        {{-- Detail Makanan --}}
                        <div class="menu-info flex-grow-1 ms-3">
                            <h4 class="menu-name">{{ $item->nama_makanan }}</h4>
                            <p class="menu-weight mb-1">
                                {{ $item->deskripsi ?? 'Weight (per item): -' }}
                            </p>
                            <p class="menu-stock mb-2">
                                Stock: <strong>{{ $item->stok }}</strong>
                            </p>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="menu-price">
                                    {{ number_format($item->harga, 0, ',', '.') }}
                                </span>

                                {{-- Tombol Nonaktifkan --}}
                                <form action="{{ url('/fnb-owner/' . $item->id . '/toggle-status') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn-post btn-unpost">
                                        Nonaktifkan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state text-center py-5">
                        <img src="{{ asset('assets/images/food-placeholder.svg') }}" alt="Empty" style="width: 80px; opacity: 0.5; margin-bottom: 12px;">
                        <h5 class="fw-semibold text-secondary">Belum ada menu aktif</h5>
                        <p class="text-muted small">Menu yang di-post dari tab Nonaktif akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- FLOATING ACTION BUTTON (Add Menu) --}}
    <a href="/fnb-owner/create" class="fab-btn" title="Tambah Menu">
        <span>+</span>
    </a>
</div>

@endsection