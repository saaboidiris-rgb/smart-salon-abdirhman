<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Account') | {{ config('app.salon_name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
<div class="dash-shell">
    <aside class="dash-sidebar">
        <a href="{{ route('home') }}" class="dash-sidebar__brand"><i class="fa-solid fa-wand-magic-sparkles text-primary"></i> {{ config('app.salon_name') }}</a>
        <nav class="dash-sidebar__nav">
            <a href="{{ route('customer.dashboard') }}" class="{{ request()->routeIs('customer.dashboard') ? 'is-active' : '' }}"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="{{ route('customer.appointments.index') }}" class="{{ request()->routeIs('customer.appointments.*') ? 'is-active' : '' }}"><i class="fa-solid fa-calendar-check"></i> My Appointments</a>
            <a href="{{ route('booking.create') }}"><i class="fa-solid fa-plus"></i> Book New Service</a>
            <a href="{{ route('customer.profile.edit') }}" class="{{ request()->routeIs('customer.profile.*') ? 'is-active' : '' }}"><i class="fa-solid fa-user"></i> Profile</a>
        </nav>
        <div class="dash-sidebar__footer">
            <a href="{{ route('home') }}"><i class="fa-solid fa-arrow-left"></i> Back to site</a>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button type="submit" class="btn btn--outline btn--sm w-full">Logout</button>
            </form>
        </div>
    </aside>

    <div class="dash-main">
        @if (session('success'))
            <div class="alert alert--success" data-flash="success" data-auto-dismiss>{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert--danger" data-flash="error" data-auto-dismiss>{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

<div id="toast-container"></div>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
