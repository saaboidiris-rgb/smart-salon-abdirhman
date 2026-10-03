<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | {{ config('app.salon_name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
<div class="dash-shell">
    <aside class="dash-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="dash-sidebar__brand"><i class="fa-solid fa-wand-magic-sparkles text-primary"></i> {{ config('app.salon_name') }} Admin</a>
        <nav class="dash-sidebar__nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}"><i class="fa-solid fa-chart-line"></i> Dashboard</a>

            <div class="dash-sidebar__section">Bookings</div>
            <a href="{{ route('admin.appointments.index') }}" class="{{ request()->routeIs('admin.appointments.*') ? 'is-active' : '' }}"><i class="fa-solid fa-calendar-check"></i> Appointments</a>

            <div class="dash-sidebar__section">Catalog</div>
            <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'is-active' : '' }}"><i class="fa-solid fa-scissors"></i> Services</a>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'is-active' : '' }}"><i class="fa-solid fa-tags"></i> Categories</a>
            <a href="{{ route('admin.employees.index') }}" class="{{ request()->routeIs('admin.employees.*') ? 'is-active' : '' }}"><i class="fa-solid fa-user-tie"></i> Employees</a>

            <div class="dash-sidebar__section">People</div>
            <a href="{{ route('admin.customers.index') }}" class="{{ request()->routeIs('admin.customers.*') ? 'is-active' : '' }}"><i class="fa-solid fa-users"></i> Customers</a>

            <div class="dash-sidebar__section">Content</div>
            <a href="{{ route('admin.gallery.index') }}" class="{{ request()->routeIs('admin.gallery.*') ? 'is-active' : '' }}"><i class="fa-solid fa-images"></i> Gallery</a>
            <a href="{{ route('admin.testimonials.index') }}" class="{{ request()->routeIs('admin.testimonials.*') ? 'is-active' : '' }}"><i class="fa-solid fa-comment-dots"></i> Testimonials</a>
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
        <div class="dash-topbar">
            <h1>@yield('page_title', 'Dashboard')</h1>
            <div class="dash-user">
                <span>👋 {{ auth()->user()->name }} <span class="badge badge--active">{{ ucfirst(auth()->user()->role) }}</span></span>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert--success" data-flash="success" data-auto-dismiss>{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert--danger" data-flash="error" data-auto-dismiss>{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert--danger">
                <strong>Please fix the following:</strong>
                <ul class="mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<div id="toast-container"></div>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
