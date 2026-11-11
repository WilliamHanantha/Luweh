<div class="offcanvas offcanvas-end" tabindex="-1" id="sidebar">

    <div class="offcanvas-header">
        <h5 class="fw-bold">LUWEH Menu</h5>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas">
        </button>
    </div>

    <div class="offcanvas-body">
        @php
            $isCourier = request()->is('courier*') || (View::hasSection('role') && trim(View::yieldContent('role')) === 'courier');
        @endphp

        @if ($isCourier)
            <a href="{{ route('courier.home') }}" class="d-block mb-3 {{ request()->routeIs('courier.home', 'courier.home.alt') ? 'fw-bold text-success' : 'text-dark text-decoration-none' }}">
                Home
            </a>

            <a href="{{ route('courier.foodhero') }}" class="d-block mb-3 {{ request()->routeIs('courier.foodhero') ? 'fw-bold text-success' : 'text-dark text-decoration-none' }}">
                Food Hero
            </a>

            <a href="{{ route('courier.activity') }}" class="d-block mb-3 {{ request()->routeIs('courier.activity') ? 'fw-bold text-success' : 'text-dark text-decoration-none' }}">
                Activity
            </a>

            <a href="{{ route('courier.redeem') }}" class="d-block mb-3 {{ request()->routeIs('courier.redeem') ? 'fw-bold text-success' : 'text-dark text-decoration-none' }}">
                Redeem Point
            </a>
        @else
            <a href="{{ url('/fnb-owner') }}" class="d-block mb-3 {{ request()->is('fnb-owner') ? 'fw-bold text-success' : 'text-dark text-decoration-none' }}">
                Your Menu
            </a>

            <a href="{{ url('/fnb-owner/order') }}" class="d-block mb-3 {{ request()->is('*order*') ? 'fw-bold text-success' : 'text-dark text-decoration-none' }}">
                Order
            </a>

            <a href="{{ url('/profile') }}" class="d-block mb-3 text-dark text-decoration-none">
                Profile
            </a>
        @endif

    </div>
</div>
