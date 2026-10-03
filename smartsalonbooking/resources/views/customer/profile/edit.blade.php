@extends('layouts.dashboard')

@section('title', 'My Profile')

@section('content')
<div class="dash-topbar"><h1>My Profile</h1></div>

<div class="glass-card" style="max-width:620px;">
    <form method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="flex-center mb-3">
            <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://i.pravatar.cc/120?u='.$user->id }}" alt="Avatar" style="width:88px;height:88px;border-radius:50%;object-fit:cover;">
        </div>
        <div class="form-group">
            <label class="form-label" for="avatar">Profile picture</label>
            <input type="file" id="avatar" name="avatar" class="form-control input-file @error('avatar') is-invalid @enderror" accept="image/png, image/jpeg">
            <span class="form-hint">JPG or PNG, max 2MB.</span>
            @error('avatar')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="name">Full name</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                @error('name')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                @error('email')<span class="form-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="phone">Phone</label>
                <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" required>
                @error('phone')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="gender">Gender</label>
                <select id="gender" name="gender" class="form-control">
                    <option value="">Prefer not to say</option>
                    <option value="female" @selected(old('gender', $user->customer?->gender) === 'female')>Female</option>
                    <option value="male" @selected(old('gender', $user->customer?->gender) === 'male')>Male</option>
                    <option value="other" @selected(old('gender', $user->customer?->gender) === 'other')>Other</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="address">Address</label>
            <input type="text" id="address" name="address" class="form-control" value="{{ old('address', $user->customer?->address) }}">
        </div>

        <button type="submit" class="btn btn--primary">Save Changes</button>
    </form>
</div>
@endsection
