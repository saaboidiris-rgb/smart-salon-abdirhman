@extends('layouts.admin')

@section('title', $testimonial->exists ? 'Edit Testimonial' : 'Add Testimonial')
@section('page_title', $testimonial->exists ? 'Edit Testimonial' : 'Add Testimonial')

@section('content')
<div class="glass-card" style="max-width:560px;">
    <form method="POST" action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($testimonial->exists) @method('PUT') @endif

        <div class="form-group">
            <label class="form-label" for="customer_name">Customer name</label>
            <input type="text" id="customer_name" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name', $testimonial->customer_name) }}" required>
            @error('customer_name')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="rating">Rating</label>
            <select id="rating" name="rating" class="form-control">
                @for ($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" @selected(old('rating', $testimonial->rating ?? 5) == $i)>{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
                @endfor
            </select>
        </div>

        <div class="form-group">
            <label class="form-label" for="message">Message</label>
            <textarea id="message" name="message" class="form-control @error('message') is-invalid @enderror" required>{{ old('message', $testimonial->message) }}</textarea>
            @error('message')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="active" @selected(old('status', $testimonial->status ?? 'active') === 'active')>Active</option>
                    <option value="inactive" @selected(old('status', $testimonial->status) === 'inactive')>Inactive</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="customer_photo">Photo (optional)</label>
                <input type="file" id="customer_photo" name="customer_photo" class="form-control input-file" accept="image/png, image/jpeg">
            </div>
        </div>

        <div class="flex-between">
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn--ghost">Cancel</a>
            <button type="submit" class="btn btn--primary">{{ $testimonial->exists ? 'Save Changes' : 'Add Testimonial' }}</button>
        </div>
    </form>
</div>
@endsection
