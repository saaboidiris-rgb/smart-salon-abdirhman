<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.salon_name')) | {{ config('app.salon_name') }}</title>
    <meta name="description" content="@yield('meta_description', 'Book premium salon services online in minutes.')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>

    @include('components.navbar')

    @if (session('success'))
        <div class="container mt-3"><div class="alert alert--success" data-flash="success" data-auto-dismiss>{{ session('success') }}</div></div>
    @endif
    @if (session('error'))
        <div class="container mt-3"><div class="alert alert--danger" data-flash="error" data-auto-dismiss>{{ session('error') }}</div></div>
    @endif

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    <div id="toast-container"></div>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
