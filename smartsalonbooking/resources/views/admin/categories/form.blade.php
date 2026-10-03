@extends('layouts.admin')

@section('title', $category->exists ? 'Edit Category' : 'Add Category')
@section('page_title', $category->exists ? 'Edit Category' : 'Add Category')

@section('content')
<div class="glass-card" style="max-width:560px;">
    <form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
        @csrf
        @if ($category->exists) @method('PUT') @endif

        <div class="form-group">
            <label class="form-label" for="name">Category name</label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
            @error('name')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Description</label>
            <textarea id="description" name="description" class="form-control">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="flex-between">
            <a href="{{ route('admin.categories.index') }}" class="btn btn--ghost">Cancel</a>
            <button type="submit" class="btn btn--primary">{{ $category->exists ? 'Save Changes' : 'Create Category' }}</button>
        </div>
    </form>
</div>
@endsection
