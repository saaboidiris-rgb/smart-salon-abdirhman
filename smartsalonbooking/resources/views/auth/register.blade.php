@extends('layouts.app')

@section('title', 'Create an Account')

@section('content')
<section class="section" style="padding-top:60px;">
    <div class="container" style="max-width:520px;">
        <div class="glass-card slide-up">
            <h2 class="text-center">Create your account</h2>
            <p class="text-center mb-3">Sign up to book and manage your appointments online.</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="name">Full name</label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                    @error('name')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="email">Email address</label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="phone">Phone number</label>
                        <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="+254 7XX XXX XXX" required>
                        @error('phone')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        <span class="form-hint">At least 8 characters, with letters and numbers.</span>
                        @error('password')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirm password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn--primary btn--block">Create Account</button>
            </form>

            <p class="text-center mt-3 mb-0">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
        </div>
    </div>
</section>
@endsection
