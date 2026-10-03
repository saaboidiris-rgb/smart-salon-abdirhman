@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<section class="section">
    <div class="container">
        <div class="section__header">
            <span class="section__eyebrow">Get in touch</span>
            <h2 class="section__title">We'd Love to Hear From You</h2>
        </div>

        <div class="grid grid-2">
            <div class="glass-card">
                <h3>Send a message</h3>
                <form method="POST" action="{{ route('contact.submit') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required>
                        @error('subject')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="message">Message</label>
                        <textarea id="message" name="message" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                        @error('message')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <button type="submit" class="btn btn--primary btn--block">Send Message</button>
                </form>
            </div>

            <div class="glass-card">
                <h3>Visit the studio</h3>
                <p><i class="fa-solid fa-location-dot text-primary" style="width:20px;"></i> {{ \App\Models\Setting::get('address', '123 Blossom Avenue, Nairobi') }}</p>
                <p><i class="fa-solid fa-phone text-primary" style="width:20px;"></i> {{ \App\Models\Setting::get('contact_phone', '+254 700 000 000') }}</p>
                <p><i class="fa-solid fa-envelope text-primary" style="width:20px;"></i> {{ \App\Models\Setting::get('contact_email', 'hello@smartsalon.test') }}</p>
                <p><i class="fa-solid fa-clock text-primary" style="width:20px;"></i> {{ \App\Models\Setting::get('opening_hours', 'Mon - Sat, 9:00 AM - 7:00 PM') }}</p>
                <div style="border-radius:var(--radius); overflow:hidden; margin-top:20px;">
                    <img src="https://images.unsplash.com/photo-1487958449943-2429e8be8625?auto=format&fit=crop&w=600&q=60" alt="Map placeholder">
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
