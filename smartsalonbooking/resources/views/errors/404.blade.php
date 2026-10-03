@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<section class="section text-center">
    <div class="container">
        <div class="glass-card" style="max-width:520px; margin:0 auto; padding:60px 40px;">
            <h1 style="font-size:4rem;">404</h1>
            <h2>Page Not Found</h2>
            <p class="mb-3">The page you're looking for doesn't exist or may have moved.</p>
            <a href="{{ route('home') }}" class="btn btn--primary">Back to Home</a>
        </div>
    </div>
</section>
@endsection
