<header class="navbar">
    <div class="navbar__inner">
        <a href="{{ route('home') }}" class="navbar__brand"><i class="fa-solid fa-wand-magic-sparkles text-primary"></i> {{ config('app.salon_name') }}<span>.</span></a>

        <nav class="navbar__links">
            <a href="{{ route('home') }}" class="navbar__link {{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
            <a href="{{ route('services.index') }}" class="navbar__link {{ request()->routeIs('services.*') ? 'is-active' : '' }}">Services</a>
            <a href="{{ route('team.index') }}" class="navbar__link {{ request()->routeIs('team.*') ? 'is-active' : '' }}">Team</a>
            <a href="{{ route('gallery.index') }}" class="navbar__link {{ request()->routeIs('gallery.*') ? 'is-active' : '' }}">Gallery</a>
            <a href="{{ route('pricing.index') }}" class="navbar__link {{ request()->routeIs('pricing.*') ? 'is-active' : '' }}">Pricing</a>
            <a href="{{ route('contact.index') }}" class="navbar__link {{ request()->routeIs('contact.*') ? 'is-active' : '' }}">Contact</a>
        </nav>

        <div class="navbar__actions">
            @auth
                @if (auth()->user()->canAccessAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn--ghost btn--sm">Admin Panel</a>
                @else
                    <a href="{{ route('customer.dashboard') }}" class="btn btn--ghost btn--sm">My Account</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn--outline btn--sm">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn--ghost btn--sm">Login</a>
            @endauth
            <a href="{{ route('booking.create') }}" class="btn btn--primary btn--sm">Book Now</a>
            <button type="button" class="navbar__toggle" data-mobile-toggle aria-label="Toggle menu"><i class="fa-solid fa-bars"></i></button>
        </div>
    </div>

    <div class="mobile-menu" data-mobile-menu>
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('services.index') }}">Services</a>
        <a href="{{ route('team.index') }}">Team</a>
        <a href="{{ route('gallery.index') }}">Gallery</a>
        <a href="{{ route('pricing.index') }}">Pricing</a>
        <a href="{{ route('testimonials.index') }}">Testimonials</a>
        <a href="{{ route('contact.index') }}">Contact</a>
        @auth
            @if (auth()->user()->canAccessAdmin())
                <a href="{{ route('admin.dashboard') }}">Admin Panel</a>
            @else
                <a href="{{ route('customer.dashboard') }}">My Account</a>
            @endif
        @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        @endauth
        <a href="{{ route('booking.create') }}">Book Now</a>
    </div>
</header>
