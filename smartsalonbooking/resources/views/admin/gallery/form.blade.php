@extends('layouts.admin')

@section('title', $image->exists ? 'Edit Image' : 'Add Image')
@section('page_title', $image->exists ? 'Edit Image' : 'Add Image')

@section('content')
<div class="glass-card" style="max-width:520px;">
    <form method="POST" action="{{ $image->exists ? route('admin.gallery.update', $image) : route('admin.gallery.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($image->exists) @method('PUT') @endif

        <div class="form-group">
            <label class="form-label" for="title">Title (optional)</label>
            <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $image->title) }}">
        </div>
        <div class="form-group">
            <label class="form-label" for="category">Category (optional)</label>
            <input type="text" id="category" name="category" class="form-control" value="{{ old('category', $image->category) }}" placeholder="e.g. Hair, Nails, Makeup">
        </div>
        <div class="form-group">
            <label class="form-label" for="image">Image</label>
            <input type="file" id="image" name="image" class="form-control input-file @error('image') is-invalid @enderror" accept="image/png, image/jpeg">
            <span class="form-hint">JPG or PNG, max 2MB.</span>
            @error('image')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        @if ($image->image)
            <img src="{{ asset('storage/'.$image->image) }}" alt="Current" style="width:120px;border-radius:var(--radius-sm);margin-bottom:16px;">
        @endif

        <div class="flex-between">
            <a href="{{ route('admin.gallery.index') }}" class="btn btn--ghost">Cancel</a>
            <button type="submit" class="btn btn--primary">{{ $image->exists ? 'Save Changes' : 'Upload' }}</button>
        </div>
    </form>
</div>
@endsection
