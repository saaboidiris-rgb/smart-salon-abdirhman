@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<section class="section" style="padding-top:60px;">
    <div class="container" style="max-width:460px;">
        <div class="glass-card slide-up">
            <h2 class="text-center">Choose a new password</h2>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label class="form-label" for="email">Email address</label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $email) }}" required autofocus>
                    @error('email')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">New password</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirm new password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn--primary btn--block">Reset Password</button>
            </form>
        </div>
    </div>
</section>
@endsection
