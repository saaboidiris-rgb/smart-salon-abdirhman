@extends('layouts.app')

@section('title', 'Login')

@section('content')
<section class="section" style="padding-top:60px;">
    <div class="container" style="max-width:460px;">
        <div class="glass-card slide-up">
            <h2 class="text-center">Welcome back</h2>
            <p class="text-center mb-3">Log in to manage your appointments.</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Email address</label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                    @error('email')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="flex-between mb-3">
                    <label class="form-check">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <a href="{{ route('password.request') }}" class="text-primary">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn--primary btn--block">Log In</button>
            </form>

            <p class="text-center mt-3 mb-0">Don't have an account? <a href="{{ route('register') }}">Create one</a></p>

            <div class="alert alert--info mt-3 mb-0">
                <strong>Demo logins:</strong><br>
                Admin: admin@smartsalon.test / password<br>
                Receptionist: receptionist@smartsalon.test / password<br>
                Customer: customer@smartsalon.test / password
            </div>
        </div>
    </div>
</section>
@endsection
