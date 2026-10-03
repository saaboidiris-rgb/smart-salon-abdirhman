@extends('layouts.admin')

@section('title', 'Edit Customer')
@section('page_title', 'Edit Customer')

@section('content')
<div class="glass-card" style="max-width:560px;">
    <form method="POST" action="{{ route('admin.customers.update', $customer) }}">
        @csrf @method('PUT')

        <div class="form-group">
            <label class="form-label" for="name">Full name</label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $customer->user->name) }}" required>
            @error('name')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $customer->user->email) }}" required>
                @error('email')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="phone">Phone</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $customer->user->phone) }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="gender">Gender</label>
                <select id="gender" name="gender" class="form-control">
                    <option value="">Not set</option>
                    <option value="female" @selected(old('gender', $customer->gender) === 'female')>Female</option>
                    <option value="male" @selected(old('gender', $customer->gender) === 'male')>Male</option>
                    <option value="other" @selected(old('gender', $customer->gender) === 'other')>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="address">Address</label>
                <input type="text" id="address" name="address" class="form-control" value="{{ old('address', $customer->address) }}">
            </div>
        </div>

        <div class="flex-between">
            <a href="{{ route('admin.customers.index') }}" class="btn btn--ghost">Cancel</a>
            <button type="submit" class="btn btn--primary">Save Changes</button>
        </div>
    </form>
</div>
@endsection
