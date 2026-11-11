@extends('layouts.app')

@section('title', 'Add Menu')

@section('page-heading', 'Add Menu')

@section('content')
<div class="create-menu-wrapper">
    {{-- Notifikasi Error Validasi --}}
    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger rounded-4 mb-3 border-0" role="alert">
            <ul class="mb-0 small ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/fnb-owner/store') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column justify-content-between flex-grow-1">
        @csrf

        <div>
            {{-- Section Title --}}
            <h3 class="create-section-title">Menu Detail</h3>

            {{-- 1. Photo Upload --}}
            <div class="form-group-custom">
                <label class="form-label-custom">Photo</label>
                <p class="form-sublabel-custom">Upload photo of the menu</p>

                <div class="photo-upload-box" id="uploadBox" title="Klik untuk upload foto">
                    <div class="photo-upload-icon" id="uploadIcon">
                        <svg width="34" height="30" viewBox="0 0 24 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.5 4L6 6.5H3C1.9 6.5 1 7.4 1 8.5V18.5C1 19.6 1.9 20.5 3 20.5H21C22.1 20.5 23 19.6 23 18.5V8.5C23 7.4 22.1 6.5 21 6.5H18L16.5 4H7.5Z" stroke="#8ca636" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="13.5" r="4" stroke="#8ca636" stroke-width="2"/>
                        </svg>
                    </div>
                    <img id="previewImg" class="preview-img" alt="Preview Foto">
                </div>

                {{-- Hidden File Input --}}
                <input type="file" name="image" id="imageInput" accept="image/*" class="d-none">
            </div>

            {{-- 2. Name --}}
            <div class="form-group-custom">
                <label class="form-label-custom" for="nama_makanan">Name</label>
                <input type="text"
                       name="nama_makanan"
                       id="nama_makanan"
                       class="form-input-underline"
                       placeholder="Ex: Chicken Teriyaki Set"
                       value="{{ old('nama_makanan') }}"
                       required
                       autocomplete="off">
            </div>

            {{-- 3. Description --}}
            <div class="form-group-custom">
                <label class="form-label-custom" for="deskripsi">Description</label>
                <input type="text"
                       name="deskripsi"
                       id="deskripsi"
                       class="form-input-underline"
                       placeholder="Ex: Chicken Teriyaki With Rice"
                       value="{{ old('deskripsi') }}"
                       required
                       autocomplete="off">
            </div>

            {{-- 4. Price (Rp) --}}
            <div class="form-group-custom">
                <label class="form-label-custom" for="harga">Price (Rp)</label>
                <input type="number"
                       name="harga"
                       id="harga"
                       class="form-input-underline"
                       placeholder="0"
                       min="0"
                       step="any"
                       value="{{ old('harga') }}"
                       required
                       autocomplete="off">
            </div>
        </div>

        {{-- Tombol Save --}}
        <div class="text-center pt-5 pb-3">
            <button type="submit" class="btn-save-menu">
                Save
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const uploadBox = document.getElementById('uploadBox');
        const imageInput = document.getElementById('imageInput');
        const previewImg = document.getElementById('previewImg');
        const uploadIcon = document.getElementById('uploadIcon');

        // Klik box untuk trigger file input
        uploadBox.addEventListener('click', function () {
            imageInput.click();
        });

        // Tampilkan preview ketika file gambar dipilih
        imageInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (evt) {
                    previewImg.src = evt.target.result;
                    previewImg.style.display = 'block';
                    uploadIcon.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush

@endsection
