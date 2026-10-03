@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<section class="section" style="padding-top:60px;">
    <div class="container" style="max-width:460px;">
        <div class="glass-card slide-up">
            <h2 class="text-center">Forgot your password?</h2>
            <p class="text-center mb-3">Enter your email and we'll send you a reset link.</p>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Email address</label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                    @error('email')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="btn btn--primary btn--block">Send Reset Link</button>
            </form>

            <p class="text-center mt-3 mb-0"><a href="{{ route('login') }}">← Back to login</a></p>
        </div>
    </div>
</section>
@endsection
