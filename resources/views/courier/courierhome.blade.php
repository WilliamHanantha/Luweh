@extends('layouts.app')

@section('title', 'Courier Home')

@section('role', 'courier')

@section('page-subtitle', 'Hello, Kim!')

@section('page-heading', 'Welcome, heroes!')

@section('content')

    <div class="courier-home-container">
        <div class="courier-hero-image-wrapper">
            <img
                src="{{ asset('assets/images/courier-hero.png') }}"
                alt="Welcome Heroes"
                class="courier-hero-img"
            >
        </div>

        <a href="{{ route('courier.foodhero') }}" class="btn-food-hero">
            Food Hero
        </a>
    </div>

@endsection
