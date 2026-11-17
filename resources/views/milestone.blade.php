
@extends('layouts.app')

@section('title', 'Milestone')
@section('page-heading', 'Your Milestone')

@section('content')


    <div class="milestone-page">

        {{-- Total Food Saved --}}
        <div class="milestone-card">
            <div class="milestone-label">
                Total<br>
                Food<br>
                Saved
            </div>

            <div class="milestone-value">
                <strong>{{ $foodSaved === null ? '—' : $foodSaved }}</strong>
                <span>Kg</span>
            </div>
        </div>

        {{-- Total Points --}}
        <div class="milestone-card">
            <div class="milestone-label">
                Total<br>
                Points
            </div>

            <div class="milestone-value">
                <strong>{{ $points }}</strong>
                <span>Points</span>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="milestone-actions">

            {{-- UC14: fungsi milik anggota lain --}}
            <button type="button"
                    class="milestone-btn milestone-btn-locked"
                    disabled>
                Redeem Points
            </button>


{{-- UC07: Download Certificate --}}
<form action="{{ route('milestone.download') }}"
      method="POST"
      class="milestone-certificate-form">

    @csrf

    <button type="submit"
            class="milestone-btn {{ $eligible ? 'milestone-btn-ready' : 'milestone-btn-locked' }}"
            @disabled(!$eligible)>
        Download Certificate
    </button>

</form>


            @if ($foodSaved !== null && $foodSaved == 0 && $points == 0)
                <p class="milestone-message">
                    Belum ada kontribusi
                </p>
            @elseif (!$eligible)
                <p class="milestone-message">
                    Save 25 kg of food or collect 100 points
                    to unlock your certificate.
                </p>
            @endif

        </div>
    </div>
@endsection

@push('styles')
<style>
    /* CSS khusus halaman Milestone */

    .page-header > .d-flex {
        justify-content: center !important;
    }

    .page-header h2 {
        text-align: center;
    }

    .milestone-page {
        max-width: 380px;
        margin: 0 auto;
        padding: 8px 0 40px;
        font-family: 'Poppins', sans-serif;
    }

    .milestone-card {
        background: #f0f4e5;
        border: 2px solid #deded9;
        border-radius: 32px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.12);
        padding: 25px 24px;
        min-height: 145px;
        margin-bottom: 24px;

        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .milestone-label {
        color: #86a333;
        font-size: 23px;
        font-weight: 800;
        line-height: 1.2;
    }

    .milestone-value {
        color: #526f12;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex-shrink: 0;
    }

    .milestone-value strong {
        font-size: clamp(44px, 12vw, 62px);
        font-weight: 800;
        line-height: 1;
        letter-spacing: -2px;
        text-shadow: 2px 2px 0 #ffffff;
    }

    .milestone-value span {
        font-size: 19px;
        font-weight: 800;
        margin-top: 4px;
    }

    .milestone-actions {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        margin-top: 38px;
    }


    .milestone-certificate-form {
    width: 100%;
    display: flex;
    justify-content: center;
    }


    .milestone-btn {
        width: 80%;
        max-width: 245px;
        min-height: 38px;
        border: none;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 700;
        color: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.16);
    }

    .milestone-btn-ready {
        background: #9cba56;
        opacity: 1;
    }

    .milestone-btn-locked {
        background: #d4d4d4;
        opacity: 1;
    }

    .milestone-message {
        max-width: 280px;
        margin: 14px auto 0;
        color: #71864a;
        text-align: center;
        font-size: 12px;
        line-height: 1.5;
    }

    @media (max-width: 400px) {
        .milestone-card {
            padding: 20px 18px;
        }

        .milestone-label {
            font-size: 20px;
        }

        .milestone-value span {
            font-size: 17px;
        }
    }
</style>
@endpush
